<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">
                    @if(auth()->user()->isSuperAdmin())
                        Ringkasan platform dan performa seluruh mitra UMKM.
                    @else
                        Ringkasan aktivitas dan performa bisnis Anda.
                    @endif
                </p>
            </div>
            
            @if(isset($period))
            <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-xl border border-gray-200 shadow-sm" id="filterForm">
                <select name="period" class="border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-lg text-sm text-gray-700 bg-gray-50 font-medium cursor-pointer" onchange="toggleCustomDate(this.value); if(this.value !== 'custom') this.form.submit()">
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Kustom Rentang</option>
                </select>

                <div id="customDateContainer" class="flex items-center gap-2 {{ $period == 'custom' ? '' : 'hidden' }}">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-lg text-sm text-gray-700 bg-gray-50" onchange="if(this.form.end_date.value) this.form.submit()">
                    <span class="text-gray-400 font-medium">-</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-lg text-sm text-gray-700 bg-gray-50" onchange="if(this.form.start_date.value) this.form.submit()">
                </div>
            </form>
            <script>
                function toggleCustomDate(value) {
                    const container = document.getElementById('customDateContainer');
                    if (value === 'custom') {
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                        document.querySelector('input[name="start_date"]').value = '';
                        document.querySelector('input[name="end_date"]').value = '';
                    }
                }
            </script>
            @endif
        </div>
    </x-slot>

    @if(!auth()->user()->isSuperAdmin() && isset($tenant) && $tenant->status === 'pending')
        <div class="mb-8 p-4 bg-yellow-50 text-yellow-800 rounded-2xl border border-yellow-200 shadow-sm flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-yellow-900 mb-1">Toko Sedang Dalam Peninjauan</h3>
                <p class="text-sm leading-relaxed">Profil UMKM Anda saat ini berstatus <strong>Pending</strong>. Tim MUI sedang memverifikasi data Anda. Selama masa peninjauan, Anda tetap bisa menambahkan produk, mengatur kategori, dan melengkapi profil toko Anda. Fitur Kasir POS baru dapat digunakan setelah toko disetujui.</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @if(auth()->user()->isSuperAdmin())
            <!-- Cards for Super Admin -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">UMKM Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTenants) }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Menunggu Verifikasi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($pendingTenantsCount) }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Komisi Platform</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPlatformFee, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTransactions) }}</p>
                </div>
            </div>
        @else
            <!-- Cards for UMKM Owner -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Pendapatan ({{ $periodLabel }})</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($periodRevenue ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Netto ({{ $periodLabel }})</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($periodNetIncome ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($periodTransactions ?? 0) }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Produk Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    @if(auth()->user()->isSuperAdmin())
        <!-- Row 1: Chart & Pending Tenants -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
            <!-- Grafik Komisi Platform (Feature 2) -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Grafik Komisi Platform ({{ $periodLabel }})</h3>
                    <a href="{{ route('admin.commissions') }}" class="text-sm font-semibold text-green-600 hover:text-green-700 flex items-center gap-1">
                        Detail Komisi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- UMKM Menunggu Verifikasi (Persetujuan Cepat) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Verifikasi Cepat
                    </h3>
                    <a href="{{ route('admin.tenants') }}" class="text-xs font-semibold text-green-600 hover:text-green-700">Semua</a>
                </div>
                
                @if(isset($pendingTenants) && $pendingTenants->isNotEmpty())
                    <div class="space-y-4 flex-grow overflow-y-auto max-h-[220px] pr-1">
                        @foreach($pendingTenants as $tenant)
                            <div class="flex items-center justify-between p-3 bg-yellow-50/50 border border-yellow-100 rounded-xl">
                                <div class="overflow-hidden mr-2">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $tenant->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $tenant->phone ?? 'Tidak ada kontak' }}</p>
                                </div>
                                <form action="{{ route('admin.tenants.approve', $tenant->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 transition-colors shadow-sm whitespace-nowrap">
                                        Setujui
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex-grow flex flex-col items-center justify-center text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mb-3 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm font-medium">Semua pendaftaran UMKM telah diproses.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Row 2: Top Performing UMKM -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 font-sans">Performa Mitra UMKM Terbaik ({{ $periodLabel }})</h3>
                <span class="text-xs text-gray-500">Berdasarkan Omset Penjualan Terbesar</span>
            </div>
            
            @if(isset($topTenants) && $topTenants->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-400 text-xs uppercase font-semibold">
                                <th class="py-3 px-4">Nama UMKM</th>
                                <th class="py-3 px-4">Kontak</th>
                                <th class="py-3 px-4 text-right">Total Omset</th>
                                <th class="py-3 px-4 text-right">Komisi Platform</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @foreach($topTenants as $tenant)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-4 font-bold text-gray-900">{{ $tenant->name }}</td>
                                    <td class="py-4 px-4 text-gray-500">{{ $tenant->phone ?? '-' }}</td>
                                    <td class="py-4 px-4 text-right font-bold text-gray-900">
                                        Rp {{ number_format($tenant->transactions_sum_total_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-4 text-right font-bold text-green-600">
                                        Rp {{ number_format($tenant->transactions_sum_platform_fee ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center text-center text-gray-400 py-12">
                    <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="text-base font-semibold text-gray-600 mb-1">Belum ada aktivitas performa</p>
                    <p class="text-sm">Tidak ditemukan transaksi untuk UMKM manapun pada periode ini.</p>
                </div>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
            <!-- Grafik Penjualan (Feature 2) -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Grafik Penjualan ({{ $periodLabel }})</h3>
                    <a href="{{ route('pos.index') }}" class="text-sm font-semibold text-green-600 hover:text-green-700 flex items-center gap-1">
                        Buka POS <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Stok Menipis (Feature 4) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Peringatan Stok
                    </h3>
                </div>
                
                @if(isset($lowStockProducts) && $lowStockProducts->isNotEmpty())
                    <div class="space-y-4 flex-1">
                        @foreach($lowStockProducts as $product)
                            <div class="flex items-center justify-between p-3 bg-red-50/50 border border-red-100 rounded-xl">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-xs text-red-600 font-medium mt-0.5">Sisa stok: {{ $product->stock_quantity }}</p>
                                </div>
                                <a href="{{ route('menu.products.index') }}" class="px-3 py-1.5 bg-white text-red-600 text-xs font-bold rounded-lg border border-red-200 hover:bg-red-50 transition-colors shadow-sm">
                                    Restock
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mb-3 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm font-medium">Stok produk Anda dalam kondisi aman.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Produk Terlaris (Feature 1) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Produk Terlaris ({{ $periodLabel }})</h3>
                </div>
                @if(isset($topProducts) && $topProducts->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($topProducts as $index => $product)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $product->product_name }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($product->total_quantity) }} terjual</p>
                                    </div>
                                </div>
                                <p class="text-sm font-bold text-green-600">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <p class="text-sm font-medium">Belum ada data penjualan produk.</p>
                    </div>
                @endif
            </div>

            <!-- Breakdown Metode Pembayaran (Feature 2) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Metode Pembayaran ({{ $periodLabel }})</h3>
                </div>
                @if(isset($paymentMethods) && $paymentMethods->isNotEmpty())
                    <div class="space-y-5">
                        @php $totalAmount = $paymentMethods->sum('total'); @endphp
                        @foreach($paymentMethods as $method)
                            @php 
                                $percentage = $totalAmount > 0 ? ($method->total / $totalAmount) * 100 : 0;
                                $color = strtolower($method->payment_method) == 'qris' ? 'blue' : (strtolower($method->payment_method) == 'transfer' ? 'purple' : 'green');
                            @endphp
                            <div>
                                <div class="flex justify-between items-end mb-1.5">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 uppercase">{{ $method->payment_method }}</p>
                                        <p class="text-xs text-gray-500">{{ $method->count }} Transaksi</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($method->total, 0, ',', '.') }}</p>
                                        <p class="text-xs text-{{$color}}-600 font-semibold">{{ number_format($percentage, 1) }}%</p>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-{{$color}}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <p class="text-sm font-medium">Belum ada transaksi.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @push('scripts')
        @if(isset($chartLabels))
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('salesChart');
                    if (ctx) {
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($chartLabels) !!},
                                datasets: [{
                                    label: '{{ auth()->user()->isSuperAdmin() ? "Komisi Platform (Rp)" : "Omzet Penjualan (Rp)" }}',
                                    data: {!! json_encode($chartData) !!},
                                    borderColor: '#16a34a',
                                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                                    borderWidth: 3,
                                    pointBackgroundColor: '#fff',
                                    pointBorderColor: '#16a34a',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'Rp ' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endpush
</x-app-layout>
