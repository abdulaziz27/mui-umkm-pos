<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function tenants()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya untuk Super Admin.');
        }

        $pendingTenants = Tenant::where('status', 'pending')->latest()->get();
        $activeTenants = Tenant::where('status', 'active')->latest()->paginate(10);
        
        return view('admin.tenants', compact('pendingTenants', 'activeTenants'));
    }

    public function approveTenant(Request $request, $id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $tenant = Tenant::findOrFail($id);
        $tenant->update(['status' => 'active']);

        return back()->with('success', "UMKM {$tenant->name} berhasil disetujui dan sekarang aktif!");
    }

    public function suspendTenant(Request $request, $id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $tenant = Tenant::findOrFail($id);
        $tenant->update(['status' => 'suspended']);

        return back()->with('error', "UMKM {$tenant->name} telah dibekukan sementara.");
    }

    public function commissions(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya untuk Super Admin.');
        }

        $query = \App\Models\Transaction::query()->with('tenant');

        // Filter by Tenant
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Filter by Payment Method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Calculate dynamic summary metrics based on active filters
        $summaryQuery = clone $query;
        $totalCommission = $summaryQuery->sum('platform_fee');
        $totalTransactions = $summaryQuery->count();
        $totalGrossSales = $summaryQuery->sum('total_amount');

        // Paginate results and preserve query parameters
        $transactions = $query->latest()->paginate(20)->withQueryString();

        // Get list of active UMKMs for filter dropdown
        $tenants = Tenant::where('status', 'active')->orderBy('name')->get();

        return view('admin.commissions', compact(
            'totalCommission',
            'totalTransactions',
            'totalGrossSales',
            'transactions',
            'tenants'
        ));
    }

    public function updateCommission(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'platform_fee_type' => 'required|in:percentage,fixed',
            'mdr_fee_percentage' => 'required_if:platform_fee_type,percentage|numeric|min:0|max:100',
            'platform_fee_fixed' => 'required_if:platform_fee_type,fixed|numeric|min:0',
        ]);

        $tenant = Tenant::findOrFail($id);
        $tenant->update([
            'platform_fee_type' => $request->platform_fee_type,
            'mdr_fee_percentage' => $request->platform_fee_type === 'percentage' ? $request->mdr_fee_percentage : $tenant->mdr_fee_percentage,
            'platform_fee_fixed' => $request->platform_fee_type === 'fixed' ? $request->platform_fee_fixed : $tenant->platform_fee_fixed,
        ]);

        $label = $request->platform_fee_type === 'percentage'
            ? "{$request->mdr_fee_percentage}%"
            : 'Rp ' . number_format($request->platform_fee_fixed, 0, ',', '.');

        return back()->with('success', "Komisi untuk {$tenant->name} berhasil diperbarui menjadi {$label} ({$request->platform_fee_type}).");
    }
}
