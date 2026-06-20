<x-app-layout>
    @section('page-title', 'Riwayat Transaksi')
    @section('breadcrumb')
        <a href="{{ route('dashboard') }}" class="hover:text-green-600">Dashboard</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Laporan Transaksi</span>
    @endsection

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Riwayat Transaksi</h2>
            <p class="text-sm text-gray-500 mt-1">Laporan penjualan dan rincian seluruh transaksi.</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('report.transactions') }}" class="flex items-center">
                <select name="period" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl py-2 pl-4 pr-8 hover:bg-gray-100 transition-colors focus:ring-green-500 focus:border-green-500 shadow-sm cursor-pointer">
                    <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_week" {{ $period === 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="last_30_days" {{ $period === 'last_30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="this_year" {{ $period === 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                </select>
            </form>
            <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>
            <a href="{{ route('report.transactions.export.csv', ['period' => $period]) }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
            <a href="{{ route('report.transactions.print', ['period' => $period]) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak PDF
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Omzet (Keseluruhan)</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Potongan Platform (MUI)</p>
            <p class="text-2xl font-bold text-red-500">-Rp {{ number_format($totalPlatformFee, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Pendapatan Bersih</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalNetIncome, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Jumlah Transaksi</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTransactions) }} <span class="text-sm font-normal text-gray-500">trx</span></p>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Daftar Transaksi Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                        <th class="py-3 px-6">Tanggal & Waktu</th>
                        <th class="py-3 px-6">No. Resi</th>
                        <th class="py-3 px-6 text-center">Metode</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-right">Total Kotor</th>
                        <th class="py-3 px-6 text-right">Fee Platform</th>
                        <th class="py-3 px-6 text-right">Pendapatan Bersih</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-6 text-sm">
                            <p class="font-medium text-gray-900">{{ $trx->created_at->format('d M Y') }}</p>
                            <p class="text-gray-500">{{ $trx->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="py-3 px-6 text-sm font-medium text-blue-600">{{ $trx->receipt_number }}</td>
                        <td class="py-3 px-6 text-center">
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold uppercase">{{ $trx->payment_method }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            @if($trx->payment_status === 'paid')
                                <span class="px-2 py-1 bg-green-50 text-green-700 border border-green-200 rounded-lg text-xs font-bold">SUKSES</span>
                            @elseif($trx->payment_status === 'failed')
                                <span class="px-2 py-1 bg-red-50 text-red-700 border border-red-200 rounded-lg text-xs font-bold">VOID / BATAL</span>
                            @else
                                <span class="px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold">{{ strtoupper($trx->payment_status) }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-right text-sm text-gray-900 font-medium">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 text-right text-sm text-red-500">- Rp {{ number_format($trx->platform_fee, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 text-right text-sm text-green-600 font-bold">Rp {{ number_format($trx->total_amount - $trx->platform_fee, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 text-right">
                            <a href="{{ route('report.transactions.show', $trx->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-200 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500">
                            Belum ada riwayat transaksi yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
