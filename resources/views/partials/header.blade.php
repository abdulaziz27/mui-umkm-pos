<!-- Header -->
<header class="sticky top-0 z-30 bg-white border-b border-gray-200">
    <div class="flex items-center justify-between h-16 px-6">
        <!-- Left side -->
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = true"
                    class="lg:hidden flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Page Title (Optional) -->
            <div>
                <h1 class="text-lg font-semibold text-gray-900 hidden sm:block">
                    @yield('page-title', 'Sistem POS UMKM MUI')
                </h1>
            </div>
        </div>

        <!-- Right side -->
        <div class="flex items-center gap-3">

            @if(auth()->check() && !auth()->user()->isSuperAdmin() && auth()->user()->tenant)
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-green-50 border border-green-200 rounded-lg">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="text-sm font-semibold text-green-700">{{ auth()->user()->tenant->name }}</span>
            </div>
            @endif

            <!-- Notifications (Low Stock Alerts) -->
            @php
                $lowStockProducts = collect();
                $lowStockCount = 0;
                if (auth()->check() && !auth()->user()->isSuperAdmin() && auth()->user()->tenant_id) {
                    $lowStockProducts = \App\Models\Product::where('tenant_id', auth()->user()->tenant_id)
                        ->where('type', 'physical')
                        ->whereColumn('stock_quantity', '<=', 'minimum_stock')
                        ->orderBy('stock_quantity', 'asc')
                        ->take(5)
                        ->get();
                    $lowStockCount = \App\Models\Product::where('tenant_id', auth()->user()->tenant_id)
                        ->where('type', 'physical')
                        ->whereColumn('stock_quantity', '<=', 'minimum_stock')
                        ->count();
                }
            @endphp

            <div x-data="{ openNotifications: false }" class="relative">
                <button @click="openNotifications = !openNotifications" class="relative flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($lowStockCount > 0)
                        <!-- Notification Badge -->
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                    @endif
                </button>

                <!-- Notifications Dropdown -->
                <div x-show="openNotifications"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     @click.away="openNotifications = false"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 overflow-hidden">
                    
                    <div class="px-4 py-2.5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <span class="text-xs font-bold text-gray-900 uppercase tracking-wider">Notifikasi Stok</span>
                        @if($lowStockCount > 0)
                            <span class="text-[10px] bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded-full">
                                {{ $lowStockCount }} Menipis
                            </span>
                        @endif
                    </div>

                    <div class="max-h-64 overflow-y-auto divide-y divide-gray-50">
                        @forelse($lowStockProducts as $product)
                            <div class="p-3.5 hover:bg-gray-50/60 transition-colors flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center flex-shrink-0 text-amber-600 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">
                                        Sisa stok: <span class="font-bold text-red-600">{{ $product->stock_quantity }} {{ $product->unit ?? 'pcs' }}</span> (Batas: {{ $product->minimum_stock }})
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-500">
                                <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs font-semibold text-gray-800">Semua Stok Aman</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Tidak ada produk dengan stok menipis saat ini.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    @if($lowStockCount > 0)
                        <div class="p-2 border-t border-gray-100 bg-gray-50/20 text-center">
                            <a href="{{ route('menu.products.index') }}" class="text-[11px] font-bold text-green-600 hover:text-green-700 transition-colors">
                                Kelola Semua Produk &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Menu -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">
                            {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'US' }}
                        </span>
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-medium text-gray-900 leading-tight">
                            {{ auth()->check() ? auth()->user()->name : 'Guest' }}
                        </p>
                        <p class="text-xs text-gray-500 leading-tight">{{ auth()->check() ? auth()->user()->role : 'User' }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     @click.away="open = false"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                    
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ auth()->check() ? auth()->user()->name : 'Guest' }}
                        </p>
                        <p class="text-xs text-gray-500 truncate mt-0.5">
                            {{ auth()->check() ? auth()->user()->email : '' }}
                        </p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-green-50 text-sm text-gray-700 hover:text-green-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profile
                    </a>

                    <div class="border-t border-gray-100 my-1"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 w-full px-4 py-2 hover:bg-red-50 text-sm text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
