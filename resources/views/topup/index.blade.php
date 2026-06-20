<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Top-Up Saldo Deposit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Saldo Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <p class="text-gray-500 text-sm font-semibold uppercase">Saldo Deposit Anda</p>
                        <h3 class="text-4xl font-bold text-green-600 mt-2">Rp {{ number_format(auth()->user()->tenant->credit_balance ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-400 mt-4">Setiap transaksi POS akan memotong saldo ini sesuai dengan tarif Platform Fee.</p>
                    </div>
                </div>

                <!-- Form Topup -->
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Pengajuan Isi Saldo (Manual Transfer)</h3>
                        <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded p-4 mb-6">
                            <p class="font-semibold">Instruksi Pembayaran Otomatis:</p>
                            <ol class="list-decimal list-inside text-sm mt-2">
                                <li>Masukkan nominal Top-Up yang diinginkan.</li>
                                <li>Klik tombol "Lanjut ke Pembayaran".</li>
                                <li>Anda akan diarahkan ke halaman pembayaran Xendit.</li>
                                <li>Pilih metode pembayaran (QRIS / Virtual Account) dan selesaikan pembayaran.</li>
                                <li>Saldo akan otomatis bertambah setelah pembayaran berhasil!</li>
                            </ol>
                        </div>

                        <form action="{{ route('topups.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700">Nominal Top-Up (Rp)</label>
                                <input type="number" name="amount" id="amount" min="10000" step="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: 50000" required>
                                <p class="text-xs text-gray-500 mt-1">Minimal pengisian saldo adalah Rp 10.000.</p>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Lanjut ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Riwayat Topup -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Riwayat Pengajuan Top-Up</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($topups as $topup)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $topup->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ number_format($topup->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($topup->status === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                            @elseif($topup->status === 'approved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($topup->payment_url && $topup->status === 'pending')
                                                <a href="{{ $topup->payment_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-bold underline">Bayar Sekarang</a>
                                            @elseif($topup->payment_proof_path)
                                                <a href="{{ Storage::url($topup->payment_proof_path) }}" target="_blank" class="text-gray-500 hover:text-gray-900">Lihat Struk Manual</a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                            Belum ada riwayat top-up.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $topups->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
