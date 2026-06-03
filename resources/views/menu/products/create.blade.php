<x-app-layout>
    @section('page-title', 'Tambah Produk')
    @section('breadcrumb')
        <a href="{{ route('menu.products.index') }}" class="hover:text-green-600">Produk</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Tambah</span>
    @endsection

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('menu.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Informasi Dasar</h3>
                <p class="text-sm text-gray-500 mb-6">Lengkapi data produk yang akan dijual.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Produk <span class="text-red-500">*</span></label>
                        <select name="type" id="type" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" onchange="document.getElementById('stock-container').style.display = this.value === 'physical' ? 'block' : 'none'">
                            <option value="physical" {{ old('type') == 'physical' ? 'selected' : '' }}>Fisik (Barang)</option>
                            <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Layanan (Jasa)</option>
                        </select>
                        @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU / Kode Barang</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Harga & Stok</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" id="price" min="0" required value="{{ old('price') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">Modal Dasar (Rp)</label>
                        <input type="number" name="cost" id="cost" min="0" value="{{ old('cost') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('cost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div id="stock-container" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6" style="display: {{ old('type', 'physical') === 'physical' ? 'grid' : 'none' }}">
                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stok Awal Saat Ini</label>
                            <input type="number" name="stock_quantity" id="stock_quantity" min="0" value="{{ old('stock_quantity') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('stock_quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="minimum_stock" class="block text-sm font-medium text-gray-700 mb-1">Batas Minimum Stok (Peringatan Lonceng)</label>
                            <input type="number" name="minimum_stock" id="minimum_stock" min="0" value="{{ old('minimum_stock', 10) }}" placeholder="Contoh: 10" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('minimum_stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Media & Detail Lainnya</h3>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk</label>
                    <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Produk Aktif (Bisa dibeli kasir)</label>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-6 border-t border-gray-100 bg-gray-50">
                <a href="{{ route('menu.products.index') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-medium">Batal</a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">Simpan Produk</button>
            </div>
        </form>
    </div>
</x-app-layout>
