# AI Agent Directives (CRITICAL)

This file serves as the core rulebook for any AI coding assistant (Antigravity/Gemini, Claude Code, Cursor, etc.) working in this repository.

## 1. Mandatory Reading
Sebelum mengeksekusi tugas atau merancang fitur baru, Anda **WAJIB** membaca dan memahami dokumen arsitektur berikut yang ada di *root directory*:
- 📄 `PROJECT_PLAN.md` -> Berisi objektif utama, *tech stack*, dan pembagian fase (Roadmap).
- 📄 `DATABASE_SCHEMA.md` -> Berisi rancangan struktur tabel MySQL. Pastikan tidak keluar dari pakem skema ini.
- 📄 `BUSINESS_LOGIC.md` -> Berisi logika bisnis utama (Approval UMKM, perbedaan produk fisik vs jasa, dan kalkulasi komisi MDR per transaksi).

## 2. Fundamental Restrictions (Batasan Mutlak AI)
- **General Business, Not F&B**: Sistem ini adalah POS Generik untuk UMKM MUI. DILARANG membuat fitur yang spesifik untuk restoran (seperti *table management*, *Kitchen Display System*, atau resep/bahan baku) kecuali diinstruksikan secara eksplisit.
- **No Subscription Tier**: DILARANG membuat sistem langganan bulanan/tahunan (*SaaS Subscription*). Sistem ini gratis digunakan UMKM, platform memotong uang dari komisi per-transaksi (MDR/Platform Fee).

## 3. Laravel & Coding Guidelines
- **Laravel 12**: Gunakan struktur Laravel versi terbaru (contoh: konfigurasi *middleware* ada di `bootstrap/app.php`, tidak ada lagi `Http/Kernel.php`).
- **Tech Stack**: Gunakan Tailwind CSS v4 dan Alpine.js v3 untuk *frontend*.
- **Database**: Gunakan MySQL. Pastikan penulisan query dan migration sesuai standar MySQL.
- **Code Quality**:
  - Gunakan *Type Hinting* dan *Return Types* secara eksplisit pada setiap fungsi PHP.
  - Gunakan Eloquent ORM dan cegah *N+1 Query Problem* (gunakan *Eager Loading*).
  - Jalankan `vendor/bin/pint` untuk memformat kode PHP setiap selesai membuat perubahan.
