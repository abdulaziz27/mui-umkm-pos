<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - MUI UMKM Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Left Panel - Branding (Identik dengan Login) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-green-600 via-green-700 to-green-900 relative overflow-hidden fixed h-screen">
            <!-- Decorative Circles -->
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center px-12 xl:px-20 text-white w-full h-full">
                <!-- Logo -->
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-wider">MUI UMKM POS</span>
                </div>

                <h1 class="text-4xl xl:text-5xl font-bold leading-tight mb-6">
                    Mulai Perjalanan Digital Bisnis Anda.
                </h1>

                <p class="text-lg text-green-100 mb-12 max-w-md">
                    Bergabung bersama ribuan UMKM lainnya. Dapatkan akses POS gratis, manajemen stok, dan katalog online langsung di bawah naungan Majelis Ulama Indonesia.
                </p>

                <!-- Features list -->
                <ul class="space-y-4">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                        <span class="font-medium text-green-50">Sistem Kasir (POS) 100% Gratis</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                        <span class="font-medium text-green-50">Direktori & Katalog Online Otomatis</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                        <span class="font-medium text-green-50">Sistem Laporan Transaksi Transparan</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Panel - Form (Scrolling) -->
        <div class="w-full lg:w-1/2 lg:ml-auto flex flex-col bg-white overflow-y-auto" x-data="{ 
            step: 1, 
            tenantName: '{{ old('tenant_name') }}', 
            tenantType: '{{ old('tenant_type') }}', 
            tenantPhone: '{{ old('tenant_phone') }}',
            nextStep() {
                if(this.tenantName && this.tenantType && this.tenantPhone) {
                    this.step = 2;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    alert('Mohon isi semua bidang yang bertanda bintang (*) sebelum melanjutkan.');
                }
            }
        }">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white sticky top-0 z-20">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="font-bold text-green-700">MUI UMKM</span>
                </div>
            </div>

            <!-- Form Container -->
            <div class="flex-1 px-6 py-8 sm:px-12 lg:px-16 xl:px-24 max-w-2xl mx-auto w-full">
                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Buat Akun UMKM</h2>
                    <p class="text-gray-500 mt-2">Daftarkan usaha Anda dalam dua langkah mudah.</p>
                </div>

                <!-- Progress Indicator -->
                <div class="relative mb-10">
                    <div class="overflow-hidden h-1.5 mb-3 text-xs flex rounded-full bg-gray-100">
                        <div :style="`width: ${step === 1 ? '50%' : '100%'}`" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500 transition-all duration-500"></div>
                    </div>
                    <div class="flex justify-between text-xs font-semibold text-gray-400">
                        <span :class="{'text-green-600': step >= 1}">1. Profil Usaha</span>
                        <span :class="{'text-green-600': step === 2}">2. Akun Akses</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- STEP 1: INFORMASI UMKM -->
                    <div x-show="step === 1" x-transition.opacity.duration.300ms>
                        <div class="space-y-5">
                            <!-- Nama UMKM -->
                            <div>
                                <label for="tenant_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Usaha (UMKM) *</label>
                                <input x-model="tenantName" type="text" id="tenant_name" name="tenant_name" required autofocus class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" placeholder="Contoh: Kedai Kopi Senja">
                                @error('tenant_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Tipe Usaha -->
                            <div>
                                <label for="tenant_type" class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Usaha *</label>
                                <select x-model="tenantType" id="tenant_type" name="tenant_type" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all appearance-none">
                                    <option value="" disabled>Pilih Kategori Utama...</option>
                                    <option value="retail">Retail / Toko Barang (Butik, Minimarket)</option>
                                    <option value="fnb">Makanan & Minuman (F&B / Kafe)</option>
                                    <option value="service">Jasa / Layanan (Salon, Bengkel)</option>
                                    <option value="other">Lainnya</option>
                                </select>
                                @error('tenant_type') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Nomor Telepon / WA -->
                            <div>
                                <label for="tenant_phone" class="block text-sm font-medium text-gray-700 mb-1.5">No. WhatsApp Bisnis *</label>
                                <input x-model="tenantPhone" type="text" id="tenant_phone" name="tenant_phone" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" placeholder="Contoh: 08123456789">
                                @error('tenant_phone') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Alamat -->
                            <div>
                                <label for="tenant_address" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap</label>
                                <textarea id="tenant_address" name="tenant_address" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all resize-none" placeholder="Tuliskan alamat lengkap agar mudah ditemukan">{{ old('tenant_address') }}</textarea>
                                @error('tenant_address') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="button" @click="nextStep()" class="w-full py-3.5 px-4 bg-gray-900 text-white font-medium rounded-xl hover:bg-green-600 focus:ring-4 focus:ring-green-500/20 transition-colors flex items-center justify-center gap-2 group">
                                Lanjut Langkah Berikutnya
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                            <p class="text-center text-sm text-gray-500 mt-4">
                                Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:text-green-700">Login sekarang</a>
                            </p>
                        </div>
                    </div>

                    <!-- STEP 2: INFORMASI PEMILIK -->
                    <div x-show="step === 2" style="display: none;" x-transition.opacity.duration.300ms>
                        <div class="space-y-5">
                            <!-- Nama Pemilik -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap Owner *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" placeholder="Sesuai identitas asli">
                                @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" placeholder="email@contoh.com">
                                @error('email') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Password -->
                            <div x-data="{ show: false }">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password *</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" id="password" name="password" required class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" placeholder="Minimal 8 karakter">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                                @error('password') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div x-data="{ show: false }">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password *</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" placeholder="Ketik ulang password">
                                </div>
                                @error('password_confirmation') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3">
                            <button type="button" @click="step = 1; window.scrollTo({ top: 0, behavior: 'smooth' });" class="w-full sm:w-1/3 py-3.5 px-4 bg-white border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                Kembali
                            </button>
                            <button type="submit" class="w-full sm:w-2/3 py-3.5 px-4 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 focus:ring-4 focus:ring-green-500/20 transition-all">
                                Selesaikan Pendaftaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
