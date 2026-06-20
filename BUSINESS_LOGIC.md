# Business Logic & Flow

Dokumen ini menjelaskan alur bisnis dan logika komputasi utama dalam sistem MUI UMKM Platform.

## 1. Flow Pendaftaran & Approval UMKM
1. UMKM mendaftar via halaman publik (input nama bisnis, kategori, KTP/legalitas).
2. Data masuk ke tabel `tenants` dengan status `pending`.
3. Super Admin MUI melakukan verifikasi dokumen dan lokasi.
4. Jika disetujui, status menjadi `active`. Sistem otomatis mengirimkan akses (password default) ke email pemilik UMKM.
5. UMKM baru bisa muncul di **Public Directory Portal** jika statusnya `active`.

## 2. Manajemen Produk Generik (Physical vs Service)
Sistem menggunakan pendekatan hibrida untuk mengakomodasi bisnis retail (seperti warung) dan bisnis jasa (seperti salon/bengkel).
- Jika produk bertipe **`physical`**: Saat terjadi transaksi sukses, sistem **wajib** memotong `stock_quantity`.
- Jika produk bertipe **`service`**: Sistem **mengabaikan** proses potong stok (bypass inventory logic).

## 3. Logika Monetisasi (Sistem Saldo Deposit Nominal)
Aplikasi POS ini menggunakan model prabayar berupa sistem **Saldo Deposit Nominal** (Top-Up). Platform tidak memotong dana transaksi penjualan secara langsung, melainkan UMKM harus memiliki saldo deposit (dalam Rupiah) untuk dapat mencatat transaksi.

**Rumus Kalkulasi Saat Checkout:**
1. UMKM dapat melakukan Top-Up saldo nominal secara bebas (misal Rp 10.000). Nilai ini disimpan pada kolom `credit_balance` di tabel `tenants`. Tarif potongan per transaksi ditentukan oleh `platform_fee_rate` (misal Rp 100).
2. Saat *checkout* POS:
   - `total_amount` = `subtotal` - `discount`
   - Uang pembayaran dari pelanggan sebesar `total_amount` 100% menjadi milik UMKM.
3. Begitu transaksi diselesaikan (sukses), sistem akan mengurangi `credit_balance` milik UMKM tersebut sebesar `platform_fee_rate`, lalu mencatat nilai potongan tersebut ke kolom `platform_fee` di tabel `transactions`.
4. Jika `credit_balance` lebih kecil dari `platform_fee_rate`, maka kasir tidak dapat memproses transaksi baru sampai UMKM melakukan *Top Up* saldo deposit ke penyedia platform.

## 4. Public Directory Portal
- Direktori ini *read-only* bagi publik.
- Mengambil data dari tabel `tenants` yang aktif.
- Menampilkan *showcase* UMKM berdasarkan lokasi terdekat (jika implementasi koordinat diaktifkan nanti) atau filter kategori (contoh: Fashion, Kuliner, Jasa Perbaikan).
