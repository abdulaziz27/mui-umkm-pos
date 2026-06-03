<x-app-layout>
    @section('page-title', 'Manajemen Produk')
    @section('breadcrumb')
        <a href="{{ route('dashboard') }}" class="hover:text-green-600">Dashboard</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Produk</span>
    @endsection

    @if(session('error_list'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm">
        <p class="text-sm font-bold mb-2">Detail Kesalahan Baris:</p>
        <ul class="list-disc list-inside text-xs text-red-600 space-y-1 ml-2">
            @foreach(session('error_list') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="mb-6 flex justify-between items-center" x-data="{ showImportModal: false }">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manajemen Produk</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola daftar barang dan jasa yang Anda jual.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="showImportModal = true" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 font-medium flex items-center gap-2 shadow-sm transition-colors">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import Excel
            </button>
            <a href="{{ route('menu.products.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium flex items-center gap-2 shadow-sm transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Produk
            </a>
        </div>

        <!-- Modal Import -->
        <div x-show="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm" style="display: none;">
            <div @click.away="showImportModal = false" class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all text-left">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Import Produk via Excel
                    </h3>
                    <button @click="showImportModal = false" class="text-gray-400 hover:bg-gray-100 rounded-full p-1 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-3">Unggah file Excel produk Anda untuk memasukkan produk secara masal. Pastikan format kolom sesuai dengan template kami.</p>
                    <a href="{{ route('menu.products.import.template') }}" class="inline-flex items-center gap-1.5 text-xs text-green-600 hover:text-green-700 font-bold bg-green-50 px-3 py-1.5 rounded-lg border border-green-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Unduh Template Excel
                    </a>
                </div>

                <form action="{{ route('menu.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pilih File Excel (.xlsx)</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <p class="text-xs text-gray-500"><span class="font-bold">Klik untuk unggah</span> atau drag file ke sini</p>
                                    <p class="text-[10px] text-gray-400 mt-1">Hanya mendukung format .xlsx atau .xls (Max. 5MB)</p>
                                </div>
                                <input type="file" name="excel_file" class="hidden" accept=".xlsx, .xls" required onchange="this.parentElement.querySelector('p.text-xs').innerHTML = '<span class=\'font-bold text-green-600\'>File terpilih:</span> ' + this.files[0].name">
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="showImportModal = false" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-xl transition-colors text-center text-sm">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-md transition-colors text-sm">
                            Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                        <th class="py-3 px-6">Produk</th>
                        <th class="py-3 px-6">Kategori</th>
                        <th class="py-3 px-6 text-right">Harga (Rp)</th>
                        <th class="py-3 px-6 text-center">Stok</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-6">
                            <div class="flex items-center gap-3">
                                @if($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->sku ?: 'No SKU' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-gray-700">{{ $product->category->name ?? '-' }}</td>
                        <td class="py-3 px-6 text-right font-medium text-gray-900">{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 text-center">
                            @if($product->type === 'physical')
                                <span class="{{ $product->stock_quantity > 10 ? 'text-green-600' : 'text-red-600' }} font-medium">{{ $product->stock_quantity ?? 0 }}</span>
                            @else
                                <span class="text-gray-400 text-sm">N/A</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            @if($product->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Aktif</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('menu.products.edit', $product) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form id="delete-form-{{ $product->id }}" action="{{ route('menu.products.destroy', $product) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="$dispatch('confirm', {
                                        title: 'Hapus Produk',
                                        message: 'Apakah Anda yakin ingin menghapus produk {{ $product->name }}? Tindakan ini tidak dapat dibatalkan.',
                                        confirmText: 'Hapus',
                                        cancelText: 'Batal',
                                        variant: 'danger',
                                        onConfirm: () => document.getElementById('delete-form-{{ $product->id }}').submit()
                                    })" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            Belum ada produk. Silakan tambah produk baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
