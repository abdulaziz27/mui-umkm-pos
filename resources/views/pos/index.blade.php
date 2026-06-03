<x-app-layout>
    @section('page-title', 'Mesin Kasir (POS)')
    
    <div class="h-[calc(100vh-4rem)] flex flex-col lg:flex-row gap-6 -m-4 sm:-m-6 p-4 sm:p-6 bg-gray-50" x-data="posEngine()">
        <!-- Kiri: Area Produk -->
        <div class="flex-1 flex flex-col overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Filter & Search -->
            <div class="p-4 border-b border-gray-100 flex items-center justify-between gap-4">
                <div class="relative flex-1 max-w-md">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="searchQuery" class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 bg-gray-50" placeholder="Cari produk...">
                </div>
                <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar">
                    <button @click="activeCategory = 'all'" :class="{'bg-green-600 text-white': activeCategory === 'all', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== 'all'}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors">
                        Semua
                    </button>
                    @foreach($categories as $category)
                    <button @click="activeCategory = '{{ $category->id }}'" :class="{'bg-green-600 text-white': activeCategory === '{{ $category->id }}', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== '{{ $category->id }}'}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Grid Produk -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <button @click="addToCart(product)" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:border-green-500 hover:shadow-md transition-all text-left flex flex-col h-full group relative">
                            <!-- Label Stok Habis -->
                            <div x-show="product.type === 'physical' && product.stock_quantity <= 0" class="absolute inset-0 bg-white/70 backdrop-blur-sm z-10 flex items-center justify-center">
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border border-red-200 shadow-sm transform -rotate-12">Stok Habis</span>
                            </div>
                            
                            <!-- Label Stok Menipis (<= 5) -->
                            <div x-show="product.type === 'physical' && product.stock_quantity > 0 && product.stock_quantity <= 5" class="absolute top-2 right-2 z-10">
                                <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-md text-[10px] font-bold border border-amber-200 shadow-sm flex items-center gap-1 animate-pulse">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Sisa <span x-text="product.stock_quantity"></span>
                                </span>
                            </div>
                            
                            <div class="aspect-square bg-gray-100 w-full relative">
                                <template x-if="product.image_url">
                                    <img :src="product.image_url" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </template>
                                <template x-if="!product.image_url">
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                </template>
                            </div>
                            <div class="p-3 flex-1 flex flex-col justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 leading-tight mb-1" x-text="product.name"></p>
                                    <p class="text-xs text-gray-500" x-show="product.type === 'physical'">Stok: <span x-text="product.stock_quantity"></span></p>
                                    <p class="text-xs text-blue-500" x-show="product.type === 'service'">Jasa/Layanan</p>
                                </div>
                                <p class="text-green-600 font-bold mt-2" x-text="formatRupiah(product.price)"></p>
                            </div>
                        </button>
                    </template>
                </div>
                
                <div x-show="filteredProducts.length === 0" class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <p class="text-gray-500">Tidak ada produk yang cocok dengan pencarian.</p>
                </div>
            </div>
        </div>

        <!-- Kanan: Keranjang / Checkout -->
        <div class="w-full lg:w-96 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex-shrink-0">
            <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Keranjang
                </h3>
                <div class="flex items-center gap-2">
                    <span class="bg-green-100 text-green-700 py-1 px-2 rounded-full text-xs font-semibold" x-text="totalItems() + ' item'"></span>
                    <button @click="connectPrinter()" :class="printerConnected ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200'" class="px-2 py-1 rounded-lg text-xs font-bold transition-all border flex items-center gap-1" :title="printerConnected ? 'Printer Bluetooth Terhubung' : 'Hubungkan Printer Bluetooth'">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 6.5l11 11L12 23V1l5.5 5.5-11 11"/></svg>
                    </button>
                    <a href="{{ route('pos.shift.close.show') }}" class="px-3 py-1 bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg text-xs font-bold transition-colors border border-gray-200 hover:border-red-200">
                        Tutup Shift
                    </a>
                </div>
            </div>

            <!-- List Item di Keranjang -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <template x-for="item in cart" :key="item.id">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate" x-text="item.name"></p>
                            <p class="text-xs text-green-600 font-medium" x-text="formatRupiah(item.price)"></p>
                        </div>
                        <div class="flex items-center gap-2 bg-white rounded-lg border border-gray-200 p-1">
                            <button @click="decreaseQuantity(item.id)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-md transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                            <span class="text-sm font-medium w-6 text-center" x-text="item.quantity"></span>
                            <button @click="increaseQuantity(item.id)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-md transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="cart.length === 0" class="text-center py-10 flex flex-col items-center justify-center h-full">
                    <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <p class="text-gray-500 font-medium">Keranjang masih kosong</p>
                    <p class="text-xs text-gray-400 mt-1">Pilih produk di sebelah kiri</p>
                </div>
            </div>

            <!-- Rincian Biaya -->
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                <!-- Promo Code ONLY Engine -->
                <div class="mb-4 p-3 bg-white border border-gray-200 rounded-xl relative">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Kupon Promo</label>
                    <div class="flex gap-2">
                        <input x-model="promoInput" :disabled="promoApplied" type="text" placeholder="Ketik kode kupon..." class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm bg-gray-50 uppercase focus:bg-white focus:ring-green-500 focus:border-green-500 disabled:opacity-50">
                        <button x-show="!promoApplied" @click="applyPromoCode()" type="button" class="px-4 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 transition-colors shrink-0">Terapkan</button>
                        <button x-show="promoApplied" @click="resetPromo()" type="button" class="px-4 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition-colors shrink-0">Hapus</button>
                    </div>
                    
                    <p x-show="promoMessage" :class="promoApplied ? 'text-green-600' : 'text-red-500'" class="text-xs font-medium mt-2" x-text="promoMessage"></p>

                    <!-- Available Promos (Click to apply) -->
                    <template x-if="!promoApplied">
                        <div class="mt-3">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Promo Tersedia (Klik untuk Pakai)</span>
                            <div class="flex flex-wrap gap-2">
                                @forelse($promos as $promo)
                                    <button type="button" @click="promoInput = '{{ $promo->code }}'; applyPromoCode()" class="px-2 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded border border-green-200 hover:bg-green-100 transition-colors">
                                        {{ $promo->code }} ({{ $promo->type === 'percentage' ? floatval($promo->value) . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.') }})
                                    </button>
                                @empty
                                    <span class="text-[10px] text-gray-500">Tidak ada promo aktif.</span>
                                @endforelse
                            </div>
                        </div>
                    </template>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium text-gray-900" x-text="formatRupiah(subtotal())"></span>
                    </div>
                    <div x-show="discountAmount() > 0" class="flex justify-between text-sm">
                        <span class="text-gray-500">Diskon</span>
                        <span class="font-medium text-green-600">- <span x-text="formatRupiah(discountAmount())"></span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 flex items-center gap-1" x-show="feeType === 'percentage'">
                            Platform Fee MUI (<span x-text="mdrPercentage"></span>%)
                        </span>
                        <span class="text-gray-500 flex items-center gap-1" x-show="feeType === 'fixed'" style="display: none;">
                            Platform Fee MUI (Fixed)
                        </span>
                        <span class="font-medium text-red-500">
                            - <span x-text="formatRupiah(platformFee())"></span>
                        </span>
                    </div>
                    <div class="pt-2 mt-2 border-t border-gray-200 flex justify-between">
                        <span class="text-base font-bold text-gray-900">Total Tagihan</span>
                        <span class="text-xl font-bold text-green-600" x-text="formatRupiah(totalAmount())"></span>
                    </div>
                    <div class="flex justify-between text-xs pt-1">
                        <span class="text-gray-500">Pendapatan Bersih UMKM</span>
                        <span class="font-bold text-gray-700" x-text="formatRupiah(netIncome())"></span>
                    </div>
                </div>

                <!-- Form Checkout -->
                <form @submit.prevent="processPaymentFlow">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" x-model="paymentMethod" value="cash" class="peer sr-only">
                                <div class="text-center py-2 px-3 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition-all">Tunai</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" x-model="paymentMethod" value="qris" class="peer sr-only">
                                <div class="text-center py-2 px-3 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition-all flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                    QRIS
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" x-model="paymentMethod" value="transfer" class="peer sr-only">
                                <div class="text-center py-2 px-3 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition-all">Transfer</div>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" :disabled="cart.length === 0 || isProcessing" class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white text-base font-bold rounded-xl shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <span x-show="!isProcessing">Bayar Tagihan</span>
                        <span x-show="isProcessing">Memproses...</span>
                        <svg x-show="!isProcessing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        <svg x-show="isProcessing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- MODAL: QRIS Simulator -->
        <div x-show="showQrisModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm" style="display: none;">
            <div @click.away="showQrisModal = false" class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full mx-4 transform transition-all text-center">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        Simulasi QRIS
                    </h3>
                    <button @click="showQrisModal = false" class="text-gray-400 hover:bg-gray-100 rounded-full p-1 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <p class="text-sm text-gray-500 mb-2">Total Tagihan:</p>
                <p class="text-3xl font-extrabold text-blue-600 mb-6" x-text="formatRupiah(totalAmount())"></p>
                
                <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-2xl p-4 inline-block mx-auto mb-6">
                    <!-- Dynamic QR via api.qrserver.com using amount -->
                    <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=QRIS_SIMULATOR_AMOUNT_${totalAmount()}`" alt="QRIS" class="w-48 h-48 mx-auto rounded-xl">
                </div>
                
                <p class="text-xs text-gray-400 mb-6">Arahkan kamera atau aplikasi e-wallet pelanggan untuk memindai kode QR di atas.</p>
                
                <button @click="executeCheckout()" :disabled="isProcessing" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition-colors flex items-center justify-center gap-2">
                    <span x-show="!isProcessing">Simulasi Pembayaran Berhasil</span>
                    <span x-show="isProcessing">Memproses...</span>
                    <svg x-show="!isProcessing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
            </div>
        </div>

        <!-- MODAL & PRINT AREA: Struk Belanja -->
        <div x-show="showReceiptModal" class="fixed inset-0 z-[70] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-sm w-full mx-4 flex flex-col max-h-[90vh]">
                
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 flex justify-between items-center hide-on-print">
                    <h3 class="font-bold text-gray-900">Transaksi Berhasil!</h3>
                    <button @click="closeReceiptModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Printable Receipt Content -->
                <div id="printable-receipt" class="p-6 overflow-y-auto font-mono text-sm text-black flex-1 bg-white">
                    <div class="text-center mb-6">
                        @if($tenant->logo_path)
                        <div class="mx-auto w-16 h-16 mb-3 flex items-center justify-center overflow-hidden rounded-full border border-black bg-white">
                            <img src="{{ Storage::url($tenant->logo_path) }}" alt="{{ $tenant->name }}" class="w-full h-full object-cover filter grayscale contrast-125">
                        </div>
                        @else
                        <div class="mx-auto w-16 h-16 border border-black rounded-full flex items-center justify-center mb-3 bg-white">
                            <span class="text-lg font-bold text-black">{{ substr($tenant->name, 0, 1) }}</span>
                        </div>
                        @endif
                        <h2 class="text-xl font-bold uppercase tracking-wide">{{ $tenant->name }}</h2>
                        @if($tenant->halal_certificate_number)
                        <div class="mt-1 flex flex-col items-center">
                            <span class="text-[9px] font-bold border border-black px-1.5 py-0.5 leading-none">✓ TERVERIFIKASI HALAL</span>
                            <span class="text-[8px] text-gray-700 mt-0.5">No: {{ $tenant->halal_certificate_number }}</span>
                        </div>
                        @endif
                        <p class="text-xs mt-2">{{ $tenant->address }}</p>
                        <p class="text-xs">Telp: {{ $tenant->phone }}</p>
                        @if($tenant->receipt_header)
                        <p class="text-xs mt-3 italic border-t border-dashed border-gray-400 pt-2">{{ $tenant->receipt_header }}</p>
                        @endif
                    </div>

                    <div class="border-b border-dashed border-gray-400 pb-3 mb-3 text-xs">
                        <div class="flex justify-between">
                            <span>Waktu</span>
                            <span x-text="receiptData.time"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kasir</span>
                            <span>{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>No. Resi</span>
                            <span x-text="receiptData.receipt_number"></span>
                        </div>
                        <div class="flex justify-between mt-1 pt-1 border-t border-dashed border-gray-200">
                            <span>Metode</span>
                            <span class="uppercase font-bold" x-text="receiptData.payment_method"></span>
                        </div>
                    </div>

                    <div class="space-y-2 mb-3 border-b border-dashed border-gray-400 pb-3 text-xs">
                        <template x-for="item in receiptData.items" :key="item.id">
                            <div class="flex justify-between items-start">
                                <div class="w-2/3">
                                    <div class="font-bold truncate" x-text="item.product_name"></div>
                                    <div class="text-[10px]" x-text="`${item.quantity} x ${formatRupiah(item.price)}`"></div>
                                </div>
                                <div class="w-1/3 text-right font-medium" x-text="formatRupiah(item.subtotal)"></div>
                            </div>
                        </template>
                    </div>

                    <div class="space-y-1 text-xs font-medium border-b border-dashed border-gray-400 pb-3 mb-3">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span x-text="formatRupiah(receiptData.subtotal)"></span>
                        </div>
                        <div class="flex justify-between" x-show="receiptData.discount_amount > 0">
                            <span>Diskon</span>
                            <span x-text="'- ' + formatRupiah(receiptData.discount_amount)"></span>
                        </div>
                        <div class="flex justify-between text-sm font-bold mt-2 pt-2 border-t border-dashed border-gray-300">
                            <span>TOTAL TAGIHAN</span>
                            <span x-text="formatRupiah(receiptData.total_amount)"></span>
                        </div>
                    </div>

                    <div class="text-center text-xs text-gray-600">
                        @if($tenant->receipt_footer)
                        <p class="mb-2 italic">{{ $tenant->receipt_footer }}</p>
                        @endif
                        <p>Terima Kasih Atas Kunjungan Anda</p>
                        <p class="mt-2 text-[10px]">Powered by MUI UMKM POS</p>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex gap-3 hide-on-print">
                    <button @click="printReceipt()" class="flex-1 py-3 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-xl shadow-md transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak Struk
                    </button>
                    <button @click="closeReceiptModal()" class="py-3 px-6 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-xl transition-colors">
                        Selesai
                    </button>
                </div>
            </div>
        </div>

        <!-- Alpine Toast Notification -->
        <div x-show="showToast" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl border hide-on-print"
             :class="toastType === 'error' ? 'bg-red-50 border-red-100 text-red-700' : (toastType === 'success' ? 'bg-green-50 border-green-100 text-green-700' : 'bg-amber-50 border-amber-100 text-amber-700')"
             style="display: none;">
            <svg x-show="toastType === 'error'" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <svg x-show="toastType === 'warning'" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <svg x-show="toastType === 'success'" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-sm font-bold tracking-wide" x-text="toastMessage"></span>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-receipt, #printable-receipt * {
                visibility: visible;
            }
            #printable-receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 58mm; /* Ukuran standard thermal printer mini */
                margin: 0;
                padding: 10px;
                background-color: white !important;
                color: black !important;
            }
            .hide-on-print {
                display: none !important;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function posEngine() {
            return {
                products: @json($products),
                cart: [],
                searchQuery: '',
                activeCategory: 'all',
                feeType: '{{ $tenant->platform_fee_type ?? "percentage" }}',
                feeFixed: {{ $tenant->platform_fee_fixed ?? 0 }},
                mdrPercentage: {{ $tenant->mdr_fee_percentage ?? 0 }},
                discountType: 'none',
                discountValue: 0,
                promoInput: '',
                promoApplied: false,
                promoMessage: '',
                paymentMethod: 'cash',
                
                // Checkout State
                isProcessing: false,
                
                // QRIS Modal
                showQrisModal: false,
                
                // Receipt Modal
                showReceiptModal: false,
                receiptData: {},
                
                // Toast State
                showToast: false,
                toastMessage: '',
                toastType: 'error',
                toastTimeout: null,
                
                // Bluetooth Printer State
                printerConnected: false,
                
                triggerToast(msg, type = 'error') {
                    this.toastMessage = msg;
                    this.toastType = type;
                    this.showToast = true;
                    if (this.toastTimeout) clearTimeout(this.toastTimeout);
                    this.toastTimeout = setTimeout(() => { this.showToast = false; }, 3000);
                },

                processPaymentFlow() {
                    if (this.cart.length === 0) return;
                    
                    if (this.paymentMethod === 'qris') {
                        this.showQrisModal = true;
                    } else {
                        this.executeCheckout();
                    }
                },
                
                async executeCheckout() {
                    this.isProcessing = true;
                    
                    try {
                        const response = await fetch('{{ route('pos.checkout') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                cart: JSON.stringify(this.cart),
                                payment_method: this.paymentMethod,
                                discount_type: this.discountType,
                                discount_value: this.discountValue
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            // Close QRIS Modal if open
                            this.showQrisModal = false;
                            
                            // Format receipt data
                            const trx = data.transaction;
                            const date = new Date(trx.created_at);
                            
                            this.receiptData = {
                                receipt_number: trx.receipt_number,
                                time: date.toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute:'2-digit' }),
                                payment_method: trx.payment_method,
                                items: trx.items,
                                subtotal: trx.subtotal,
                                discount_amount: trx.discount_amount,
                                total_amount: trx.total_amount
                            };
                            
                            // Show Receipt Modal
                            this.showReceiptModal = true;
                            this.triggerToast('Transaksi Berhasil!', 'success');
                            
                            // Auto-print to Bluetooth if connected
                            if (this.printerConnected) {
                                this.directPrintReceipt(this.receiptData);
                            }
                            
                            // Deduct stocks in memory so UI updates immediately
                            this.updateStockMemory();
                            
                        } else {
                            this.triggerToast(data.message || 'Gagal memproses transaksi', 'error');
                        }
                    } catch (error) {
                        this.triggerToast('Terjadi kesalahan jaringan.', 'error');
                    } finally {
                        this.isProcessing = false;
                    }
                },
                
                updateStockMemory() {
                    // Reduce stock in JS memory based on cart
                    this.cart.forEach(item => {
                        if (item.type === 'physical') {
                            const product = this.products.find(p => p.id === item.id);
                            if (product && product.stock_quantity !== null) {
                                product.stock_quantity -= item.quantity;
                            }
                        }
                    });
                },
                
                closeReceiptModal() {
                    this.showReceiptModal = false;
                    // Reset cart and checkout state for next transaction
                    this.cart = [];
                    this.resetPromo();
                    this.paymentMethod = 'cash';
                },
                
                printReceipt() {
                    if (this.printerConnected) {
                        this.directPrintReceipt(this.receiptData);
                    } else {
                        window.print();
                    }
                },
                
                // Web Bluetooth Thermal Printer Integration
                async connectPrinter() {
                    if (!navigator.bluetooth) {
                        alert('Browser Anda tidak mendukung Web Bluetooth API. Gunakan Chrome, Edge, atau Opera.');
                        return;
                    }

                    try {
                        const device = await navigator.bluetooth.requestDevice({
                            acceptAllDevices: true,
                            optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb', 'e7810a71-73ae-499d-8c15-faa9aef0c3f2', '0000fee7-0000-1000-8000-00805f9b34fb']
                        });
                        
                        const server = await device.gatt.connect();
                        const services = await server.getPrimaryServices();
                        
                        let characteristic = null;
                        for (const s of services) {
                            const characteristics = await s.getCharacteristics();
                            for (const c of characteristics) {
                                if (c.properties.write || c.properties.writeWithoutResponse) {
                                    characteristic = c;
                                    break;
                                }
                            }
                            if (characteristic) break;
                        }
                        
                        if (characteristic) {
                            window.printerCharacteristic = characteristic;
                            this.printerConnected = true;
                            
                            device.addEventListener('gattserverdisconnected', () => {
                                this.printerConnected = false;
                                window.printerCharacteristic = null;
                            });
                            
                            this.triggerToast('Printer Bluetooth terhubung!', 'success');
                        } else {
                            this.triggerToast('Port print tidak ditemukan pada device ini.', 'error');
                        }
                    } catch (error) {
                        console.error(error);
                        if(error.name !== 'NotFoundError') {
                            this.triggerToast('Gagal terhubung: ' + error.message, 'error');
                        }
                    }
                },

                async directPrintReceipt(data) {
                    if (!window.printerCharacteristic) {
                        this.triggerToast('Printer tidak terhubung.', 'error');
                        return;
                    }
                    
                    const escpos = {
                        buffer: [],
                        init() { this.buffer.push(0x1B, 0x40); return this; },
                        alignLeft() { this.buffer.push(0x1B, 0x61, 0x00); return this; },
                        alignCenter() { this.buffer.push(0x1B, 0x61, 0x01); return this; },
                        alignRight() { this.buffer.push(0x1B, 0x61, 0x02); return this; },
                        bold(on) { this.buffer.push(0x1B, 0x45, on ? 1 : 0); return this; },
                        text(str) { 
                            for(let i=0; i<str.length; i++) this.buffer.push(str.charCodeAt(i));
                            return this;
                        },
                        line(str) { this.text(str); this.buffer.push(0x0A); return this; },
                        newline(count = 1) { for(let i=0;i<count;i++) this.buffer.push(0x0A); return this; },
                        cut() { this.buffer.push(0x1D, 0x56, 0x41, 0x00); return this; },
                        separator() { this.line('-'.repeat(32)); return this; },
                        feed() { this.newline(4); return this; },
                        getBytes() { return new Uint8Array(this.buffer); }
                    };

                    const tenantName = "{{ $tenant->name }}";
                    const tenantHalal = "{{ $tenant->halal_certificate_number }}";

                    escpos.init()
                          .alignCenter()
                          .bold(true)
                          .line(tenantName)
                          .bold(false);
                          
                    if (tenantHalal) {
                        escpos.line('TERVERIFIKASI HALAL MUI')
                              .line('No: ' + tenantHalal);
                    }
                    
                    escpos.newline()
                          .alignLeft()
                          .line('Order ID : ' + data.receipt_number)
                          .line('Tanggal  : ' + data.time)
                          .separator();

                    data.items.forEach(item => {
                        escpos.alignLeft().line(item.name);
                        
                        let qtyPrice = `${item.quantity} x ${this.formatRupiah(item.price)}`;
                        let subtotal = this.formatRupiah(item.quantity * item.price);
                        
                        let spaces = 32 - qtyPrice.length - subtotal.length;
                        if (spaces < 1) spaces = 1;
                        
                        escpos.line(qtyPrice + ' '.repeat(spaces) + subtotal);
                    });
                    
                    escpos.separator()
                          .alignLeft();
                          
                    if (data.discount_amount > 0) {
                        let discStr = this.formatRupiah(data.discount_amount);
                        escpos.line('DISKON:   ' + ' '.repeat(32 - 10 - discStr.length) + '-' + discStr);
                    }
                          
                    let totalStr = this.formatRupiah(data.total_amount);
                    escpos.bold(true)
                          .line('TOTAL:    ' + ' '.repeat(32 - 10 - totalStr.length) + totalStr)
                          .bold(false)
                          .line('Metode:   ' + (data.payment_method === 'cash' ? 'Tunai' : 'QRIS / Non-Tunai'))
                          .separator()
                          .alignCenter()
                          .newline()
                          .line('Terima Kasih!')
                          .line('Powered by MUI UMKM POS')
                          .feed()
                          .cut();

                    const bytes = escpos.getBytes();
                    const CHUNK_SIZE = 100;
                    
                    try {
                        for (let i = 0; i < bytes.length; i += CHUNK_SIZE) {
                            const chunk = bytes.slice(i, i + CHUNK_SIZE);
                            await window.printerCharacteristic.writeValue(chunk);
                            await new Promise(r => setTimeout(r, 40)); 
                        }
                    } catch (err) {
                        console.error("Print error", err);
                        this.triggerToast("Koneksi print terputus: " + err.message, "error");
                    }
                },

                resetPromo() {
                    this.discountType = 'none';
                    this.discountValue = 0;
                    this.promoInput = '';
                    this.promoApplied = false;
                    this.promoMessage = '';
                },

                async applyPromoCode() {
                    if (!this.promoInput.trim()) return;
                    
                    try {
                        const response = await fetch('{{ route('pos.apply-promo') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ code: this.promoInput })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.discountType = data.promo.type;
                            this.discountValue = data.promo.value;
                            this.promoApplied = true;
                            
                            let valueText = data.promo.type === 'percentage' ? 
                                            data.promo.value + '%' : 
                                            this.formatRupiah(data.promo.value);
                                            
                            this.promoMessage = `Kupon Berhasil! Diskon ${valueText} diterapkan.`;
                        } else {
                            this.promoMessage = data.message;
                            this.promoApplied = false;
                        }
                    } catch (error) {
                        this.promoMessage = 'Terjadi kesalahan jaringan.';
                        this.promoApplied = false;
                    }
                },

                get filteredProducts() {
                    return this.products.filter(product => {
                        const matchesSearch = product.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                              (product.sku && product.sku.toLowerCase().includes(this.searchQuery.toLowerCase()));
                        const matchesCategory = this.activeCategory === 'all' || product.category_id === this.activeCategory;
                        return matchesSearch && matchesCategory;
                    });
                },

                addToCart(product) {
                    if (product.type === 'physical' && product.stock_quantity <= 0) {
                        this.triggerToast('Stok barang sudah habis!', 'error');
                        return;
                    }

                    const existingItemIndex = this.cart.findIndex(item => item.id === product.id);
                    
                    if (existingItemIndex !== -1) {
                        if (product.type === 'physical' && this.cart[existingItemIndex].quantity >= product.stock_quantity) {
                            this.triggerToast('Batas maksimal stok fisik tercapai!', 'warning');
                            return;
                        }
                        this.cart[existingItemIndex].quantity++;
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            quantity: 1,
                            type: product.type
                        });
                    }
                },

                increaseQuantity(productId) {
                    const itemIndex = this.cart.findIndex(item => item.id === productId);
                    if (itemIndex !== -1) {
                        const product = this.products.find(p => p.id === productId);
                        if (product.type === 'physical' && this.cart[itemIndex].quantity >= product.stock_quantity) {
                            this.triggerToast('Batas maksimal stok fisik tercapai!', 'warning');
                            return;
                        }
                        this.cart[itemIndex].quantity++;
                    }
                },

                decreaseQuantity(productId) {
                    const itemIndex = this.cart.findIndex(item => item.id === productId);
                    if (itemIndex !== -1) {
                        if (this.cart[itemIndex].quantity > 1) {
                            this.cart[itemIndex].quantity--;
                        } else {
                            this.cart.splice(itemIndex, 1);
                        }
                    }
                },

                totalItems() {
                    return this.cart.reduce((total, item) => total + item.quantity, 0);
                },

                subtotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                discountAmount() {
                    let amount = 0;
                    if (this.discountType === 'percentage') {
                        amount = (this.subtotal() * this.discountValue) / 100;
                    } else if (this.discountType === 'nominal') {
                        amount = Number(this.discountValue);
                    }
                    return Math.min(amount, this.subtotal());
                },

                totalAmount() {
                    return this.subtotal() - this.discountAmount();
                },

                platformFee() {
                    if (this.feeType === 'fixed') {
                        return this.feeFixed;
                    }
                    return (this.totalAmount() * this.mdrPercentage) / 100;
                },

                netIncome() {
                    return this.totalAmount() - this.platformFee();
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
