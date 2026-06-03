<x-app-layout>
    @section('page-title', 'Audit Shift Kasir')

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Audit Laporan Shift</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau kejujuran kasir dan rekapitulasi laci kas tunai.</p>
        </div>
    </div>

    <!-- Tabel Daftar Shift -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 rounded-tl-2xl">Kasir</th>
                        <th scope="col" class="px-6 py-4">Waktu (Buka - Tutup)</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Modal Awal</th>
                        <th scope="col" class="px-6 py-4 text-right">Fisik Dihitung</th>
                        <th scope="col" class="px-6 py-4 text-right rounded-tr-2xl">Selisih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($shifts as $shift)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $shift->cashier->name }}
                        </td>
                        <td class="px-6 py-4">
                            <div>Mulai: <span class="font-semibold">{{ $shift->opened_at->format('d/m/Y H:i') }}</span></div>
                            <div class="text-gray-400 mt-0.5">Tutup: {{ $shift->closed_at ? $shift->closed_at->format('d/m/Y H:i') : 'Belum ditutup' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($shift->status === 'open')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                    Sedang Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                    Selesai
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            Rp {{ number_format($shift->starting_cash, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($shift->actual_ending_cash !== null)
                                Rp {{ number_format($shift->actual_ending_cash, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($shift->difference !== null)
                                @if($shift->difference == 0)
                                    <span class="text-gray-500 font-bold">Rp 0</span>
                                @elseif($shift->difference > 0)
                                    <span class="text-green-600 font-bold">+ Rp {{ number_format($shift->difference, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-red-600 font-bold">- Rp {{ number_format(abs($shift->difference), 0, ',', '.') }}</span>
                                @endif
                            @else
                                <span class="text-gray-400 italic">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="text-lg font-medium text-gray-900">Belum Ada Riwayat Shift</p>
                            <p class="mt-1 text-sm text-gray-500">Data shift kasir akan muncul di sini setelah kasir membuka dan menutup shift.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shifts->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $shifts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
