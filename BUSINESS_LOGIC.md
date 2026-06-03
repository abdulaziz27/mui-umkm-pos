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

## 3. Logika Monetisasi (Platform Fee / MDR)
Aplikasi POS ini gratis digunakan, namun platform mengambil komisi (fee) dari setiap transaksi sukses.

**Rumus Kalkulasi Saat Checkout:**
1. Default Platform Fee (misal: 1% atau Rp 500 flat). Konfigurasi ini disimpan di *environment variables* atau tabel pengaturan global.
2. Jika tabel `tenants` memiliki nilai khusus pada `mdr_fee_percentage`, maka nilai tersebut akan me-override persentase default.
3. Saat *checkout* POS:
   - `total_amount` = `subtotal` - `discount`
   - `platform_fee` = `total_amount` * (MDR % / 100)
   - `net_amount` (Hak UMKM) = `total_amount` - `platform_fee`
4. Angka-angka di atas dikunci dan disimpan secara statis di tabel `transactions` untuk keperluan audit dan *settlement* pencairan dana.

## 4. Public Directory Portal
- Direktori ini *read-only* bagi publik.
- Mengambil data dari tabel `tenants` yang aktif.
- Menampilkan *showcase* UMKM berdasarkan lokasi terdekat (jika implementasi koordinat diaktifkan nanti) atau filter kategori (contoh: Fashion, Kuliner, Jasa Perbaikan).
