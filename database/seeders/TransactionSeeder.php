<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $bengkel = Tenant::where('slug', 'bengkel-barokah-motor')->first();
        $kopi = Tenant::where('slug', 'kopi-kenangan-senja')->first();

        $oleholeh = Tenant::where('slug', 'pusat-oleh-oleh-nusantara')->first();

        // 1. Seed Transactions for Bengkel Barokah Motor
        if ($bengkel) {
            $cashier = User::where('tenant_id', $bengkel->id)->where('role', 'cashier')->first();
            $products = Product::where('tenant_id', $bengkel->id)->get();

            // We generate 15 transactions over the last 7 days
            for ($i = 0; $i < 15; $i++) {
                $daysAgo = rand(0, 7);
                $createdAt = now()->subDays($daysAgo)->subHours(rand(1, 10))->subMinutes(rand(1, 59));
                
                // Select 1-3 random products
                $selectedProducts = $products->random(rand(1, min(3, $products->count())));
                
                $subtotal = 0;
                $items = [];
                
                foreach ($selectedProducts as $product) {
                    $qty = rand(1, 2);
                    $itemSubtotal = $product->price * $qty;
                    $subtotal += $itemSubtotal;
                    
                    $items[] = [
                        'id' => (string) Str::uuid(),
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'type' => $product->type,
                        'price' => $product->price,
                        'quantity' => $qty,
                        'subtotal' => $itemSubtotal,
                    ];
                }

                // Random discount
                $discount = 0;
                if (rand(0, 10) > 6) {
                    // Apply a random 10% discount to simulate a promo code application
                    $discount = round($subtotal * 0.10, -2); // round to nearest 100
                }

                $totalAmount = max(0, $subtotal - $discount);
                
                // MDR fee calculations (1.5% for Bengkel)
                $paymentMethod = rand(0, 10) > 4 ? 'qris' : 'cash';
                $platformFee = 100.00; // Flat fee 100 Rupiah

                $receiptNum = 'TRX/BKM/' . $createdAt->format('Ymd') . '/' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

                $transaction = Transaction::create([
                    'id' => (string) Str::uuid(),
                    'tenant_id' => $bengkel->id,
                    'cashier_id' => $cashier?->id,
                    'receipt_number' => $receiptNum,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'total_amount' => $totalAmount,
                    'platform_fee' => $platformFee,
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'paid',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($items as $item) {
                    $item['transaction_id'] = $transaction->id;
                    TransactionItem::create($item);
                }
            }
        }

        // 2. Seed Transactions for Kopi Kenangan Senja
        if ($kopi) {
            $cashier = User::where('tenant_id', $kopi->id)->where('role', 'cashier')->first();
            $products = Product::where('tenant_id', $kopi->id)->get();

            // We generate 30 transactions over the last 7 days (cafes have higher frequency)
            for ($i = 0; $i < 30; $i++) {
                $daysAgo = rand(0, 7);
                $createdAt = now()->subDays($daysAgo)->subHours(rand(1, 12))->subMinutes(rand(1, 59));
                
                $selectedProducts = $products->random(rand(1, min(4, $products->count())));
                
                $subtotal = 0;
                $items = [];
                
                foreach ($selectedProducts as $product) {
                    $qty = rand(1, 3);
                    $itemSubtotal = $product->price * $qty;
                    $subtotal += $itemSubtotal;
                    
                    $items[] = [
                        'id' => (string) Str::uuid(),
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'type' => $product->type,
                        'price' => $product->price,
                        'quantity' => $qty,
                        'subtotal' => $itemSubtotal,
                    ];
                }

                // Random promo (e.g. SENJAHAPPY 20% discount)
                $discount = 0;
                if (rand(0, 10) > 5) {
                    $discount = round($subtotal * 0.20, -2);
                }

                $totalAmount = max(0, $subtotal - $discount);
                
                // MDR fee calculations (0.7% for Kopi QRIS transactions)
                $paymentMethod = rand(0, 10) > 3 ? 'qris' : 'cash';
                $platformFee = 100.00;

                $receiptNum = 'TRX/KKS/' . $createdAt->format('Ymd') . '/' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

                $transaction = Transaction::create([
                    'id' => (string) Str::uuid(),
                    'tenant_id' => $kopi->id,
                    'cashier_id' => $cashier?->id,
                    'receipt_number' => $receiptNum,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'total_amount' => $totalAmount,
                    'platform_fee' => $platformFee,
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'paid',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($items as $item) {
                    $item['transaction_id'] = $transaction->id;
                    TransactionItem::create($item);
                }
            }
        }

        // 3. Seed Transactions for Pusat Oleh-Oleh Nusantara
        if ($oleholeh) {
            $cashier = User::where('tenant_id', $oleholeh->id)->where('role', 'cashier')->first();
            $products = Product::where('tenant_id', $oleholeh->id)->get();

            // We generate 25 transactions over the last 7 days
            for ($i = 0; $i < 25; $i++) {
                $daysAgo = rand(0, 7);
                $createdAt = now()->subDays($daysAgo)->subHours(rand(1, 10))->subMinutes(rand(1, 59));
                
                $selectedProducts = $products->random(rand(2, min(5, $products->count())));
                
                $subtotal = 0;
                $items = [];
                
                foreach ($selectedProducts as $product) {
                    $qty = rand(1, 5); // They buy many souvenirs
                    $itemSubtotal = $product->price * $qty;
                    $subtotal += $itemSubtotal;
                    
                    $items[] = [
                        'id' => (string) Str::uuid(),
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'type' => $product->type,
                        'price' => $product->price,
                        'quantity' => $qty,
                        'subtotal' => $itemSubtotal,
                    ];
                }

                $discount = 0; // Usually no discounts on souvenirs
                
                $totalAmount = $subtotal - $discount;
                
                // MDR fee calculations (1% for Retail QRIS)
                $paymentMethod = rand(0, 10) > 5 ? 'qris' : 'cash';
                $platformFee = 100.00;

                $receiptNum = 'TRX/OOH/' . $createdAt->format('Ymd') . '/' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

                $transaction = Transaction::create([
                    'id' => (string) Str::uuid(),
                    'tenant_id' => $oleholeh->id,
                    'cashier_id' => $cashier?->id,
                    'receipt_number' => $receiptNum,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'total_amount' => $totalAmount,
                    'platform_fee' => $platformFee,
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'paid',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($items as $item) {
                    $item['transaction_id'] = $transaction->id;
                    TransactionItem::create($item);
                }
            }
        }
    }
}
