<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $bengkel = Tenant::where('slug', 'bengkel-barokah-motor')->first();
        $kopi = Tenant::where('slug', 'kopi-kenangan-senja')->first();

        $oleholeh = Tenant::where('slug', 'pusat-oleh-oleh-nusantara')->first();

        if ($bengkel) {
            Category::create([
                'tenant_id' => $bengkel->id,
                'name' => 'Servis & Jasa',
                'is_active' => true,
            ]);

            Category::create([
                'tenant_id' => $bengkel->id,
                'name' => 'Sparepart & Suku Cadang',
                'is_active' => true,
            ]);

            Category::create([
                'tenant_id' => $bengkel->id,
                'name' => 'Oli & Pelumas',
                'is_active' => true,
            ]);
        }

        if ($kopi) {
            Category::create([
                'tenant_id' => $kopi->id,
                'name' => 'Coffee',
                'is_active' => true,
            ]);

            Category::create([
                'tenant_id' => $kopi->id,
                'name' => 'Non-Coffee',
                'is_active' => true,
            ]);

            Category::create([
                'tenant_id' => $kopi->id,
                'name' => 'Snack & Pastry',
                'is_active' => true,
            ]);
        }

        if ($oleholeh) {
            Category::create([
                'tenant_id' => $oleholeh->id,
                'name' => 'Makanan Ringan',
                'is_active' => true,
            ]);

            Category::create([
                'tenant_id' => $oleholeh->id,
                'name' => 'Minuman Tradisional',
                'is_active' => true,
            ]);

            Category::create([
                'tenant_id' => $oleholeh->id,
                'name' => 'Pakaian & Batik',
                'is_active' => true,
            ]);
            
            Category::create([
                'tenant_id' => $oleholeh->id,
                'name' => 'Kerajinan Tangan',
                'is_active' => true,
            ]);
        }
    }
}
