<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - {{ $tenant->name }}</title>
    <!-- We use Tailwind CDN here just for the print layout, ensuring it works independently of Vite build in some cases, or we can just use the standard app.css -->
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { background-color: white !important; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .print-break-inside-avoid { break-inside: avoid; }
        }
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .page-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            padding: 3rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        @media print {
            .page-container {
                margin: 0;
                padding: 0;
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>
</head>
<body class="text-gray-900">

    <!-- Print Action Bar (Hidden when printing) -->
    <div class="no-print bg-gray-800 text-white p-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
        <div>
            <h1 class="font-bold">Mode Pratinjau Cetak (PDF)</h1>
            <p class="text-xs text-gray-300">Gunakan fitur Print browser (Ctrl+P / Cmd+P) lalu pilih "Save as PDF".</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('report.transactions') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm font-semibold transition-colors">Kembali</a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-sm font-bold transition-colors shadow flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Document Wrapper -->
    <div class="page-container">
        
        <!-- Header -->
        <div class="border-b-2 border-gray-900 pb-6 mb-6 text-center">
            <h1 class="text-3xl font-black uppercase tracking-widest text-gray-900 mb-2">Laporan Penjualan</h1>
            <h2 class="text-xl font-bold text-gray-700">{{ $tenant->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $tenant->address }} | Telp: {{ $tenant->phone }}</p>
            <p class="text-xs text-gray-400 mt-3">Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
        </div>

        <!-- Summary -->
        <div class="flex gap-4 mb-8 bg-gray-50 p-4 rounded-xl border border-gray-200">
            <div class="flex-1 text-center border-r border-gray-200 last:border-0">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Total Omzet</p>
                <p class="text-lg font-black text-gray-900">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
            </div>
            <div class="flex-1 text-center border-r border-gray-200 last:border-0">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Potongan MDR (MUI)</p>
                <p class="text-lg font-black text-red-600">-Rp {{ number_format($totalPlatformFee, 0, ',', '.') }}</p>
            </div>
            <div class="flex-1 text-center border-r border-gray-200 last:border-0">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Pendapatan Bersih</p>
                <p class="text-lg font-black text-green-600">Rp {{ number_format($totalNetIncome, 0, ',', '.') }}</p>
            </div>
            <div class="flex-1 text-center">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Total Transaksi</p>
                <p class="text-lg font-black text-gray-900">{{ number_format($totalTransactionsCount) }} <span class="text-sm font-normal text-gray-500">trx</span></p>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-left text-sm mb-8">
            <thead>
                <tr class="border-b-2 border-gray-900 text-gray-900">
                    <th class="py-2 px-2 font-bold uppercase tracking-wider text-xs">Tanggal</th>
                    <th class="py-2 px-2 font-bold uppercase tracking-wider text-xs">No. Resi</th>
                    <th class="py-2 px-2 font-bold uppercase tracking-wider text-xs text-center">Metode</th>
                    <th class="py-2 px-2 font-bold uppercase tracking-wider text-xs text-right">Bruto (Rp)</th>
                    <th class="py-2 px-2 font-bold uppercase tracking-wider text-xs text-right">Fee (Rp)</th>
                    <th class="py-2 px-2 font-bold uppercase tracking-wider text-xs text-right">Neto (Rp)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($transactions as $trx)
                <tr class="print-break-inside-avoid">
                    <td class="py-3 px-2 text-gray-600 whitespace-nowrap">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3 px-2 font-mono text-gray-800">{{ $trx->receipt_number }}</td>
                    <td class="py-3 px-2 text-center">
                        <span class="uppercase text-[10px] font-bold border border-gray-300 px-1.5 py-0.5 rounded text-gray-600 bg-gray-100">{{ $trx->payment_method }}</span>
                    </td>
                    <td class="py-3 px-2 text-right font-medium text-gray-900">{{ number_format($trx->subtotal, 0, ',', '.') }}</td>
                    <td class="py-3 px-2 text-right text-red-600">-{{ number_format($trx->platform_fee, 0, ',', '.') }}</td>
                    <td class="py-3 px-2 text-right font-bold text-gray-900">{{ number_format($trx->total_amount - $trx->platform_fee, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500 italic">Belum ada riwayat transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer Note -->
        <div class="mt-12 pt-8 border-t border-gray-300 text-center text-xs text-gray-500">
            <p class="font-bold text-gray-700 uppercase mb-1">Daulat Umat Platform</p>
            <p>Laporan ini dihasilkan secara otomatis oleh sistem dan sah tanpa tanda tangan.</p>
        </div>
    </div>
</body>
</html>
