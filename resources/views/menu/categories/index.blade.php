<x-app-layout>
    @section('page-title', 'Kategori Produk')
    @section('breadcrumb')
        <a href="{{ route('dashboard') }}" class="hover:text-green-600">Dashboard</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Kategori Produk</span>
    @endsection

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kategori Produk</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola kategori untuk mengelompokkan produk Anda.</p>
        </div>
        <a href="{{ route('menu.categories.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Kategori
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                        <th class="py-3 px-6">Nama Kategori</th>
                        <th class="py-3 px-6">Deskripsi</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-6 font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="py-3 px-6 text-gray-500">{{ Str::limit($category->description, 50) ?: '-' }}</td>
                        <td class="py-3 px-6">
                            @if($category->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Aktif</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('menu.categories.edit', $category) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form id="delete-form-{{ $category->id }}" action="{{ route('menu.categories.destroy', $category) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="$dispatch('confirm', {
                                        title: 'Hapus Kategori',
                                        message: 'Apakah Anda yakin ingin menghapus kategori {{ $category->name }}? Tindakan ini tidak dapat dibatalkan.',
                                        confirmText: 'Hapus',
                                        cancelText: 'Batal',
                                        variant: 'danger',
                                        onConfirm: () => document.getElementById('delete-form-{{ $category->id }}').submit()
                                    })" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">
                            Belum ada kategori produk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
