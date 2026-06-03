<x-app-layout>
    @section('page-title', 'Buat Kupon Baru')
    @section('breadcrumb')
        <a href="{{ route('menu.promos.index') }}" class="hover:text-green-600">Promo & Diskon</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Buat Kupon</span>
    @endsection

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('menu.promos.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Informasi Kode Kupon</h3>
                <p class="text-sm text-gray-500 mb-6">Tambahkan detail diskon dan masa berlaku kupon baru.</p>

                <div class="space-y-5">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kode Promo <span class="text-red-500">*</span></label>
                        <input type="text" name="code" id="code" required value="{{ old('code') }}" placeholder="Contoh: RAMADHAN" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 uppercase">
                        <p class="text-xs text-gray-500 mt-1">Kode promo akan otomatis diubah menjadi huruf kapital.</p>
                        @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Diskon <span class="text-red-500">*</span></label>
                            <select name="type" id="type" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                <option value="nominal" {{ old('type') === 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                            </select>
                            @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Nilai Diskon <span class="text-red-500">*</span></label>
                            <input type="number" name="value" id="value" min="1" required value="{{ old('value') }}" placeholder="Contoh: 10 atau 15000" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('value') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Berlaku Sampai (Opsional)</label>
                        <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika promo ini berlaku selamanya.</p>
                        @error('expires_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-6 bg-gray-50">
                <a href="{{ route('menu.promos.index') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-medium transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">Buat Kupon</button>
            </div>
        </form>
    </div>
</x-app-layout>
