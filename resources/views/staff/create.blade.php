<x-app-layout>
    @section('page-title', 'Tambah Akun Kasir')
    @section('breadcrumb')
        <a href="{{ route('dashboard') }}" class="hover:text-green-600">Dashboard</a>
        <span class="mx-2">&rsaquo;</span>
        <a href="{{ route('menu.staff.index') }}" class="hover:text-green-600">Manajemen Kasir</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Tambah Kasir</span>
    @endsection

    <div class="max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Tambah Akun Kasir Baru</h2>
                <p class="text-sm text-gray-500 mt-1">Akun ini nantinya digunakan oleh pegawai Anda untuk masuk ke Mesin POS.</p>
            </div>
            <a href="{{ route('menu.staff.index') }}" class="text-sm text-gray-500 hover:text-gray-900 border border-gray-200 bg-white px-4 py-2 rounded-xl shadow-sm transition-colors">Batal</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <form method="POST" action="{{ route('menu.staff.store') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="name" value="Nama Lengkap Pegawai" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email (Digunakan untuk Login Kasir)" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6 mt-6">
                    <div>
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <x-primary-button class="px-8 py-3 rounded-xl bg-green-600 hover:bg-green-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Simpan Akun Kasir
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
