<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            // Filter Periode
            $period = $request->input('period', 'this_month');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if ($period === 'today') {
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                $periodLabel = 'Hari Ini';
            } elseif ($period === 'this_month') {
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $periodLabel = 'Bulan Ini';
            } elseif ($period === 'this_year') {
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $periodLabel = 'Tahun Ini';
            } elseif ($period === 'custom' && $startDate && $endDate) {
                $start = \Carbon\Carbon::parse($startDate)->startOfDay();
                $end = \Carbon\Carbon::parse($endDate)->endOfDay();
                $periodLabel = \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y');
            } else {
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $periodLabel = 'Bulan Ini';
                $period = 'this_month';
            }

            // Metrik General
            $totalTenants = Tenant::where('status', 'active')->count();
            $pendingTenantsCount = Tenant::where('status', 'pending')->count();

            // Query Transaksi Berdasarkan Periode
            $trxQuery = Transaction::whereBetween('created_at', [$start, $end]);
            $totalPlatformFee = (clone $trxQuery)->sum('platform_fee');
            $totalTransactions = (clone $trxQuery)->count();

            // 1. Daftar UMKM Pending (Maksimal 5) untuk Persetujuan Cepat
            $pendingTenants = Tenant::where('status', 'pending')->latest()->take(5)->get();

            // 2. Daftar UMKM Berperforma Terbaik (Top 5 berdasarkan omset penjualan)
            $topTenants = Tenant::where('status', 'active')
                ->withSum(['transactions' => function($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                }], 'total_amount')
                ->withSum(['transactions' => function($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                }], 'platform_fee')
                ->get()
                ->sortByDesc('transactions_sum_total_amount')
                ->take(5);

            // 3. Grafik Pendapatan Komisi Platform Dinamis
            $diffInDays = $start->diffInDays($end);
            $chartLabels = [];
            $chartData = [];

            if ($diffInDays <= 31) {
                // Grafik Harian
                $sales = (clone $trxQuery)
                    ->selectRaw('DATE(created_at) as date, SUM(platform_fee) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('total', 'date');

                for ($i = 0; $i <= $diffInDays; $i++) {
                    $date = (clone $start)->addDays($i)->format('Y-m-d');
                    $chartLabels[] = \Carbon\Carbon::parse($date)->translatedFormat('d M');
                    $chartData[] = $sales[$date] ?? 0;
                }
            } else {
                // Grafik Bulanan
                $sales = (clone $trxQuery)
                    ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(platform_fee) as total')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total', 'month');

                $startMonth = (clone $start)->startOfMonth();
                while ($startMonth <= $end) {
                    $monthStr = $startMonth->format('Y-m');
                    $chartLabels[] = $startMonth->translatedFormat('M Y');
                    $chartData[] = $sales[$monthStr] ?? 0;
                    $startMonth->addMonth();
                }
            }

            return view('dashboard', compact(
                'totalTenants',
                'pendingTenantsCount',
                'totalPlatformFee',
                'totalTransactions',
                'pendingTenants',
                'topTenants',
                'chartLabels',
                'chartData',
                'period',
                'periodLabel',
                'startDate',
                'endDate'
            ));
        }

        // Data analitik untuk Pemilik UMKM (Owner)
        $tenant = $user->tenant;

        if (!$tenant) {
            return view('dashboard', [
                'todayRevenue' => 0,
                'totalTransactions' => 0,
                'totalProducts' => 0,
                'totalNetIncome' => 0,
            ]);
        }

        // Filter Periode
        $period = $request->input('period', 'today');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($period === 'today') {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
            $periodLabel = 'Hari Ini';
        } elseif ($period === 'this_month') {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
            $periodLabel = 'Bulan Ini';
        } elseif ($period === 'this_year') {
            $start = now()->startOfYear();
            $end = now()->endOfYear();
            $periodLabel = 'Tahun Ini';
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $start = \Carbon\Carbon::parse($startDate)->startOfDay();
            $end = \Carbon\Carbon::parse($endDate)->endOfDay();
            $periodLabel = \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y');
        } else {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
            $periodLabel = 'Hari Ini';
            $period = 'today';
        }

        // Base Query untuk Transaksi di Periode Tersebut
        $trxQuery = Transaction::where('tenant_id', $tenant->id)
            ->whereBetween('created_at', [$start, $end]);

        // Pendapatan di Periode Ini
        $periodRevenue = (clone $trxQuery)->sum('total_amount');

        // Total Transaksi di Periode Ini
        $periodTransactions = (clone $trxQuery)->count();

        // Pendapatan Bersih di Periode Ini
        $periodNetIncome = (clone $trxQuery)->sum('net_amount');

        // Total Produk Aktif (Keseluruhan)
        $totalProducts = Product::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->count();

        // Produk Terlaris (Top 5)
        $topProducts = \App\Models\TransactionItem::whereHas('transaction', function($q) use ($start, $end, $tenant) {
            $q->where('tenant_id', $tenant->id)
              ->where('payment_status', 'paid')
              ->whereBetween('created_at', [$start, $end]);
        })
        ->selectRaw('product_name, SUM(quantity) as total_quantity, SUM(subtotal) as total_revenue')
        ->groupBy('product_name')
        ->orderByDesc('total_quantity')
        ->take(5)
        ->get();

        // Breakdown Metode Pembayaran
        $paymentMethods = (clone $trxQuery)
            ->where('payment_status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        // Feature 4: Stok Menipis (Low Stock Alert)
        $lowStockProducts = Product::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->where('type', 'physical')
            ->where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();

        // Feature 2: Grafik Penjualan Dinamis Berdasarkan Periode
        $diffInDays = $start->diffInDays($end);
        
        $chartLabels = [];
        $chartData = [];

        if ($diffInDays <= 31) {
            // Grafik Harian
            $sales = (clone $trxQuery)
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

            for ($i = 0; $i <= $diffInDays; $i++) {
                $date = (clone $start)->addDays($i)->format('Y-m-d');
                $chartLabels[] = \Carbon\Carbon::parse($date)->translatedFormat('d M');
                $chartData[] = $sales[$date] ?? 0;
            }
        } else {
            // Grafik Bulanan (Tahun Ini / Kustom panjang)
            $sales = (clone $trxQuery)
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $startMonth = (clone $start)->startOfMonth();
            while ($startMonth <= $end) {
                $monthStr = $startMonth->format('Y-m');
                $chartLabels[] = $startMonth->translatedFormat('M Y');
                $chartData[] = $sales[$monthStr] ?? 0;
                $startMonth->addMonth();
            }
        }

        return view('dashboard', compact(
            'periodRevenue',
            'periodTransactions',
            'totalProducts',
            'periodNetIncome',
            'tenant',
            'lowStockProducts',
            'topProducts',
            'paymentMethods',
            'chartLabels',
            'chartData',
            'period',
            'periodLabel',
            'startDate',
            'endDate'
        ));
    }
}
