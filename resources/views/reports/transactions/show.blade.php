<x-app-layout>
    @section('page-title', 'Detail Transaksi')
    @section('breadcrumb')
        <a href="{{ route('report.transactions') }}" class="hover:text-green-600">Laporan Transaksi</a>
        <span class="mx-2">&rsaquo;</span>
        <span class="text-gray-700">{{ $transaction->receipt_number }}</span>
    @endsection

    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Rincian Transaksi</h2>
            <a href="{{ route('report.transactions') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header Struk -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wide">No. Resi</p>
                    <p class="text-xl font-bold text-gray-900">{{ $transaction->receipt_number }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wide">Tanggal Transaksi</p>
                    <p class="text-gray-900 font-medium">{{ $transaction->created_at->format('d F Y, H:i') }} WIB</p>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="p-6 border-b border-gray-100 grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kasir</p>
                    <p class="font-medium text-gray-900">{{ $transaction->cashier->name ?? 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Metode Pembayaran</p>
                    <p class="font-medium text-gray-900 uppercase">{{ $transaction->payment_method }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status Pembayaran</p>
                    @if($transaction->payment_status === 'paid')
                        <span class="inline-flex px-2.5 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase tracking-wide">SUKSES</span>
                    @elseif($transaction->payment_status === 'failed')
                        <span class="inline-flex px-2.5 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold uppercase tracking-wide">VOID / BATAL</span>
                    @else
                        <span class="inline-flex px-2.5 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold uppercase tracking-wide">{{ $transaction->payment_status }}</span>
                    @endif
                </div>
            </div>

            <!-- Item List -->
            <div class="p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Item Belanja</h3>
                <div class="space-y-4">
                    @foreach($transaction->items as $item)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Summary Kalkulasi -->
            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <div class="w-full md:w-2/3 ml-auto space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal Belanja</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($transaction->discount_amount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Diskon</span>
                        <span class="font-medium text-red-500">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="pt-2 border-t border-gray-200 flex justify-between">
                        <span class="text-base font-bold text-gray-900">Total Dibayar Konsumen</span>
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-dashed border-gray-300">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500">Platform Fee (Potongan MDR MUI)</span>
                            <span class="font-medium text-red-500">- Rp {{ number_format($transaction->platform_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-base font-bold text-green-700">Pendapatan Bersih UMKM</span>
                            <span class="text-xl font-bold text-green-600">Rp {{ number_format($transaction->net_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-4 bg-white border-t border-gray-100 flex justify-center gap-3">
                <button onclick="window.print()" class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Struk
                </button>
                @if($transaction->payment_status !== 'failed')
                <form id="void-form-{{ $transaction->id }}" action="{{ route('report.transactions.void', $transaction->id) }}" method="POST" class="inline">
                    @csrf
                </form>
                <button type="button"
                        @click="window.dispatchEvent(new CustomEvent('confirm', {
                            detail: {
                                title: 'Batalkan Transaksi?',
                                message: 'Apakah Anda yakin ingin membatalkan transaksi ini? Seluruh stok barang fisik dalam transaksi ini akan dikembalikan secara otomatis.',
                                confirmText: 'Ya, Batalkan',
                                cancelText: 'Batal',
                                variant: 'danger',
                                onConfirm: () => {
                                    document.getElementById('void-form-{{ $transaction->id }}').submit();
                                }
                            }
                        }))"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Batalkan / Void Transaksi
                </button>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
