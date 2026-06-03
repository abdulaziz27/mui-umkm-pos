<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - MUI UMKM Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Left Panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-green-600 via-green-700 to-green-900 relative overflow-hidden">
            <!-- Decorative Circles -->
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center px-12 xl:px-20 text-white w-full">
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
                    Mendukung Pertumbuhan Bisnis Lokal.
                </h1>

                <p class="text-lg text-green-100 mb-12 max-w-md">
                    Platform POS gratis yang didesain untuk semua jenis UMKM. Cepat, ringan, dan mudah digunakan.
                </p>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 mb-12 border-t border-white/20 pt-8">
                    <div class="text-left">
                        <p class="text-3xl font-bold">100%</p>
                        <p class="text-sm text-green-200">Gratis Langganan</p>
                    </div>
                    <div class="text-left">
                        <p class="text-3xl font-bold">POS</p>
                        <p class="text-sm text-green-200">Semua Tipe Usaha</p>
                    </div>
                    <div class="text-left">
                        <p class="text-3xl font-bold">Aman</p>
                        <p class="text-sm text-green-200">Terverifikasi MUI</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="w-full lg:w-1/2 flex flex-col bg-white">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center justify-center gap-3 py-6 border-b border-gray-100">
                <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-green-700">MUI UMKM</span>
            </div>

            <!-- Form Container -->
            <div class="flex-1 flex items-center justify-center px-6 py-8 sm:px-12 lg:px-16 xl:px-24">
                <div class="w-full max-w-md">
                    <!-- Header -->
                    <div class="mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Selamat Datang!</h2>
                        <p class="text-gray-500 mt-2">Silakan masuk ke dashboard Anda.</p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all @error('email') border-red-500 @enderror" placeholder="email@contoh.com">
                            </div>
                            @error('email') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div x-data="{ show: false }">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                                <input :type="show ? 'text' : 'password'" id="password" name="password" required class="w-full pl-11 pr-12 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all @error('password') border-red-500 @enderror" placeholder="••••••••">
                                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            @error('password') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500/20">
                                <span class="text-sm text-gray-600">Ingat Saya</span>
                            </label>
                            <a href="#" class="text-sm text-green-600 hover:text-green-700 font-medium">Lupa password?</a>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 focus:ring-4 focus:ring-green-500/20 transition-all flex items-center justify-center gap-2">
                            Masuk
                        </button>
                    </form>
                                 <!-- Premium Demo Credentials Panel -->
                    <div x-data="{ currentTab: 'fnb' }" class="mt-8 p-5 bg-green-50/50 rounded-2xl border border-green-100/80 backdrop-blur-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-green-900">Demo Quick Login</p>
                                <p class="text-xs text-green-600">Klik salah satu akun untuk langsung mengisi form.</p>
                            </div>
                        </div>

                        <!-- Tab Header -->
                        <div class="flex gap-1 bg-green-100/50 p-1 rounded-xl mb-4 text-xs font-semibold">
                            <button type="button" @click="currentTab = 'fnb'" :class="currentTab === 'fnb' ? 'bg-white text-green-800 shadow-sm' : 'text-green-600 hover:text-green-800'" class="flex-1 py-2 rounded-lg transition-all text-center">
                                F&B
                            </button>
                            <button type="button" @click="currentTab = 'retail'" :class="currentTab === 'retail' ? 'bg-white text-green-800 shadow-sm' : 'text-green-600 hover:text-green-800'" class="flex-1 py-2 rounded-lg transition-all text-center">
                                Oleh-Oleh
                            </button>
                            <button type="button" @click="currentTab = 'service'" :class="currentTab === 'service' ? 'bg-white text-green-800 shadow-sm' : 'text-green-600 hover:text-green-800'" class="flex-1 py-2 rounded-lg transition-all text-center">
                                Bengkel
                            </button>
                            <button type="button" @click="currentTab = 'admin'" :class="currentTab === 'admin' ? 'bg-white text-green-800 shadow-sm' : 'text-green-600 hover:text-green-800'" class="flex-1 py-2 rounded-lg transition-all text-center">
                                Admin
                            </button>
                        </div>

                        <!-- Tab Contents -->
                        <div class="space-y-2">
                            <!-- F&B (Cafe) -->
                            <div x-show="currentTab === 'fnb'" class="space-y-2" x-transition>
                                <button type="button" onclick="fillCredentials('sarah@kopisenja.com')" class="w-full p-3 bg-white hover:bg-green-50/50 rounded-xl border border-gray-100 hover:border-green-200 transition-all text-left flex justify-between items-center group shadow-sm">
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-green-700 text-xs">Sarah Wijaya (Owner)</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">sarah@kopisenja.com</p>
                                    </div>
                                    <span class="text-[10px] font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full border border-amber-100">Kopi Senja</span>
                                </button>
                                <button type="button" onclick="fillCredentials('rian@kopisenja.com')" class="w-full p-3 bg-white hover:bg-green-50/50 rounded-xl border border-gray-100 hover:border-green-200 transition-all text-left flex justify-between items-center group shadow-sm">
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-green-700 text-xs">Rian Hidayat (Kasir)</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">rian@kopisenja.com</p>
                                    </div>
                                    <span class="text-[10px] font-bold bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-100">POS Kasir</span>
                                </button>
                            </div>

                            <!-- Retail (Oleh-Oleh) -->
                            <div x-show="currentTab === 'retail'" class="space-y-2" x-transition x-cloak>
                                <button type="button" onclick="fillCredentials('slamet@oleholeh.com')" class="w-full p-3 bg-white hover:bg-green-50/50 rounded-xl border border-gray-100 hover:border-green-200 transition-all text-left flex justify-between items-center group shadow-sm">
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-green-700 text-xs">Bapak Slamet (Owner)</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">slamet@oleholeh.com</p>
                                    </div>
                                    <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-100">Oleh-Oleh</span>
                                </button>
                                <button type="button" onclick="fillCredentials('ratna@oleholeh.com')" class="w-full p-3 bg-white hover:bg-green-50/50 rounded-xl border border-gray-100 hover:border-green-200 transition-all text-left flex justify-between items-center group shadow-sm">
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-green-700 text-xs">Ratna Ayu (Kasir)</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">ratna@oleholeh.com</p>
                                    </div>
                                    <span class="text-[10px] font-bold bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-100">POS Kasir</span>
                                </button>
                            </div>

                            <!-- Services (Bengkel) -->
                            <div x-show="currentTab === 'service'" class="space-y-2" x-transition x-cloak>
                                <button type="button" onclick="fillCredentials('budi@bengkelbarokah.com')" class="w-full p-3 bg-white hover:bg-green-50/50 rounded-xl border border-gray-100 hover:border-green-200 transition-all text-left flex justify-between items-center group shadow-sm">
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-green-700 text-xs">Budi Santoso (Owner)</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">budi@bengkelbarokah.com</p>
                                    </div>
                                    <span class="text-[10px] font-bold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full border border-indigo-100">Bengkel</span>
                                </button>
                                <button type="button" onclick="fillCredentials('andi@bengkelbarokah.com')" class="w-full p-3 bg-white hover:bg-green-50/50 rounded-xl border border-gray-100 hover:border-green-200 transition-all text-left flex justify-between items-center group shadow-sm">
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-green-700 text-xs">Andi Pratama (Kasir)</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">andi@bengkelbarokah.com</p>
                                    </div>
                                    <span class="text-[10px] font-bold bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-100">POS Kasir</span>
                                </button>
                            </div>

                            <!-- Admin (MUI Platform) -->
                            <div x-show="currentTab === 'admin'" class="space-y-2" x-transition x-cloak>
                                <button type="button" onclick="fillCredentials('admin@mui.or.id')" class="w-full p-3 bg-white hover:bg-green-50/50 rounded-xl border border-gray-100 hover:border-green-200 transition-all text-left flex justify-between items-center group shadow-sm">
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-green-700 text-xs">MUI Admin (Superadmin)</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">admin@mui.or.id</p>
                                    </div>
                                    <span class="text-[10px] font-bold bg-purple-50 text-purple-700 px-2 py-0.5 rounded-full border border-purple-100">MUI Pusat</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <script>
                        function fillCredentials(email) {
                            document.getElementById('email').value = email;
                            document.getElementById('password').value = 'password';
                            document.getElementById('email').focus();
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
