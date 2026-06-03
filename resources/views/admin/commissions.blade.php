<x-app-layout>
    @section('page-title', 'Laporan Komisi MUI')
    @section('breadcrumb')
        <a href="{{ route('dashboard') }}" class="hover:text-green-600">Dashboard</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Laporan Komisi</span>
    @endsection

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Laporan Komisi Platform</h2>
            <p class="text-sm text-gray-500 mt-1">Rincian seluruh transaksi dari mitra UMKM dan komisi MDR yang masuk.</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 font-medium flex items-center gap-2 shadow-sm transition-colors">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Laporan
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl border border-green-200 flex flex-col justify-center shadow-sm">
            <p class="text-sm font-medium text-green-800 mb-1">Total Pendapatan Komisi (MDR)</p>
            <p class="text-3xl font-extrabold text-green-700">Rp {{ number_format($totalCommission, 0, ',', '.') }}</p>
            <p class="text-xs text-green-600 mt-1">Berdasarkan filter aktif</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Omzet Kotor (Semua UMKM)</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalGrossSales, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Berdasarkan filter aktif</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Transaksi</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTransactions) }} <span class="text-sm font-normal text-gray-500">trx</span></p>
            <p class="text-xs text-gray-400 mt-1">Berdasarkan filter aktif</p>
        </div>
    </div>

    <!-- Filter Section (UX Upgrade) -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-8">
        <form method="GET" action="{{ route('admin.commissions') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Filter UMKM -->
            <div>
                <label for="tenant_id" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Filter UMKM</label>
                <select id="tenant_id" name="tenant_id" class="w-full px-3 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 text-sm transition-colors">
                    <option value="">Semua UMKM</option>
                    @foreach($tenants as $t)
                        <option value="{{ $t->id }}" {{ request('tenant_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Metode Pembayaran -->
            <div>
                <label for="payment_method" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Metode Bayar</label>
                <select id="payment_method" name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 text-sm transition-colors">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
            </div>

            <!-- Filter Rentang Tanggal -->
            <div class="md:col-span-2 grid grid-cols-2 gap-2">
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Dari Tanggal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 text-sm transition-colors">
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 text-sm transition-colors">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-4 flex justify-end gap-2 mt-2">
                @if(request()->anyFilled(['tenant_id', 'payment_method', 'start_date', 'end_date']))
                    <a href="{{ route('admin.commissions') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 text-sm font-medium transition-colors">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Riwayat Transaksi Global</h3>
            @if(request()->anyFilled(['tenant_id', 'payment_method', 'start_date', 'end_date']))
                <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold">Filter Aktif</span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-bold tracking-wider">
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Nama UMKM</th>
                        <th class="py-4 px-6 text-center">Metode</th>
                        <th class="py-4 px-6 text-right">Nilai Transaksi (Kotor)</th>
                        <th class="py-4 px-6 text-right">Komisi (MUI)</th>
                        <th class="py-4 px-6 text-right">Bersih ke UMKM</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 text-sm">
                            <p class="font-bold text-gray-900">{{ $trx->created_at->format('d M Y') }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">{{ $trx->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm font-extrabold text-gray-900">{{ $trx->tenant->name ?? 'Unknown' }}</p>
                            <span class="inline-flex px-2 py-0.5 bg-green-50 text-green-700 rounded-full text-[10px] font-medium uppercase mt-0.5">{{ $trx->tenant->type ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-[10px] font-extrabold uppercase">{{ $trx->payment_method }}</span>
                        </td>
                        <td class="py-4 px-6 text-right text-sm text-gray-900 font-bold">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="py-4 px-6 text-right text-sm font-extrabold text-green-600">+Rp {{ number_format($trx->platform_fee, 0, ',', '.') }}</td>
                        <td class="py-4 px-6 text-right text-sm text-gray-500">Rp {{ number_format($trx->net_amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Tidak ada transaksi yang cocok dengan filter yang ditentukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="p-5 border-t border-gray-100 bg-gray-50/50">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
