<x-app-layout>
    @section('page-title', 'Pengaturan Toko')
    @section('breadcrumb')
        <a href="{{ route('dashboard') }}" class="hover:text-green-600">Dashboard</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">Pengaturan Toko</span>
    @endsection

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Profil & Pengaturan Toko</h2>
        <p class="text-sm text-gray-500 mt-1">Lengkapi informasi toko Anda agar lebih menarik di Direktori Publik MUI.</p>
    </div>

    @if (session('status') === 'shop-updated')
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium">Profil toko berhasil diperbarui.</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form method="POST" action="{{ route('settings.shop.update') }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8" x-data="imageUploader()">
            @csrf
            @method('patch')

            <!-- Bagian Logo Toko -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Logo Bisnis</h3>
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50 overflow-hidden relative group">
                            <!-- Preview Image Alpine -->
                            <img x-show="previewUrl" :src="previewUrl" class="w-full h-full object-cover absolute inset-0 z-10">
                            
                            <!-- Current Image -->
                            @if($tenant->logo_path)
                            <img x-show="!previewUrl" src="{{ Storage::url($tenant->logo_path) }}" class="w-full h-full object-cover absolute inset-0 z-0">
                            @endif

                            <div x-show="!previewUrl && !'{{ $tenant->logo_path }}'" class="text-gray-400 flex flex-col items-center">
                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[10px] font-medium uppercase tracking-wider">Upload</span>
                            </div>

                            <!-- Overlay Hover -->
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20 cursor-pointer" @click="$refs.fileInput.click()">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input type="file" name="logo" x-ref="fileInput" @change="fileChosen" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                        <button type="button" @click="$refs.fileInput.click()" class="px-4 py-2 bg-white border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-green-500/20 transition-colors">
                            Pilih Foto Baru
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Maksimal ukuran file: 2MB.<br>Format: JPG, PNG, atau WEBP.</p>
                        @error('logo') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Bagian Informasi Dasar -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Toko/Usaha *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $tenant->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                        @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori Usaha *</label>
                        <select id="type" name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors appearance-none">
                            <option value="retail" {{ old('type', $tenant->type) === 'retail' ? 'selected' : '' }}>Retail / Toko Barang</option>
                            <option value="fnb" {{ old('type', $tenant->type) === 'fnb' ? 'selected' : '' }}>Makanan & Minuman (F&B)</option>
                            <option value="service" {{ old('type', $tenant->type) === 'service' ? 'selected' : '' }}>Jasa / Layanan</option>
                            <option value="other" {{ old('type', $tenant->type) === 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Lengkap Toko</label>
                        <p class="text-xs text-gray-500 mb-2">Jelaskan tentang usaha Anda, keunggulan, jam buka, dll. Ini akan tampil di Katalog Publik.</p>
                        <textarea id="description" name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors resize-y">{{ old('description', $tenant->description) }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Bagian Kontak -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Kontak & Lokasi</h3>
                <div class="space-y-5">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">No. WhatsApp Bisnis *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $tenant->phone) }}" required class="w-full pl-11 pr-4 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors" placeholder="08123456789">
                        </div>
                        @error('phone') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap Toko</label>
                        <textarea id="address" name="address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors resize-none">{{ old('address', $tenant->address) }}</textarea>
                        @error('address') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Sertifikasi Halal MUI -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                    Sertifikasi Halal MUI
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-full uppercase tracking-wider">Premium</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="halal_certificate_number" class="block text-sm font-medium text-gray-700 mb-1.5">No. Sertifikat Halal MUI</label>
                        <input type="text" id="halal_certificate_number" name="halal_certificate_number" value="{{ old('halal_certificate_number', $tenant->halal_certificate_number) }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors" placeholder="Contoh: ID1234567890123">
                        <p class="text-[11px] text-gray-500 mt-1">Jika diisi, badge Halal akan muncul di struk belanja.</p>
                        @error('halal_certificate_number') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="halal_certificate_expires_at" class="block text-sm font-medium text-gray-700 mb-1.5">Masa Berlaku Sertifikat</label>
                        <input type="date" id="halal_certificate_expires_at" name="halal_certificate_expires_at" value="{{ old('halal_certificate_expires_at', $tenant->halal_certificate_expires_at ? $tenant->halal_certificate_expires_at->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors">
                        @error('halal_certificate_expires_at') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Struk Belanja -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Teks Struk Belanja (Receipt)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="receipt_header" class="block text-sm font-medium text-gray-700 mb-1.5">Header (Teks Atas Struk)</label>
                        <textarea id="receipt_header" name="receipt_header" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors resize-none" placeholder="Contoh: Pesanan Anda sangat berarti bagi kami!">{{ old('receipt_header', $tenant->receipt_header) }}</textarea>
                        @error('receipt_header') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="receipt_footer" class="block text-sm font-medium text-gray-700 mb-1.5">Footer (Teks Bawah Struk)</label>
                        <textarea id="receipt_footer" name="receipt_footer" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors resize-none" placeholder="Contoh: Terima kasih! Barang yang sudah dibeli tidak dapat ditukar.">{{ old('receipt_footer', $tenant->receipt_footer) }}</textarea>
                        @error('receipt_footer') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Jam Operasional & Kontak Tambahan -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Jam Operasional & Sosial Media</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="instagram_handle" class="block text-sm font-medium text-gray-700 mb-1.5">Akun Instagram</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">@</span>
                            <input type="text" id="instagram_handle" name="instagram_handle" value="{{ old('instagram_handle', $tenant->instagram_handle) }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors" placeholder="kopisenja_official">
                        </div>
                        @error('instagram_handle') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="website_url" class="block text-sm font-medium text-gray-700 mb-1.5">Website / Linktree</label>
                        <input type="url" id="website_url" name="website_url" value="{{ old('website_url', $tenant->website_url) }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-colors" placeholder="https://kopisenja.com">
                        @error('website_url') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hari & Jam Buka Toko</label>
                        @php
                            $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                            $operating_hours = old('operating_hours', is_array($tenant->operating_hours) ? $tenant->operating_hours : []);
                        @endphp
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($days as $day)
                                @php
                                    $isOpen = isset($operating_hours[$day]['is_open']) && $operating_hours[$day]['is_open'] == '1';
                                    $openTime = $operating_hours[$day]['open'] ?? '09:00';
                                    $closeTime = $operating_hours[$day]['close'] ?? '21:00';
                                @endphp
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-xl bg-white" x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="day_{{ $day }}" name="operating_hours[{{ $day }}][is_open]" value="1" x-model="open" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                        <label for="day_{{ $day }}" class="text-sm font-medium text-gray-700 capitalize w-16">{{ $day }}</label>
                                    </div>
                                    <div class="flex items-center gap-1" x-show="open">
                                        <input type="time" name="operating_hours[{{ $day }}][open]" value="{{ $openTime }}" class="text-xs py-1 px-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                        <span class="text-gray-400 text-xs">-</span>
                                        <input type="time" name="operating_hours[{{ $day }}][close]" value="{{ $closeTime }}" class="text-xs py-1 px-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                    </div>
                                    <div x-show="!open" class="text-xs font-semibold text-red-500 bg-red-50 px-2 py-1 rounded">Libur</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors focus:ring-4 focus:ring-green-500/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function imageUploader() {
            return {
                previewUrl: null,
                fileChosen(event) {
                    const file = event.target.files[0];
                    if (!file) {
                        this.previewUrl = null;
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = e => {
                        this.previewUrl = e.target.result;
                    };
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
