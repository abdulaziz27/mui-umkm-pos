<x-app-layout>
    @section('page-title', 'Kelola Kode Promo')
    @section('breadcrumb')
        <a href="{{ route('dashboard') }}" class="hover:text-green-600">Dashboard</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Promo & Diskon</span>
    @endsection

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Promo & Diskon</h2>
            <p class="text-sm text-gray-500 mt-1">Buat kupon promo untuk menarik lebih banyak pelanggan.</p>
        </div>
        <a href="{{ route('menu.promos.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium flex items-center gap-2 shadow-sm transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Kupon Baru
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                        <th class="py-3 px-6">Kode Kupon</th>
                        <th class="py-3 px-6 text-center">Tipe</th>
                        <th class="py-3 px-6 text-right">Nilai Diskon</th>
                        <th class="py-3 px-6 text-center">Berlaku Sampai</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($promos as $promo)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-6">
                            <span class="inline-block bg-green-50 text-green-700 font-bold px-3 py-1 rounded-lg text-sm tracking-wider uppercase border border-green-200">
                                {{ $promo->code }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center text-gray-700">
                            {{ $promo->type === 'percentage' ? 'Persentase (%)' : 'Nominal (Rp)' }}
                        </td>
                        <td class="py-3 px-6 text-right font-medium text-gray-900">
                            {{ $promo->type === 'percentage' ? floatval($promo->value) . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-6 text-center text-sm">
                            @if($promo->expires_at)
                                @if(now()->startOfDay()->gt($promo->expires_at))
                                    <span class="text-red-500 font-medium">{{ $promo->expires_at->format('d M Y') }} (Kadaluarsa)</span>
                                @else
                                    <span class="text-gray-700">{{ $promo->expires_at->format('d M Y') }}</span>
                                @endif
                            @else
                                <span class="text-gray-400">Selamanya</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            <form action="{{ route('menu.promos.update', $promo->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="toggle_active" value="1">
                                <button type="submit" class="px-3 py-1 rounded-full text-xs font-semibold {{ $promo->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }} transition-colors">
                                    {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('menu.promos.edit', $promo->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form id="delete-form-{{ $promo->id }}" action="{{ route('menu.promos.destroy', $promo->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="$dispatch('confirm', {
                                        title: 'Hapus Promo',
                                        message: 'Apakah Anda yakin ingin menghapus kupon {{ $promo->code }}? Tindakan ini tidak dapat dibatalkan.',
                                        confirmText: 'Hapus',
                                        cancelText: 'Batal',
                                        variant: 'danger',
                                        onConfirm: () => document.getElementById('delete-form-{{ $promo->id }}').submit()
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
                            Belum ada promo yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
