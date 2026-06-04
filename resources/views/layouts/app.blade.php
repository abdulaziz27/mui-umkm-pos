<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'MUI UMKM POS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16A34A">
    <link rel="apple-touch-icon" href="/images/logo-192.png">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 font-sans antialiased" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0" :class="{ 'lg:ml-64': sidebarOpen, 'lg:ml-20': !sidebarOpen }">
            <!-- Header -->
            @include('partials.header')

                <!-- Page Content -->
            <main class="flex-1 p-6">

                <!-- Page Header -->
                @if(isset($header))
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endif

                <!-- Flash Messages -->
                @if(session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif

                @if(session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif
                
                @if($errors->any())
                    <x-alert type="error" :message="$errors->first()" />
                @endif

                <!-- Main Slot -->
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="py-4 px-6 border-t border-gray-100 bg-white">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400 font-medium">
                    <div>
                        &copy; {{ date('Y') }} <span class="text-gray-600 font-semibold">{{ config('app.name') }}</span>. Hak Cipta Dilindungi.
                    </div>
                    <div class="flex items-center gap-2 bg-gray-50 px-3 py-1 rounded-full border border-gray-200/50">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <span class="text-gray-500 text-[10px] uppercase tracking-wider font-bold">MUI Cloud Verified</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- Global Confirmation Dialog --}}
    <x-delete-confirm />

    <!-- PWA Install Prompt Card -->
    <div x-data="{
            showPrompt: false,
            isIOS: false,
            deferredPrompt: null,
            init() {
                if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
                    return;
                }

                if (sessionStorage.getItem('pwa-prompt-dismissed') === 'true') {
                    return;
                }

                const ua = window.navigator.userAgent.toLowerCase();
                this.isIOS = /iphone|ipad|ipod/.test(ua);

                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;
                    this.showPrompt = true;
                });

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
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:translate-x-8"
        x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-4 left-4 right-4 sm:bottom-6 sm:right-6 sm:left-auto z-50 max-w-sm bg-white/95 backdrop-blur-md border border-green-100 rounded-2xl shadow-2xl p-4 flex flex-col gap-3"
        style="display: none;">
        
        <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl bg-green-600 flex-shrink-0 flex items-center justify-center shadow-md shadow-green-600/20">
                <img src="/images/logo-192.png" alt="MUI UMKM Logo" class="w-10 h-10 object-contain rounded-lg">
            </div>
            
            <div class="flex-1">
                <h4 class="font-bold text-gray-900 text-sm">Instal Aplikasi POS</h4>
                <p class="text-xs text-gray-600 mt-0.5 leading-relaxed" x-text="isIOS ? 'Tambahkan aplikasi kasir ini ke layar utama iPhone/iPad Anda untuk penggunaan cepat dan offline.' : 'Pasang aplikasi kasir MUI UMKM di layar perangkat Anda untuk akses lebih mudah dan cepat.'"></p>
            </div>

            <button @click="dismiss()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex items-center justify-end gap-2 border-t border-gray-50 pt-3">
            <button @click="dismiss()" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 rounded-xl hover:bg-gray-50 transition-colors">
                Nanti Saja
            </button>
            
            <button x-show="!isIOS" @click="install()" class="px-4 py-2 text-xs font-bold text-white bg-green-600 hover:bg-green-700 active:scale-95 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Pasang Aplikasi
            </button>

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

    @stack('scripts')

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
