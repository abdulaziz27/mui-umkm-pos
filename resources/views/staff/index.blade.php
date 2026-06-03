<x-app-layout>
    @section('page-title', 'Manajemen Kasir')
    @section('breadcrumb')
        <a href="{{ route('dashboard') }}" class="hover:text-green-600">Dashboard</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Pegawai (Kasir)</span>
    @endsection

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Akun Pegawai (Kasir)</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola akun kasir yang dapat mengakses mesin POS toko Anda.</p>
        </div>
        <a href="{{ route('menu.staff.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm shadow-green-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kasir Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                        <th class="py-3 px-6">Nama Pegawai</th>
                        <th class="py-3 px-6">Email / Username</th>
                        <th class="py-3 px-6 text-center">Status Role</th>
                        <th class="py-3 px-6">Tanggal Dibuat</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($staffs as $staff)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-6 text-sm font-medium text-gray-900 flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($staff->name, 0, 1)) }}
                            </div>
                            {{ $staff->name }}
                        </td>
                        <td class="py-3 px-6 text-sm text-gray-600">{{ $staff->email }}</td>
                        <td class="py-3 px-6 text-center">
                            <span class="inline-flex px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold uppercase tracking-wide">KASIR POS</span>
                        </td>
                        <td class="py-3 px-6 text-sm text-gray-500">{{ $staff->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('menu.staff.edit', $staff->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form id="delete-form-{{ $staff->id }}" action="{{ route('menu.staff.destroy', $staff->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="$dispatch('confirm', {
                                        title: 'Hapus Akun Kasir',
                                        message: 'Apakah Anda yakin ingin menghapus akun {{ $staff->name }}? Ia tidak akan bisa lagi mengakses mesin POS.',
                                        confirmText: 'Hapus',
                                        cancelText: 'Batal',
                                        variant: 'danger',
                                        onConfirm: () => document.getElementById('delete-form-{{ $staff->id }}').submit()
                                    })" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Belum ada akun kasir yang didaftarkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($staffs->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $staffs->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
