<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected function getTransactionQuery(Request $request, $tenantId)
    {
        $query = Transaction::where('tenant_id', $tenantId)->latest();
        $period = $request->input('period', 'last_30_days');

        if ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'this_week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'this_year') {
            $query->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'last_30_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        }
        
        // 'all' period skips any date filtering

        return $query;
    }

    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $period = $request->input('period', 'last_30_days');
        
        $query = $this->getTransactionQuery($request, $tenantId);
        $transactions = (clone $query)->paginate(15)->appends(['period' => $period]);

        // Aggregate statistics for the filtered period (only paid/successful transactions)
        $statsQuery = (clone $query)->where('payment_status', 'paid');

        $totalOmzet = $statsQuery->sum('total_amount');
        $totalPlatformFee = $statsQuery->sum('platform_fee');
        $totalNetIncome = $statsQuery->sum('net_amount');
        $totalTransactions = $statsQuery->count();

        return view('reports.transactions.index', compact(
            'transactions', 
            'totalOmzet', 
            'totalPlatformFee', 
            'totalNetIncome', 
            'totalTransactions',
            'period'
        ));
    }

    public function show($id)
    {
        $transaction = Transaction::where('tenant_id', auth()->user()->tenant_id)
            ->with(['items', 'cashier'])
            ->findOrFail($id);
            
        return view('reports.transactions.show', compact('transaction'));
    }

    public function void($id)
    {
        $tenantId = auth()->user()->tenant_id;
        
        DB::beginTransaction();
        try {
            $transaction = Transaction::where('tenant_id', $tenantId)
                ->with('items')
                ->findOrFail($id);

            if ($transaction->payment_status === 'failed') {
                return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
            }

            // Restore stocks for physical items
            foreach ($transaction->items as $item) {
                $product = \App\Models\Product::find($item->product_id);
                if ($product && $product->type === 'physical' && $product->stock_quantity !== null) {
                    $product->increment('stock_quantity', $item->quantity);
                }
            }

            // Update status to failed (representing Voided/Canceled)
            $transaction->update([
                'payment_status' => 'failed'
            ]);

            DB::commit();

            return redirect()->route('report.transactions')->with('success', 'Transaksi ' . $transaction->receipt_number . ' berhasil dibatalkan (void) dan stok barang telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    public function exportCsv(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $transactions = $this->getTransactionQuery($request, $tenantId)->get();

        $filename = "laporan_transaksi_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'No. Resi', 'Metode Pembayaran', 'Status', 'Total Kotor (Rp)', 'Diskon (Rp)', 'Platform Fee (Rp)', 'Pendapatan Bersih (Rp)'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $trx) {
                $row['Tanggal']  = $trx->created_at->format('Y-m-d H:i:s');
                $row['No. Resi'] = $trx->receipt_number;
                $row['Metode']   = strtoupper($trx->payment_method);
                $row['Status']   = strtoupper($trx->payment_status);
                $row['Total']    = $trx->subtotal;
                $row['Diskon']   = $trx->discount_amount;
                $row['Fee']      = $trx->platform_fee;
                $row['Bersih']   = $trx->net_amount;

                fputcsv($file, array($row['Tanggal'], $row['No. Resi'], $row['Metode'], $row['Status'], $row['Total'], $row['Diskon'], $row['Fee'], $row['Bersih']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $tenant = auth()->user()->tenant;
        $transactions = $this->getTransactionQuery($request, $tenantId)->get();

        $totalOmzet = $transactions->sum('total_amount');
        $totalPlatformFee = $transactions->sum('platform_fee');
        $totalNetIncome = $transactions->sum('net_amount');
        $totalTransactionsCount = $transactions->count();

        return view('reports.transactions.print', compact(
            'transactions', 'tenant', 'totalOmzet', 'totalPlatformFee', 'totalNetIncome', 'totalTransactionsCount'
        ));
    }

    public function shifts(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        
        $shifts = \App\Models\Shift::where('tenant_id', $tenantId)
            ->with('cashier')
            ->latest()
            ->paginate(15);

        return view('reports.shifts.index', compact('shifts'));
    }
}
