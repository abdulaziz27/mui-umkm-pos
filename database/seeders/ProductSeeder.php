<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $bengkel = Tenant::where('slug', 'bengkel-barokah-motor')->first();
        $kopi = Tenant::where('slug', 'kopi-kenangan-senja')->first();

        $oleholeh = Tenant::where('slug', 'pusat-oleh-oleh-nusantara')->first();

        // 1. Seed products for Bengkel Barokah Motor
        if ($bengkel) {
            $jasaCat = Category::where('tenant_id', $bengkel->id)->where('name', 'Servis & Jasa')->first();
            $sparepartCat = Category::where('tenant_id', $bengkel->id)->where('name', 'Sparepart & Suku Cadang')->first();
            $oliCat = Category::where('tenant_id', $bengkel->id)->where('name', 'Oli & Pelumas')->first();

            // Services (Service type does not have stock_quantity)
            Product::create([
                'tenant_id' => $bengkel->id,
                'category_id' => $jasaCat?->id,
                'type' => 'service',
                'name' => 'Servis Motor Ringan',
                'sku' => 'SVC-RG-01',
                'description' => 'Pengecekan busi, pembersihan karburator/injeksi, setel rantai, rem, dll.',
                'price' => 50000.00,
                'cost' => 15000.00,
                'stock_quantity' => null,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $bengkel->id,
                'category_id' => $jasaCat?->id,
                'type' => 'service',
                'name' => 'Ganti Oli Plus Servis',
                'sku' => 'SVC-OL-02',
                'description' => 'Servis lengkap motor berkala ditambah jasa penggantian oli.',
                'price' => 75000.00,
                'cost' => 20000.00,
                'stock_quantity' => null,
                'is_active' => true,
            ]);

            // Physical Products (Physical type has stock_quantity)
            Product::create([
                'tenant_id' => $bengkel->id,
                'category_id' => $oliCat?->id,
                'type' => 'physical',
                'name' => 'Oli Shell Helix 1L',
                'sku' => 'OLI-SH-1L',
                'description' => 'Oli mesin performa tinggi untuk perlindungan mesin ekstra.',
                'price' => 65000.00,
                'cost' => 45000.00,
                'stock_quantity' => 50,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $bengkel->id,
                'category_id' => $sparepartCat?->id,
                'type' => 'physical',
                'name' => 'Aki GS Astra GTZ5S',
                'sku' => 'AKI-GS-5S',
                'description' => 'Aki kering andal dengan daya start tangguh.',
                'price' => 220000.00,
                'cost' => 170000.00,
                'stock_quantity' => 15,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $bengkel->id,
                'category_id' => $sparepartCat?->id,
                'type' => 'physical',
                'name' => 'Kampas Rem Depan Honda',
                'sku' => 'KMP-RM-HD',
                'description' => 'Kampas rem depan orisinal berkualitas tinggi presisi.',
                'price' => 45000.00,
                'cost' => 30000.00,
                'stock_quantity' => 30,
                'is_active' => true,
            ]);
        }

        // 2. Seed products for Kopi Kenangan Senja
        if ($kopi) {
            $coffeeCat = Category::where('tenant_id', $kopi->id)->where('name', 'Coffee')->first();
            $nonCoffeeCat = Category::where('tenant_id', $kopi->id)->where('name', 'Non-Coffee')->first();
            $snackCat = Category::where('tenant_id', $kopi->id)->where('name', 'Snack & Pastry')->first();

            Product::create([
                'tenant_id' => $kopi->id,
                'category_id' => $coffeeCat?->id,
                'type' => 'physical',
                'name' => 'Kopi Susu Gula Aren',
                'sku' => 'COP-SGA-01',
                'description' => 'Kopi susu signature dengan espresso arabika dan gula aren asli.',
                'price' => 18000.00,
                'cost' => 8000.00,
                'stock_quantity' => 200,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $kopi->id,
                'category_id' => $coffeeCat?->id,
                'type' => 'physical',
                'name' => 'Ice Americano',
                'sku' => 'COP-AME-02',
                'description' => 'Espresso arabika disajikan dingin segar tanpa gula.',
                'price' => 15000.00,
                'cost' => 5000.00,
                'stock_quantity' => 300,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $kopi->id,
                'category_id' => $nonCoffeeCat?->id,
                'type' => 'physical',
                'name' => 'Matcha Latte Premium',
                'sku' => 'NCP-MAT-03',
                'description' => 'Bubuk matcha murni berkualitas dipadu dengan susu segar.',
                'price' => 22000.00,
                'cost' => 10000.00,
                'stock_quantity' => 150,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $kopi->id,
                'category_id' => $snackCat?->id,
                'type' => 'physical',
                'name' => 'Croissant Chocolate',
                'sku' => 'PST-CRC-04',
                'description' => 'Roti pastry mentega berlapis cokelat Belgia leleh.',
                'price' => 25000.00,
                'cost' => 15000.00,
                'stock_quantity' => 40,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $kopi->id,
                'category_id' => $snackCat?->id,
                'type' => 'physical',
                'name' => 'Kentang Goreng Truffle',
                'sku' => 'SNC-KGT-05',
                'description' => 'Kentang goreng renyah wangi truffle oil disajikan hangat.',
                'price' => 15000.00,
                'cost' => 8000.00,
                'stock_quantity' => 80,
                'is_active' => true,
            ]);
        }

        // 3. Seed products for Pusat Oleh-Oleh Nusantara
        if ($oleholeh) {
            $makananCat = Category::where('tenant_id', $oleholeh->id)->where('name', 'Makanan Ringan')->first();
            $pakaianCat = Category::where('tenant_id', $oleholeh->id)->where('name', 'Pakaian & Batik')->first();
            $kerajinanCat = Category::where('tenant_id', $oleholeh->id)->where('name', 'Kerajinan Tangan')->first();

            Product::create([
                'tenant_id' => $oleholeh->id,
                'category_id' => $makananCat?->id,
                'type' => 'physical',
                'name' => 'Bakpia Pathok 25 Premium',
                'sku' => 'O-BKP-01',
                'description' => 'Bakpia khas Jogja isi kacang hijau asli, empuk dan legit.',
                'price' => 45000.00,
                'cost' => 30000.00,
                'stock_quantity' => 120,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $oleholeh->id,
                'category_id' => $makananCat?->id,
                'type' => 'physical',
                'name' => 'Keripik Tempe Sagu',
                'sku' => 'O-KTS-02',
                'description' => 'Keripik tempe tipis digoreng garing dengan balutan sagu renyah.',
                'price' => 25000.00,
                'cost' => 15000.00,
                'stock_quantity' => 85,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $oleholeh->id,
                'category_id' => $pakaianCat?->id,
                'type' => 'physical',
                'name' => 'Kaos Dagadu Original',
                'sku' => 'O-DGD-03',
                'description' => 'Kaos oblong khas Jogja dengan desain ikonik dan bahan katun combed 30s.',
                'price' => 110000.00,
                'cost' => 60000.00,
                'stock_quantity' => 45,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $oleholeh->id,
                'category_id' => $pakaianCat?->id,
                'type' => 'physical',
                'name' => 'Batik Tulis Lengan Panjang',
                'sku' => 'O-BTK-04',
                'description' => 'Kemeja batik tulis eksklusif dengan pola parang.',
                'price' => 350000.00,
                'cost' => 200000.00,
                'stock_quantity' => 15,
                'minimum_stock' => 20,
                'is_active' => true,
            ]);

            Product::create([
                'tenant_id' => $oleholeh->id,
                'category_id' => $kerajinanCat?->id,
                'type' => 'physical',
                'name' => 'Gantungan Kunci Kayu Ukir',
                'sku' => 'O-GKK-05',
                'description' => 'Souvenir gantungan kunci dengan ukiran khas keraton.',
                'price' => 5000.00,
                'cost' => 2000.00,
                'stock_quantity' => 500,
                'is_active' => true,
            ]);
        }
    }
}
