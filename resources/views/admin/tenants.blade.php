<x-app-layout>
    @section('page-title', 'Verifikasi Mitra UMKM')
    @section('breadcrumb')
        <a href="{{ route('dashboard') }}" class="hover:text-green-600">Dashboard</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Verifikasi UMKM</span>
    @endsection

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Manajemen & Verifikasi UMKM</h2>
        <p class="text-sm text-gray-500 mt-1">Setujui pendaftaran baru atau kelola status mitra yang sudah ada.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div x-data="{ tab: 'pending' }" class="space-y-6">
        <!-- Tabs -->
        <div class="flex gap-4 border-b border-gray-200">
            <button @click="tab = 'pending'" :class="{'border-b-2 border-green-600 text-green-600 font-bold': tab === 'pending', 'text-gray-500 hover:text-gray-700': tab !== 'pending'}" class="pb-3 px-2 transition-colors relative">
                Menunggu Persetujuan
                @if($pendingTenants->count() > 0)
                    <span class="absolute top-0 -right-4 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingTenants->count() }}</span>
                @endif
            </button>
            <button @click="tab = 'active'" :class="{'border-b-2 border-green-600 text-green-600 font-bold': tab === 'active', 'text-gray-500 hover:text-gray-700': tab !== 'active'}" class="pb-3 px-2 transition-colors">
                Mitra Aktif
            </button>
        </div>

        <!-- Tab Pending -->
        <div x-show="tab === 'pending'" x-transition.opacity>
            @if($pendingTenants->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Tidak Ada Antrean</h3>
                    <p class="text-gray-500 mt-1">Belum ada UMKM baru yang mendaftar saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($pendingTenants as $tenant)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative overflow-hidden flex flex-col">
                        <div class="absolute top-0 right-0 bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">Pending</div>
                        
                        <div class="flex items-start justify-between mb-4 mt-2">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $tenant->name }}</h3>
                                <p class="text-sm font-medium text-gray-500 uppercase">{{ $tenant->type }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 mb-6 flex-1 text-sm text-gray-600">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $tenant->address ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>{{ $tenant->phone ?? '-' }}</span>
                            </div>
                            @if($tenant->halal_certificate_number)
                            <div class="flex items-center gap-2 mt-2 px-3 py-1.5 bg-green-50 border border-green-200 rounded-lg">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3zm-2 16l-4-4 1.41-1.41L10 15.17l6.59-6.59L18 10l-8 8z"/></svg>
                                <div>
                                    <p class="text-[10px] font-bold text-green-800 uppercase tracking-wider leading-none">Klaim Sertifikat Halal MUI</p>
                                    <p class="text-xs text-green-700 font-medium mt-0.5">{{ $tenant->halal_certificate_number }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-100 mt-auto">
                            <form action="{{ route('admin.tenants.approve', $tenant->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors">Terima & Aktifkan</button>
                            </form>
                            <form action="{{ route('admin.tenants.suspend', $tenant->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 border border-red-200 text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Tolak">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Tab Active -->
        <div x-show="tab === 'active'" x-transition.opacity style="display: none;">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                                <th class="py-4 px-6">Nama UMKM</th>
                                <th class="py-4 px-6">Kontak</th>
                                <th class="py-4 px-6">Komisi Platform</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($activeTenants as $tenant)
                            <tr class="hover:bg-gray-50 transition-colors" x-data="{ showCommission: false }">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">{{ $tenant->name }}</p>
                                    <p class="text-xs text-gray-500 uppercase mt-0.5">{{ $tenant->type }}</p>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-600">
                                    {{ $tenant->phone }}
                                </td>
                                <td class="py-4 px-6">
                                    @if(($tenant->platform_fee_type ?? 'percentage') === 'fixed')
                                        <span class="inline-flex px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold">
                                            Rp {{ number_format($tenant->platform_fee_fixed ?? 0, 0, ',', '.') }} / trx
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 bg-green-50 text-green-700 rounded-lg text-xs font-bold">
                                            {{ number_format($tenant->mdr_fee_percentage ?? 0, 1) }}%
                                        </span>
                                    @endif
                                    <button @click="showCommission = !showCommission" class="ml-1 text-gray-400 hover:text-green-600 transition-colors" title="Edit Komisi">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>

                                    <!-- Inline Commission Editor -->
                                    <div x-show="showCommission" x-transition class="mt-3">
                                        <form action="{{ route('admin.tenants.commission.update', $tenant->id) }}" method="POST" class="p-3 bg-gray-50 border border-gray-200 rounded-xl space-y-3" x-data="{ feeType: '{{ $tenant->platform_fee_type ?? 'percentage' }}' }">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label class="block text-xs font-bold text-gray-600 mb-1">Tipe Komisi</label>
                                                <select name="platform_fee_type" x-model="feeType" class="w-full border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-lg text-sm bg-white">
                                                    <option value="percentage">Persentase (%)</option>
                                                    <option value="fixed">Fixed per Transaksi (Rp)</option>
                                                </select>
                                            </div>
                                            <div x-show="feeType === 'percentage'">
                                                <label class="block text-xs font-bold text-gray-600 mb-1">Persentase (%)</label>
                                                <input type="number" name="mdr_fee_percentage" value="{{ $tenant->mdr_fee_percentage ?? 0 }}" step="0.01" min="0" max="100" class="w-full border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-lg text-sm bg-white" placeholder="Contoh: 2.5">
                                            </div>
                                            <div x-show="feeType === 'fixed'">
                                                <label class="block text-xs font-bold text-gray-600 mb-1">Nominal per Transaksi (Rp)</label>
                                                <input type="number" name="platform_fee_fixed" value="{{ $tenant->platform_fee_fixed ?? 0 }}" step="100" min="0" class="w-full border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-lg text-sm bg-white" placeholder="Contoh: 5000">
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit" class="flex-1 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-colors">Simpan</button>
                                                <button type="button" @click="showCommission = false" class="px-3 py-1.5 border border-gray-200 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-100 transition-colors">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Aktif</span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <form action="{{ route('admin.tenants.suspend', $tenant->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin membekukan (suspend) UMKM ini?')">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Suspend</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">
                                    Tidak ada UMKM aktif saat ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $activeTenants->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
