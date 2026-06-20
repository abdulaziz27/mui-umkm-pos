<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Super Admin (MUI)
        User::create([
            'name' => 'MUI Admin',
            'email' => 'admin@mui.or.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'tenant_id' => null,
        ]);

        // 2. Create Tenant 1: Bengkel Barokah Motor (Jasa & Retail)
        $tenant1 = Tenant::create([
            'name' => 'Bengkel Barokah Motor',
            'slug' => 'bengkel-barokah-motor',
            'type' => 'service',
            'description' => 'Jasa servis motor dan ganti oli terpercaya',
            'address' => 'Jl. Raya Sleman No. 12, Yogyakarta',
            'phone' => '081234567890',
            'status' => 'active',
            'credit_balance' => 10000,
            'platform_fee_rate' => 100,
        ]);

        // Create Tenant 1 Owner
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@bengkelbarokah.com',
            'password' => Hash::make('password'),
            'role' => 'tenant_owner',
            'tenant_id' => $tenant1->id,
        ]);

        // Create Tenant 1 Cashier
        User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@bengkelbarokah.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'tenant_id' => $tenant1->id,
        ]);

        // 3. Create Tenant 2: Kopi Kenangan Senja (F&B)
        $tenant2 = Tenant::create([
            'name' => 'Kopi Kenangan Senja',
            'slug' => 'kopi-kenangan-senja',
            'type' => 'fnb',
            'description' => 'Kopi nikmat di kala senja dan cemilan hangat',
            'address' => 'Jl. Kaliurang KM 5, Yogyakarta',
            'phone' => '089876543210',
            'status' => 'active',
            'credit_balance' => 50000,
            'platform_fee_rate' => 100,
            'halal_certificate_number' => 'ID34110001234560822',
            'halal_certificate_expires_at' => '2028-12-31',
            'receipt_header' => 'Senja Lebih Indah dengan Secangkir Kopi',
            'receipt_footer' => 'Terima kasih telah menemani senja kami. Follow IG @kopisenja_yk',
            'instagram_handle' => 'kopisenja_yk',
            'operating_hours' => [
                'senin' => ['is_open' => '1', 'open' => '08:00', 'close' => '22:00'],
                'selasa' => ['is_open' => '1', 'open' => '08:00', 'close' => '22:00'],
                'rabu' => ['is_open' => '1', 'open' => '08:00', 'close' => '22:00'],
                'kamis' => ['is_open' => '1', 'open' => '08:00', 'close' => '22:00'],
                'jumat' => ['is_open' => '1', 'open' => '08:00', 'close' => '23:00'],
                'sabtu' => ['is_open' => '1', 'open' => '08:00', 'close' => '23:00'],
                'minggu' => ['is_open' => '1', 'open' => '08:00', 'close' => '22:00'],
            ],
        ]);

        // Create Tenant 2 Owner
        User::create([
            'name' => 'Sarah Wijaya',
            'email' => 'sarah@kopisenja.com',
            'password' => Hash::make('password'),
            'role' => 'tenant_owner',
            'tenant_id' => $tenant2->id,
        ]);

        // Create Tenant 2 Cashier
        User::create([
            'name' => 'Rian Hidayat',
            'email' => 'rian@kopisenja.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'tenant_id' => $tenant2->id,
        ]);
        // 4. Create Tenant 3: Pusat Oleh-Oleh Nusantara (Retail)
        $tenant3 = Tenant::create([
            'name' => 'Pusat Oleh-Oleh Nusantara',
            'slug' => 'pusat-oleh-oleh-nusantara',
            'type' => 'retail',
            'description' => 'Menyediakan berbagai macam oleh-oleh khas Nusantara lengkap dan murah',
            'address' => 'Jl. Malioboro No. 99, Yogyakarta',
            'phone' => '087766554433',
            'status' => 'active',
            'credit_balance' => 0,
            'platform_fee_rate' => 100,
        ]);

        // Create Tenant 3 Owner
        User::create([
            'name' => 'Bapak Slamet',
            'email' => 'slamet@oleholeh.com',
            'password' => Hash::make('password'),
            'role' => 'tenant_owner',
            'tenant_id' => $tenant3->id,
        ]);

        // Create Tenant 3 Cashier
        User::create([
            'name' => 'Ratna Ayu',
            'email' => 'ratna@oleholeh.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'tenant_id' => $tenant3->id,
        ]);
    }
}
