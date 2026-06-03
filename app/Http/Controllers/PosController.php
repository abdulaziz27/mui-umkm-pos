<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\Shift;

class PosController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        
        $activeShift = Shift::where('tenant_id', $tenant->id)
            ->where('status', 'open')
            ->first();

        if (!$activeShift) {
            return redirect()->route('pos.shift.show');
        }
        
        $categories = $tenant->categories()->where('is_active', true)->get();
        $products = $tenant->products()
            ->where('is_active', true)
            ->with('category')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'stock_quantity' => $product->stock_quantity,
                    'type' => $product->type,
                    'category_id' => $product->category_id,
                    'image_url' => $product->image_path ? Storage::url($product->image_path) : null,
                ];
            });

        $promos = $tenant->promos()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now()->startOfDay());
            })
            ->get();

        return view('pos.index', compact('categories', 'products', 'tenant', 'promos'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'cart' => 'required|string',
            'payment_method' => 'required|in:cash,qris,transfer',
            'discount_type' => 'nullable|in:none,percentage,nominal',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        $cart = json_decode($request->cart, true);
        
        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong.');
        }

        $tenant = auth()->user()->tenant;
        $feeType = $tenant->platform_fee_type ?? 'percentage';
        $mdrPercentage = $tenant->mdr_fee_percentage ?? 0;

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $itemsData = [];

            foreach ($cart as $item) {
                $product = Product::where('tenant_id', $tenant->id)->findOrFail($item['id']);
                
                // Deduct stock if physical
                if ($product->type === 'physical') {
                    if ($product->stock_quantity !== null && $product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi. Sisa stok: {$product->stock_quantity}");
                    }
                    if ($product->stock_quantity !== null) {
                        $product->decrement('stock_quantity', $item['quantity']);
                    }
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'id' => Str::uuid()->toString(),
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'type' => $product->type,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $discountType = $request->discount_type ?? 'none';
            $discountValue = (float) ($request->discount_value ?? 0);
            $discountAmount = 0;

            if ($discountType === 'percentage') {
                $discountAmount = ($subtotal * $discountValue) / 100;
            } elseif ($discountType === 'nominal') {
                $discountAmount = $discountValue;
            }

            // Ensure discount doesn't exceed subtotal
            $discountAmount = min($discountAmount, $subtotal);

            $totalAmount = $subtotal - $discountAmount;
            
            // Hitung Platform Fee berdasarkan tipe komisi yang diatur Super Admin
            if ($feeType === 'fixed') {
                $platformFee = $tenant->platform_fee_fixed ?? 0;
            } else {
                $platformFee = ($totalAmount * $mdrPercentage) / 100;
            }
            $netAmount = $totalAmount - $platformFee;

            // Generate Receipt Number
            $receiptNumber = 'TRX-' . strtoupper(Str::random(8));

            $transaction = Transaction::create([
                'tenant_id' => $tenant->id,
                'cashier_id' => auth()->id(),
                'receipt_number' => $receiptNumber,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'platform_fee' => $platformFee,
                'net_amount' => $netAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
            ]);

            foreach ($itemsData as &$itemData) {
                $itemData['transaction_id'] = $transaction->id;
            }

            $transaction->items()->insert($itemsData);

            DB::commit();

            // Load items for the receipt
            $transaction->load('items');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil diproses!',
                    'transaction' => $transaction
                ]);
            }

            return back()->with('success', 'Transaksi berhasil diproses! No Resi: ' . $receiptNumber);
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
                ], 422);
            }

            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function applyPromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $tenant = auth()->user()->tenant;
        $promo = \App\Models\Promo::where('tenant_id', $tenant->id)
            ->where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now()->startOfDay());
            })
            ->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak valid, kadaluarsa, atau sudah tidak aktif.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'promo' => [
                'type' => $promo->type,
                'value' => (float) $promo->value,
                'code' => $promo->code
            ]
        ]);
    }

    public function showOpenShift()
    {
        $tenantId = auth()->user()->tenant_id;
        $activeShift = Shift::where('tenant_id', $tenantId)
            ->where('status', 'open')
            ->first();

        if ($activeShift) {
            return redirect()->route('pos.index');
        }

        return view('pos.shift.open');
    }

    public function openShift(Request $request)
    {
        $request->validate([
            'starting_cash' => 'required|numeric|min:0'
        ]);

        // Cek dulu apakah udah ada shift aktif biar nggak dobel!
        $activeShift = Shift::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'open')
            ->first();

        if ($activeShift) {
            return redirect()->route('pos.index')->with('error', 'Shift sudah aktif! Anda tidak perlu membuka shift baru.');
        }

        Shift::create([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->id(),
            'starting_cash' => $request->starting_cash,
            'status' => 'open',
            'opened_at' => now(),
        ]);

        return redirect()->route('pos.index')->with('success', 'Shift kasir berhasil dibuka.');
    }

    public function showCloseShift()
    {
        $tenantId = auth()->user()->tenant_id;

        $shift = Shift::where('tenant_id', $tenantId)
            ->where('status', 'open')
            ->first();

        if (!$shift) {
            return redirect()->route('pos.index')->with('error', 'Tidak ada shift aktif.');
        }

        // Hitung total penjualan tunai (cash) oleh seluruh kasir selama shift ini berjalan
        $cashSales = Transaction::where('tenant_id', $tenantId)
            ->where('payment_method', 'cash')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', $shift->opened_at)
            ->sum('total_amount');

        $expectedCash = $shift->starting_cash + $cashSales;

        return view('pos.shift.close', compact('shift', 'cashSales', 'expectedCash'));
    }

    public function closeShift(Request $request)
    {
        $request->validate([
            'actual_ending_cash' => 'required|numeric|min:0'
        ]);

        $tenantId = auth()->user()->tenant_id;

        $shift = Shift::where('tenant_id', $tenantId)
            ->where('status', 'open')
            ->first();

        if (!$shift) {
            return redirect()->route('pos.index')->with('error', 'Tidak ada shift aktif.');
        }

        // Hitung total penjualan tunai (cash) oleh seluruh kasir selama shift ini berjalan
        $cashSales = Transaction::where('tenant_id', $tenantId)
            ->where('payment_method', 'cash')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', $shift->opened_at)
            ->sum('total_amount');

        $expectedCash = $shift->starting_cash + $cashSales;
        $actualCash = $request->actual_ending_cash;
        $difference = $actualCash - $expectedCash;

        $shift->update([
            'expected_ending_cash' => $expectedCash,
            'actual_ending_cash' => $actualCash,
            'difference' => $difference,
            'closed_at' => now(),
            'status' => 'closed',
        ]);

        $message = "Shift ditutup! Selisih laci kas: Rp " . number_format($difference, 0, ',', '.');
        if ($difference < 0) {
            return redirect()->route('dashboard')->with('error', $message . ' (Minus)');
        }
        
        return redirect()->route('dashboard')->with('success', $message);
    }
}
