<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tenant->name }} - MUI UMKM</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 selection:bg-green-500 selection:text-white pb-20">

    <!-- Header Banner -->
    <div class="h-48 md:h-64 bg-green-600 bg-cover bg-center relative">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-black/10"></div>
        
        <a href="{{ route('home') }}" class="absolute top-6 left-6 text-white hover:text-green-300 transition-colors flex items-center gap-2 font-medium z-10 bg-black/20 backdrop-blur-sm px-4 py-2 rounded-xl border border-white/10 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Direktori
        </a>
    </div>

    <!-- Profil UMKM -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-10">
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-6 md:p-10 border border-gray-100 flex flex-col md:flex-row gap-8 items-start md:items-center">
            
            <div class="flex-shrink-0">
                @if($tenant->logo_path)
                    <img src="{{ Storage::url($tenant->logo_path) }}" alt="{{ $tenant->name }}" class="w-32 h-32 md:w-40 md:h-40 rounded-3xl border-4 border-white shadow-lg object-cover bg-white">
                @else
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-3xl border-4 border-white shadow-lg bg-green-50 flex items-center justify-center text-green-600 text-5xl font-extrabold uppercase">
                        {{ substr($tenant->name, 0, 1) }}
                    </div>
                @endif
            </div>

            <div class="flex-1 w-full">
                <div class="flex flex-col md:flex-row md:items-center gap-4 justify-between mb-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">{{ $tenant->name }}</h1>
                        <span class="inline-flex px-3 py-1 mt-2 bg-green-100 text-green-800 rounded-lg text-xs font-bold uppercase tracking-wider">{{ $tenant->type }}</span>
                    </div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->phone ?? '0') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold shadow-md transition-colors whitespace-nowrap w-full md:w-auto">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                </div>

                <p class="text-gray-600 text-base leading-relaxed mb-6">{{ $tenant->description ?? 'UMKM ini belum memiliki deskripsi profil.' }}</p>

                @if($tenant->halal_certificate_number)
                <div class="mb-6 inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 border border-green-200 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3zm-2 16l-4-4 1.41-1.41L10 15.17l6.59-6.59L18 10l-8 8z"/></svg>
                    <div>
                        <p class="text-xs font-bold text-green-800 uppercase">Sertifikasi Halal MUI</p>
                        <p class="text-[10px] text-green-600 font-medium">{{ $tenant->halal_certificate_number }}</p>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 border-t border-gray-100 pt-6">
                    <div class="flex items-center gap-3 text-gray-600">
                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Alamat Lengkap</p>
                            <p class="text-xs font-medium">{{ $tenant->address ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 text-gray-600">
                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Nomor Kontak</p>
                            <p class="text-xs font-medium">{{ $tenant->phone ?? '-' }}</p>
                        </div>
                    </div>

                    @if($tenant->instagram_handle)
                    <div class="flex items-center gap-3 text-gray-600">
                        <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center text-pink-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Instagram</p>
                            <a href="https://instagram.com/{{ $tenant->instagram_handle }}" target="_blank" class="text-xs font-medium text-pink-600 hover:underline">{{ '@'.$tenant->instagram_handle }}</a>
                        </div>
                    </div>
                    @endif

                    @if($tenant->website_url)
                    <div class="flex items-center gap-3 text-gray-600">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Website</p>
                            <a href="{{ $tenant->website_url }}" target="_blank" class="text-xs font-medium text-blue-600 hover:underline">Kunjungi Web</a>
                        </div>
                    </div>
                    @endif
                </div>

                @if($tenant->operating_hours && is_array($tenant->operating_hours) && count($tenant->operating_hours) > 0)
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">Jam Operasional</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day)
                            @if(isset($tenant->operating_hours[$day]))
                                @php
                                    $dayData = $tenant->operating_hours[$day];
                                    $isOpen = isset($dayData['is_open']) && $dayData['is_open'] == '1';
                                @endphp
                                <div class="px-3 py-1.5 rounded-lg border text-[11px] {{ $isOpen ? 'border-green-200 bg-green-50 text-green-800' : 'border-gray-200 bg-gray-50 text-gray-500' }}">
                                    <span class="font-bold capitalize">{{ $day }}</span>: 
                                    @if($isOpen)
                                        {{ $dayData['open'] ?? '09:00' }} - {{ $dayData['close'] ?? '21:00' }}
                                    @else
                                        Libur
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Katalog Produk / Layanan -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b border-gray-200 pb-4">Katalog Produk & Layanan</h2>

        @if($categories->isEmpty() || $categories->sum(fn($c) => $c->products->count()) === 0)
            <div class="bg-white p-12 rounded-3xl text-center border border-gray-100 shadow-sm">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Katalog Masih Kosong</h3>
                <p class="text-gray-500">UMKM ini belum menambahkan produk atau layanan publik.</p>
            </div>
        @else
            <div class="space-y-12">
                @foreach($categories as $category)
                    @if($category->products->count() > 0)
                    <div>
                        <div class="flex items-center gap-4 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 bg-gray-100 px-4 py-2 rounded-xl">{{ $category->name }}</h3>
                            <div class="h-px bg-gray-200 flex-1"></div>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                            @foreach($category->products as $product)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-300 group flex flex-col h-full">
                                <div class="aspect-square bg-gray-50 relative overflow-hidden">
                                    @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                    @if($product->type === 'service')
                                        <div class="absolute top-2 right-2 bg-blue-500/90 backdrop-blur text-white text-[10px] font-bold px-2 py-1 rounded-md uppercase tracking-wide">Jasa / Layanan</div>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col flex-1 justify-between">
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 mb-1 leading-tight group-hover:text-green-600 transition-colors">{{ $product->name }}</h4>
                                        @if($product->description)
                                            <p class="text-xs text-gray-500 line-clamp-2 mb-3 leading-relaxed">{{ $product->description }}</p>
                                        @endif
                                    </div>
                                    <p class="text-green-600 font-extrabold mt-2 tracking-tight">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
