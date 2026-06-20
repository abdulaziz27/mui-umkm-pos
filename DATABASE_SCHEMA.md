# Database Schema Architecture

Dokumen ini mendefinisikan struktur tabel utama (MySQL) yang dirancang secara spesifik untuk MVP MUI UMKM Platform.

## 1. Core & Tenancy
### `tenants` (Data UMKM)
- `id` (uuid, primary key)
- `name` (string) - Nama bisnis UMKM
- `slug` (string) - Slug unik untuk URL public directory
- `type` (string) - Jenis bisnis (F&B, Retail, Jasa, dll)
- `description` (text, nullable)
- `address`, `phone`, `logo_path`
- `status` (enum: pending, active, suspended) - Butuh verifikasi Super Admin
- `credit_balance` (decimal) - Saldo deposit Rupiah untuk membayar fee platform (default 0)
- `platform_fee_rate` (decimal) - Tarif potongan flat per transaksi (default 100)
- `created_at`, `updated_at`

### `users` (Staff & Admin)
- `id` (uuid, primary key)
- `tenant_id` (uuid, nullable) - Null berarti Super Admin platform
- `role` (enum: superadmin, tenant_owner, cashier)
- `name`, `email`, `password`
- `created_at`, `updated_at`

## 2. Master Data (UMKM Scope)
### `categories`
- `id` (uuid)
- `tenant_id` (uuid, foreign key)
- `name` (string)
- `is_active` (boolean)

### `products` (Barang / Jasa)
- `id` (uuid)
- `tenant_id` (uuid, foreign key)
- `category_id` (uuid, foreign key)
- `type` (enum: physical, service) - *Physical* memotong stok, *Service* tidak.
- `name` (string)
- `description` (text, nullable)
- `price` (decimal)
- `stock_quantity` (integer, nullable) - Hanya relevan untuk tipe `physical`
- `is_active` (boolean)
- `created_at`, `updated_at`

## 3. Transaction & Monetization
### `transactions`
- `id` (uuid, primary key)
- `tenant_id` (uuid, foreign key)
- `cashier_id` (uuid, foreign key)
- `receipt_number` (string)
- `subtotal` (decimal)
- `discount_amount` (decimal)
- `total_amount` (decimal) - Total yang dibayar pelanggan
- `platform_fee` (decimal) - Bukti potong saldo deposit atas transaksi ini
- `payment_method` (string) - Cash, QRIS, Transfer, dll
- `payment_status` (enum: pending, paid, failed)
- `created_at`, `updated_at`

### `transaction_items`
- `id` (uuid)
- `transaction_id` (uuid, foreign key)
- `product_id` (uuid, foreign key, nullable)
- `product_name` (string) - Disimpan statis agar history tidak berubah jika nama produk diupdate
- `type` (enum: physical, service)
- `price` (decimal)
- `quantity` (integer)
- `subtotal` (decimal)

## Catatan Arsitektur
1. **Semua tabel (kecuali users admin) wajib memiliki `tenant_id`** untuk memastikan data UMKM A tidak bisa diakses oleh UMKM B.
2. **Tidak ada tabel `recipes` atau `tables` (meja)** untuk menjaga agar sistem tetap generik.
