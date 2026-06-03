<x-app-layout>
    @section('page-title', 'Tutup Shift Kasir')

    <div class="max-w-xl mx-auto mt-10">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="closeShiftForm()">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Rekapitulasi Shift</h2>
                    <p class="text-sm text-gray-500 mt-1">Selesaikan shift dan hitung setoran tunai.</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Rincian Shift Sistem -->
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Mulai Shift</span>
                        <span class="font-medium text-gray-900">{{ $shift->opened_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Modal Awal</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($shift->starting_cash, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Penjualan Tunai</span>
                        <span class="font-medium text-gray-900">+ Rp {{ number_format($cashSales, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-200 flex justify-between">
                        <span class="font-bold text-gray-700">Estimasi Uang Fisik (Sistem)</span>
                        <span class="font-black text-gray-900">Rp {{ number_format($expectedCash, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form action="{{ route('pos.shift.close') }}" method="POST">
                    @csrf
                    
                    <div class="mb-2">
                        <label for="actual_ending_cash" class="block text-sm font-semibold text-gray-700 mb-2">Hitungan Uang Fisik Aktual (Laci Kasir)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">Rp</span>
                            <input type="number" name="actual_ending_cash" id="actual_ending_cash" required min="0" step="100" 
                                x-model="actualCash" @input="calculateDifference"
                                class="w-full pl-12 pr-4 py-4 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white text-xl font-black shadow-sm" placeholder="0">
                        </div>
                    </div>

                    <!-- Indikator Selisih -->
                    <div class="mb-8 p-3 rounded-lg flex items-center justify-between transition-colors" 
                         :class="{'bg-gray-50': difference === 0, 'bg-green-50': difference > 0, 'bg-red-50': difference < 0}">
                        <span class="text-sm font-semibold" 
                              :class="{'text-gray-600': difference === 0, 'text-green-700': difference > 0, 'text-red-700': difference < 0}">
                            Status Selisih
                        </span>
                        <span class="font-bold text-lg" 
                              :class="{'text-gray-900': difference === 0, 'text-green-600': difference > 0, 'text-red-600': difference < 0}"
                              x-text="formatDifference(difference)">
                        </span>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('pos.index') }}" class="w-1/3 py-3 px-4 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-xl text-center hover:bg-gray-50 transition-colors">Batal</a>
                        <button type="submit" class="flex-1 py-3 px-4 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl shadow-sm transition-colors flex items-center justify-center gap-2">
                            <span>Akhiri Shift Kasir</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function closeShiftForm() {
            return {
                expectedCash: {{ $expectedCash }},
                actualCash: '',
                difference: null,

                calculateDifference() {
                    const actual = parseFloat(this.actualCash) || 0;
                    this.difference = actual - this.expectedCash;
                },

                formatDifference(val) {
                    if (val === null || this.actualCash === '') return 'Belum Dihitung';
                    const num = Math.abs(val).toLocaleString('id-ID');
                    if (val === 0) return 'Seimbang (Klop)';
                    if (val > 0) return '+ Rp ' + num + ' (Lebih)';
                    return '- Rp ' + num + ' (Kurang)';
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
