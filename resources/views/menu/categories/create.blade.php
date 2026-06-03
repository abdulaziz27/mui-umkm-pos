<x-app-layout>
    @section('page-title', 'Tambah Kategori')
    @section('breadcrumb')
        <a href="{{ route('menu.categories.index') }}" class="hover:text-green-600">Kategori</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Tambah</span>
    @endsection

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('menu.categories.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm" placeholder="Misal: Minuman Dingin">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">Kategori Aktif</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('menu.categories.index') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Batal</a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
