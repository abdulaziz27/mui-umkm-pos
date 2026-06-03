# MUI UMKM SaaS - Project Blueprint & AI Guidelines

## 1. Project Overview
- **Name**: MUI UMKM Platform & POS (Temporary Name)
- **Objective**: Platform direktori untuk memperkenalkan UMKM (beragam jenis) ke publik, sekaligus memberikan aplikasi POS (Kasir) gratis bagi UMKM yang terdaftar.
- **Monetization**: Bebas biaya langganan bulanan. Platform dimonetisasi melalui komisi/MDR per transaksi (Fee per-transaction).
- **Target Audience**: Sangat variatif (Warung, Bengkel, Salon, Barbershop, Retail, dll). Sistem **wajib bersifat General (MVP)** dan tidak boleh bias ke satu jenis bisnis tertentu (misal: jangan terlalu fokus ke fitur restoran/F&B).

## 2. Core Architecture
- **Tech Stack**: Laravel Latest Version, Tailwind CSS Latest Version, Alpine.js Latest Version, MySQL, Redis.
- **Pattern**: Multi-tenancy dasar (Satu UMKM = Satu Tenant).
- **Monetization Engine**: Split payment / commission-based (bukan subscription base).

## 3. Key Modules (MVP Scope)
1. **Public Directory Portal (Web)**
   - Halaman utama (Showcase) untuk publik.
   - Katalog UMKM dengan fitur pencarian dan filter (Kategori, Lokasi).
   - Halaman detail Profil tiap UMKM (Info, Layanan/Menu, Sertifikasi).
2. **Tenant/UMKM Dashboard (POS & Management)**
   - Manajemen Master Data (Kategori, Layanan/Produk).
   - Tipe Item Generik: `physical` (ada stok, cth: sparepart, makanan) dan `service` (jasa, cth: potong rambut, cuci motor).
   - Sistem POS/Kasir sederhana yang cepat dan mudah dimengerti siapa saja.
   - Laporan penjualan & rekap potongan komisi per transaksi.
3. **Super Admin Dashboard (MUI / Pengelola)**
   - Verifikasi & Approval pendaftaran UMKM.
   - Dashboard analitik: total transaksi, komisi masuk, dll.

## 4. AI Development Rules (CRITICAL FOR GEMINI)
Saat mengeksekusi instruksi, AI (Gemini) harus SELALU mengacu pada aturan berikut:
1. **KEEP IT GENERAL**: Jangan membuat atau merancang struktur tabel yang terlalu spesifik untuk restoran (seperti tabel _table management_, _kitchen status_, atau _recipe/BOM_).
2. **NO SUBSCRIPTION LOGIC**: Hindari implementasi _billing tier_ langganan (SaaS bulanan). Fokus pada struktur `platform_fee` atau komisi di dalam detail transaksi.
3. **PRODUCT TYPES**: Struktur item/produk harus punya tipe. Ini untuk memfasilitasi bisnis jasa (tidak butuh inventory) dan bisnis retail (butuh potong stok).
4. **MODULAR & INCREMENTAL**: Kerjakan fitur selangkah demi selangkah sesuai roadmap. Jangan over-engineer di awal.
5. **FOLLOW BOOST GUIDELINES**: Tetap ikuti Laravel Boost Guidelines yang ada di rules (Laravel 12 structure, Pint formatting, TDD, dll).

## 5. Roadmap Development (Next Steps)
- [ ] Fase 1: Setup skeleton Laravel 12 & arsitektur Database (Multi-tenancy UMKM & User Roles).
- [ ] Fase 2: Auth System & Approval Flow (Super Admin & UMKM).
- [ ] Fase 3: Modul Master Data Produk & Jasa yang generik.
- [ ] Fase 4: Engine Transaksi & Kasir POS + Logika Kalkulasi Fee.
- [ ] Fase 5: Frontend Public Directory Portal.
