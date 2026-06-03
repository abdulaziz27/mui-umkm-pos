<?php

namespace Database\Seeders;

use App\Models\Promo;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        $bengkel = Tenant::where('slug', 'bengkel-barokah-motor')->first();
        $kopi = Tenant::where('slug', 'kopi-kenangan-senja')->first();

        // Promos for Bengkel Barokah Motor
        if ($bengkel) {
            Promo::create([
                'tenant_id' => $bengkel->id,
                'code' => 'MUIBERKAH',
                'type' => 'percentage',
                'value' => 10,
                'is_active' => true,
                'expires_at' => null,
            ]);

            Promo::create([
                'tenant_id' => $bengkel->id,
                'code' => 'BAROKAH50',
                'type' => 'nominal',
                'value' => 50000,
                'is_active' => true,
                'expires_at' => now()->addDays(30)->toDateString(),
            ]);

            Promo::create([
                'tenant_id' => $bengkel->id,
                'code' => 'KADALUARSA',
                'type' => 'percentage',
                'value' => 15,
                'is_active' => true,
                'expires_at' => now()->subDays(5)->toDateString(),
            ]);

            Promo::create([
                'tenant_id' => $bengkel->id,
                'code' => 'PROMONONAKTIF',
                'type' => 'nominal',
                'value' => 20000,
                'is_active' => false,
                'expires_at' => null,
            ]);
        }

        // Promos for Kopi Kenangan Senja
        if ($kopi) {
            Promo::create([
                'tenant_id' => $kopi->id,
                'code' => 'SENJAHAPPY',
                'type' => 'percentage',
                'value' => 20,
                'is_active' => true,
                'expires_at' => now()->addDays(14)->toDateString(),
            ]);

            Promo::create([
                'tenant_id' => $kopi->id,
                'code' => 'DISKONGEDE',
                'type' => 'nominal',
                'value' => 10000,
                'is_active' => true,
                'expires_at' => null,
            ]);
        }
    }
}
