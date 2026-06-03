<x-app-layout>
    @section('page-title', 'Buka Shift Kasir')

    <div class="max-w-md mx-auto mt-10">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 text-center">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Buka Shift Baru</h2>
                <p class="text-sm text-gray-500 mt-1">Masukkan modal awal (uang fisik) di dalam laci kasir saat ini.</p>
            </div>

            <div class="p-6">
                <form action="{{ route('pos.shift.open') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="starting_cash" class="block text-sm font-semibold text-gray-700 mb-2">Modal Awal Laci (Rp)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">Rp</span>
                            <input type="number" name="starting_cash" id="starting_cash" required min="0" step="500" class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 text-lg font-bold" placeholder="0">
                        </div>
                        @error('starting_cash')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('dashboard') }}" class="w-1/3 py-3 px-4 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-xl text-center hover:bg-gray-50 transition-colors">Batal</a>
                        <button type="submit" class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors flex items-center justify-center gap-2">
                            <span>Buka Shift & Masuk POS</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
