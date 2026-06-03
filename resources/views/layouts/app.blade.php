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

    @stack('scripts')
</body>
</html>
