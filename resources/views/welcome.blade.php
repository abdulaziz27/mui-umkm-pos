<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MUI UMKM - Platform Ekonomi Umat & Aplikasi Kasir Gratis</title>

    <!-- PWA Manifest & Meta -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16A34A">
    <link rel="apple-touch-icon" href="/images/logo-192.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-white text-gray-900 selection:bg-green-500 selection:text-white"
    x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Navigation -->
    <nav :class="{ 'bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm': scrolled, 'bg-transparent': !scrolled }"
        class="fixed top-0 inset-x-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-3 group">
                    <div
                        class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-600/30 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-gray-900">MUI <span
                            class="text-green-600">UMKM</span></span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#fitur"
                        class="text-sm font-semibold text-gray-600 hover:text-green-600 transition-colors">Fitur POS</a>
                    <a href="#katalog"
                        class="text-sm font-semibold text-gray-600 hover:text-green-600 transition-colors">Katalog
                        Mitra</a>
                    <a href="#panduan"
                        class="text-sm font-semibold text-gray-600 hover:text-green-600 transition-colors">Cara
                        Bergabung</a>
                </div>

                <!-- Desktop CTA -->
                <div class="hidden md:flex items-center gap-4">
                    <button x-data="{ isInstalled: false }" 
                            x-init="isInstalled = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true" 
                            x-show="!isInstalled" 
                            @click="$dispatch('trigger-pwa-install')" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-green-700 bg-green-50 hover:bg-green-100 rounded-xl transition-all border border-green-200/50 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Instal App
                    </button>
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-5 py-2.5 text-sm font-bold text-green-700 bg-green-50 hover:bg-green-100 rounded-xl transition-colors">Buka
                            Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-bold text-gray-700 hover:text-green-600 transition-colors">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-5 py-2.5 text-sm font-bold text-white bg-gray-900 hover:bg-green-600 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">Daftar
                                Sekarang</a>
                        @endif
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden p-2 text-gray-600 hover:text-gray-900">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak x-transition
            class="md:hidden bg-white border-b border-gray-100 px-4 pt-2 pb-6 shadow-xl absolute top-full inset-x-0">
            <div class="flex flex-col space-y-4">
                <a href="#fitur" @click="mobileMenuOpen = false"
                    class="text-base font-medium text-gray-700 hover:text-green-600">Fitur POS</a>
                <a href="#katalog" @click="mobileMenuOpen = false"
                    class="text-base font-medium text-gray-700 hover:text-green-600">Katalog Mitra</a>
                <a href="#panduan" @click="mobileMenuOpen = false"
                    class="text-base font-medium text-gray-700 hover:text-green-600">Cara Bergabung</a>
                
                <button x-data="{ isInstalled: false }" 
                        x-init="isInstalled = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true" 
                        x-show="!isInstalled" 
                        @click="mobileMenuOpen = false; $dispatch('trigger-pwa-install')" 
                        class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-3 text-sm font-bold text-green-700 bg-green-50 hover:bg-green-100 rounded-xl transition-all border border-green-200/50 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Instal Aplikasi POS
                </button>

                <hr class="border-gray-100">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="text-center py-3 font-bold text-white bg-green-600 rounded-xl">Buka Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-center py-3 font-bold text-gray-700 bg-gray-100 rounded-xl">Masuk ke Akun</a>
                    <a href="{{ route('register') }}"
                        class="text-center py-3 font-bold text-white bg-gray-900 rounded-xl">Daftar Sekarang</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute inset-0 bg-gray-50/50"></div>
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3">
            <div class="w-96 h-96 bg-green-400/20 rounded-full blur-3xl"></div>
        </div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3">
            <div class="w-[500px] h-[500px] bg-green-300/20 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-16 items-center">
                <!-- Hero Text -->
                <div class="lg:col-span-6 text-center lg:text-left mb-16 lg:mb-0">
                    <!-- <div
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-sm font-bold mb-6">
                        <span class="flex w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        Program Resmi Pemberdayaan Ekonomi Umat
                    </div> -->
                    <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-[1.15] mb-6">
                        Tingkatkan Kelas <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">Usaha
                            Anda</span> Bersama Kami.
                    </h1>
                    <p class="text-lg lg:text-xl text-gray-600 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Sistem Kasir (POS) gratis, manajemen stok, dan laporan keuangan terotomatisasi. Didesain khusus
                        untuk memajukan pengusaha UMKM di bawah naungan Majelis Ulama Indonesia.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}"
                            class="px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-2xl shadow-lg shadow-green-600/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            Mulai Gunakan Gratis
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <a href="#katalog"
                            class="px-8 py-4 bg-white hover:bg-gray-50 text-gray-900 border border-gray-200 font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                            Lihat Direktori UMKM
                        </a>
                    </div>

                    <div
                        class="mt-10 flex items-center justify-center lg:justify-start gap-4 text-sm text-gray-500 font-medium">
                        <div class="flex -space-x-2">
                            <div
                                class="w-8 h-8 rounded-full bg-green-100 border-2 border-white flex items-center justify-center text-xs font-bold text-green-700">
                                A</div>
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-xs font-bold text-blue-700">
                                B</div>
                            <div
                                class="w-8 h-8 rounded-full bg-yellow-100 border-2 border-white flex items-center justify-center text-xs font-bold text-yellow-700">
                                C</div>
                        </div>
                        <p>Bergabung dengan ratusan mitra UMKM lainnya.</p>
                    </div>
                </div>

                <!-- Hero Image / Mockup (Tablet POS Mockup) -->
                <div class="lg:col-span-6 relative">
                    <!-- Premium Tablet/iPad Frame -->
                    <div
                        class="relative bg-gray-900 rounded-[2.5rem] shadow-2xl p-3 border-4 border-gray-800 transform rotate-1 hover:rotate-0 transition-transform duration-500 max-w-lg mx-auto lg:max-w-none">
                        <!-- Camera Notch / Sensor -->
                        <div
                            class="absolute left-1/2 top-4 -translate-x-1/2 w-20 h-4 bg-gray-900 rounded-full z-20 flex items-center justify-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-gray-800 border border-gray-700"></div>
                        </div>

                        <!-- Screen Container -->
                        <div
                            class="relative aspect-square sm:aspect-[4/3] rounded-[2rem] overflow-hidden bg-gray-950 border border-gray-800">
                            <!-- Real POS Mockup Image -->
                            <img src="{{ asset('images/pos_mockup.png') }}" alt="MUI POS App Mockup"
                                class="w-full h-full object-cover">

                            <!-- Screen Glossy reflection overlay -->
                            <div
                                class="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/5 to-white/10 pointer-events-none">
                            </div>
                        </div>
                    </div>

                    <!-- Floating Sales Notification -->
                    <div
                        class="absolute -right-4 bottom-8 bg-white p-4 rounded-2xl shadow-xl border border-gray-100 animate-bounce flex items-center gap-4 z-10">
                        <div
                            class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Pesanan Baru</p>
                            <p class="text-sm font-extrabold text-gray-900">Rp 150.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Directory List -->
    <div id="katalog" class="py-24 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-4">Katalog Mitra UMKM
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl">Jelajahi dan dukung berbagai usaha mikro, kecil, dan
                        menengah yang telah terverifikasi dan tergabung dalam ekosistem platform ini.</p>
                </div>

                <!-- Search Bar -->
                <form action="{{ route('home') }}#katalog" method="GET" class="w-full md:w-96 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-11 pr-24 py-3 sm:text-sm border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500 shadow-sm bg-gray-50"
                        placeholder="Cari nama toko...">
                    <button type="submit"
                        class="absolute inset-y-1.5 right-1.5 px-4 bg-gray-900 hover:bg-green-600 text-white rounded-lg text-sm font-semibold transition-colors">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Category Filter Pills (UX Upgrade) -->
            <div class="flex flex-wrap gap-2 mb-8 mt-6">
                <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}#katalog"
                    class="px-4 py-2 rounded-xl text-sm font-bold tracking-wide transition-all shadow-sm border {{ !request('type') ? 'bg-green-600 text-white border-green-600' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                    Semua Kategori
                </a>
                <a href="{{ request()->fullUrlWithQuery(['type' => 'retail']) }}#katalog"
                    class="px-4 py-2 rounded-xl text-sm font-bold tracking-wide transition-all shadow-sm border {{ request('type') === 'retail' ? 'bg-green-600 text-white border-green-600' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                    Retail / Barang
                </a>
                <a href="{{ request()->fullUrlWithQuery(['type' => 'fnb']) }}#katalog"
                    class="px-4 py-2 rounded-xl text-sm font-bold tracking-wide transition-all shadow-sm border {{ request('type') === 'fnb' ? 'bg-green-600 text-white border-green-600' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                    Makanan & Minuman (F&B)
                </a>
                <a href="{{ request()->fullUrlWithQuery(['type' => 'service']) }}#katalog"
                    class="px-4 py-2 rounded-xl text-sm font-bold tracking-wide transition-all shadow-sm border {{ request('type') === 'service' ? 'bg-green-600 text-white border-green-600' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                    Jasa / Layanan
                </a>
                <a href="{{ request()->fullUrlWithQuery(['type' => 'other']) }}#katalog"
                    class="px-4 py-2 rounded-xl text-sm font-bold tracking-wide transition-all shadow-sm border {{ request('type') === 'other' ? 'bg-green-600 text-white border-green-600' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                    Lainnya
                </a>
            </div>

            @if(request('search') || request('type'))
                <div class="mb-8 flex flex-wrap gap-2">
                    @if(request('search'))
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200">
                            Pencarian: "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}#katalog"
                                class="text-gray-400 hover:text-red-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </span>
                    @endif
                    @if(request('type'))
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200 uppercase">
                            Kategori: {{ request('type') }}
                            <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}#katalog"
                                class="text-gray-400 hover:text-red-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </span>
                    @endif
                    <a href="{{ route('home') }}#katalog"
                        class="text-xs font-semibold text-red-600 hover:underline flex items-center gap-1 px-2">
                        Reset Semua Filter
                    </a>
                </div>
            @endif

            @if($tenants->isEmpty())
                <div class="bg-gray-50 rounded-3xl border border-gray-200 border-dashed p-16 text-center">
                    <div
                        class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-gray-100">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada UMKM Ditemukan</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Toko tidak ditemukan atau kami sedang menunggu verifikasi UMKM
                        pertama di platform ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                    @foreach($tenants as $tenant)
                        <a href="{{ route('public.umkm', $tenant->slug) }}"
                            class="group flex flex-col bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                            <div
                                class="h-32 bg-green-50 border-b border-gray-100 relative overflow-hidden flex items-center justify-center">
                                <!-- Background Pattern -->
                                <div
                                    class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
                                </div>

                                @if($tenant->logo_path)
                                    <img src="{{ Storage::url($tenant->logo_path) }}" alt="{{ $tenant->name }}"
                                        class="relative z-10 w-20 h-20 rounded-2xl border border-gray-100 shadow-md object-cover bg-white group-hover:scale-105 transition-transform">
                                @else
                                    <div
                                        class="relative z-10 w-20 h-20 rounded-2xl shadow-md bg-white flex items-center justify-center text-green-600 text-3xl font-extrabold uppercase group-hover:scale-105 transition-transform border border-green-100">
                                        {{ substr($tenant->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <h3
                                        class="text-lg font-bold text-gray-900 leading-tight group-hover:text-green-600 transition-colors">
                                        {{ $tenant->name }}
                                    </h3>
                                    <span
                                        class="inline-flex px-2.5 py-1 bg-green-50 text-green-700 rounded-md text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">{{ $tenant->type }}</span>
                                </div>
                                <p class="text-sm text-gray-500 line-clamp-2 mb-5 flex-1 leading-relaxed">
                                    {{ $tenant->description ?? 'UMKM ini belum memiliki deskripsi detail.' }}
                                </p>
                                <div
                                    class="flex items-center gap-2 text-sm font-semibold text-green-600 pt-4 border-t border-gray-50">
                                    Kunjungi Profil Toko <svg
                                        class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $tenants->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Features Section -->
    <div id="fitur" class="py-24 bg-gray-50 relative border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-green-600 font-bold tracking-wide uppercase text-sm mb-3">Keunggulan Platform</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Satu Aplikasi, Ratusan Manfaat</h3>
                <p class="text-lg text-gray-600">Tinggalkan pencatatan manual. Kami menyediakan alat digital kelas
                    profesional khusus untuk Anda, sepenuhnya gratis.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div
                    class="bg-white rounded-3xl p-8 hover:shadow-lg hover:-translate-y-1 transition-all group border border-gray-100">
                    <div
                        class="w-14 h-14 bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:bg-green-600 group-hover:text-white transition-colors text-gray-700">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Mesin Kasir Cerdas (POS)</h4>
                    <p class="text-gray-600 leading-relaxed">Kelola transaksi penjualan langsung dari HP atau Laptop.
                        Hitung total belanja, kembalian, dan cetak struk digital dalam hitungan detik.</p>
                </div>
                <!-- Feature 2 -->
                <div
                    class="bg-white rounded-3xl p-8 hover:shadow-lg hover:-translate-y-1 transition-all group border border-gray-100">
                    <div
                        class="w-14 h-14 bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:bg-green-600 group-hover:text-white transition-colors text-gray-700">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Manajemen Stok & Inventori</h4>
                    <p class="text-gray-600 leading-relaxed">Tidak perlu takut kehabisan barang. Sistem otomatis
                        mengurangi stok saat terjadi penjualan dan memberi tahu Anda produk terlaris.</p>
                </div>
                <!-- Feature 3 -->
                <div
                    class="bg-white rounded-3xl p-8 hover:shadow-lg hover:-translate-y-1 transition-all group border border-gray-100">
                    <div
                        class="w-14 h-14 bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:bg-green-600 group-hover:text-white transition-colors text-gray-700">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Laporan Keuangan Instan</h4>
                    <p class="text-gray-600 leading-relaxed">Pantau omzet harian, mingguan, hingga bulanan. Lihat
                        pendapatan bersih Anda kapan saja untuk pengambilan keputusan yang lebih baik.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How it works (Panduan) -->
    <div id="panduan" class="py-24 bg-gray-900 text-white relative overflow-hidden">
        <div
            class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Cara Mudah Bergabung</h2>
                <p class="text-gray-400 text-lg">Hanya butuh 3 langkah untuk mendigitalkan bisnis Anda bersama MUI.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 relative">
                <!-- Connecting Line (Desktop) -->
                <div class="hidden md:block absolute top-12 left-[15%] right-[15%] h-0.5 bg-gray-700"></div>

                <div class="text-center relative">
                    <div
                        class="w-24 h-24 bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mx-auto mb-6 relative z-10 text-3xl font-bold text-green-500 shadow-xl">
                        1
                    </div>
                    <h4 class="text-xl font-bold mb-2">Daftarkan Usaha</h4>
                    <p class="text-gray-400 text-sm px-4">Isi form pendaftaran singkat dengan profil usaha dan data
                        kontak Anda secara online.</p>
                </div>

                <div class="text-center relative">
                    <div
                        class="w-24 h-24 bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mx-auto mb-6 relative z-10 text-3xl font-bold text-green-500 shadow-xl">
                        2
                    </div>
                    <h4 class="text-xl font-bold mb-2">Proses Verifikasi</h4>
                    <p class="text-gray-400 text-sm px-4">Tim admin MUI akan meninjau dan memverifikasi profil usaha
                        Anda dalam 1x24 jam.</p>
                </div>

                <div class="text-center relative">
                    <div
                        class="w-24 h-24 bg-green-600 border-4 border-gray-900 rounded-full flex items-center justify-center mx-auto mb-6 relative z-10 text-3xl font-bold text-white shadow-xl shadow-green-600/30">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Mulai Jualan</h4>
                    <p class="text-gray-400 text-sm px-4">Setelah disetujui, toko Anda otomatis masuk Direktori Publik
                        dan fitur Kasir siap dipakai.</p>
                </div>
            </div>

            <div class="mt-16 text-center">
                <a href="{{ route('register') }}"
                    class="inline-flex px-8 py-4 bg-green-500 hover:bg-green-400 text-white font-bold rounded-xl transition-colors">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-600/30 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight">MUI UMKM POS</span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-sm mb-6">
                        Platform Point of Sales dan Direktori Resmi Majelis Ulama Indonesia. Membantu digitalisasi
                        jutaan UMKM di Indonesia secara gratis.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-4 uppercase text-sm tracking-wider">Tautan Cepat</h4>
                    <ul class="space-y-3">
                        <li><a href="#fitur" class="text-sm text-gray-500 hover:text-green-600 font-medium">Fitur
                                Kami</a></li>
                        <li><a href="#katalog" class="text-sm text-gray-500 hover:text-green-600 font-medium">Direktori
                                UMKM</a></li>
                        <li><a href="{{ route('register') }}"
                                class="text-sm text-gray-500 hover:text-green-600 font-medium">Daftar Jadi Mitra</a>
                        </li>
                        <li><a href="{{ route('login') }}"
                                class="text-sm text-gray-500 hover:text-green-600 font-medium">Login Akun</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-4 uppercase text-sm tracking-wider">Kontak Kami</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3 text-sm text-gray-500">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Gedung Majelis Ulama Indonesia Pusat<br>Jakarta, Indonesia
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-500">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            support@mui.or.id
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 py-6 text-center">
            <p class="text-gray-400 text-sm font-medium">
                &copy; {{ date('Y') }} MUI Pusat. Hak Cipta Dilindungi Undang-Undang.
            </p>
        </div>
    </footer>

    <!-- PWA Install Prompt Card -->
    <div x-data="{
            showPrompt: false,
            isIOS: false,
            deferredPrompt: null,
            init() {
                // Check if already in standalone mode
                if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
                    return;
                }

                // Check if user dismissed it in this session
                if (sessionStorage.getItem('pwa-prompt-dismissed') === 'true') {
                    return;
                }

                // Detect iOS
                const ua = window.navigator.userAgent.toLowerCase();
                this.isIOS = /iphone|ipad|ipod/.test(ua);

                // Listen for beforeinstallprompt (Android / Chrome / Desktop)
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;
                    this.showPrompt = true;
                });

                // Show prompt on iOS Safari after a short delay (e.g. 3 seconds)
                if (this.isIOS && !('standalone' in window.navigator && window.navigator.standalone)) {
                    setTimeout(() => {
                        this.showPrompt = true;
                    }, 3000);
                }
            },
            async install() {
                if (this.deferredPrompt) {
                    this.deferredPrompt.prompt();
                    const { outcome } = await this.deferredPrompt.userChoice;
                    if (outcome === 'accepted') {
                        console.log('User accepted the PWA install prompt');
                    }
                    this.deferredPrompt = null;
                    this.showPrompt = false;
                }
            },
            dismiss() {
                this.showPrompt = false;
                sessionStorage.setItem('pwa-prompt-dismissed', 'true');
            }
        }"
        x-show="showPrompt"
        @trigger-pwa-install.window="showPrompt = true; if (!isIOS && deferredPrompt) install()"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:translate-x-8"
        x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-4 left-4 right-4 sm:bottom-6 sm:right-6 sm:left-auto z-50 max-w-sm bg-white/95 backdrop-blur-md border border-green-100 rounded-2xl shadow-2xl p-4 flex flex-col gap-3"
        style="display: none;">
        
        <div class="flex items-start gap-3">
            <!-- Logo Icon -->
            <div class="w-12 h-12 rounded-xl bg-green-600 flex-shrink-0 flex items-center justify-center shadow-md shadow-green-600/20">
                <img src="/images/logo-192.png" alt="MUI UMKM Logo" class="w-10 h-10 object-contain rounded-lg">
            </div>
            
            <!-- Text details -->
            <div class="flex-1">
                <h4 class="font-bold text-gray-900 text-sm">Instal Aplikasi POS</h4>
                <p class="text-xs text-gray-600 mt-0.5 leading-relaxed" x-text="isIOS ? 'Tambahkan aplikasi kasir ini ke layar utama iPhone/iPad Anda untuk penggunaan cepat dan offline.' : 'Pasang aplikasi kasir MUI UMKM di layar perangkat Anda untuk akses lebih mudah dan cepat.'"></p>
            </div>

            <!-- Close Button -->
            <button @click="dismiss()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-2 border-t border-gray-50 pt-3">
            <button @click="dismiss()" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 rounded-xl hover:bg-gray-50 transition-colors">
                Nanti Saja
            </button>
            
            <!-- Standard PWA install trigger -->
            <button x-show="!isIOS" @click="install()" class="px-4 py-2 text-xs font-bold text-white bg-green-600 hover:bg-green-700 active:scale-95 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Pasang Aplikasi
            </button>

            <!-- IOS Instruction Helper -->
            <div x-show="isIOS" class="text-[10px] text-gray-500 font-medium bg-green-50/50 border border-green-100/50 rounded-xl px-3 py-1.5 flex items-center gap-1.5">
                <span>Ketuk</span>
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 10.742l-4.472 2.236a2 2 0 00-.974 1.708V20a2 2 0 002 2h13.684a2 2 0 002-2v-5.314a2 2 0 00-.974-1.708l-4.472-2.236a2 2 0 00-1.708 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v11m0-11L9 8m3-3l3 3" />
                </svg>
                <span>lalu <strong>"Tambah ke Layar Utama"</strong></span>
            </div>
        </div>
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(error => {
                    console.error('ServiceWorker registration failed:', error);
                });
            });
        }
    </script>
</body>

</html>