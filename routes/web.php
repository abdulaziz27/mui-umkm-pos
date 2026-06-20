<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\PublicController::class, 'index'])->name('home');
Route::get('/umkm/{slug}', [\App\Http\Controllers\PublicController::class, 'show'])->name('public.umkm');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shop Settings (Tenant Profile)
    Route::get('/settings/shop', [\App\Http\Controllers\TenantSettingsController::class, 'edit'])->name('settings.shop');
    Route::patch('/settings/shop', [\App\Http\Controllers\TenantSettingsController::class, 'update'])->name('settings.shop.update');

    // CRUD Menu Management
    Route::name('menu.')->group(function () {
        Route::get('products/import/template', [\App\Http\Controllers\ProductController::class, 'downloadTemplate'])->name('products.import.template');
        Route::post('products/import', [\App\Http\Controllers\ProductController::class, 'importExcel'])->name('products.import');
        Route::resource('categories', \App\Http\Controllers\CategoryController::class)->except(['show']);
        Route::resource('products', \App\Http\Controllers\ProductController::class)->except(['show']);
        Route::resource('promos', \App\Http\Controllers\PromoController::class)->except(['show']);
        Route::resource('staff', \App\Http\Controllers\StaffController::class)->except(['show']);
    });

    // POS Engine (Dilindungi Middleware)
    Route::middleware('tenant.active')->group(function () {
        Route::get('/pos/shift', [\App\Http\Controllers\PosController::class, 'showOpenShift'])->name('pos.shift.show');
        Route::post('/pos/shift/open', [\App\Http\Controllers\PosController::class, 'openShift'])->name('pos.shift.open');
        Route::get('/pos/shift/close', [\App\Http\Controllers\PosController::class, 'showCloseShift'])->name('pos.shift.close.show');
        Route::post('/pos/shift/close', [\App\Http\Controllers\PosController::class, 'closeShift'])->name('pos.shift.close');
        Route::get('/pos', [\App\Http\Controllers\PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/checkout', [\App\Http\Controllers\PosController::class, 'checkout'])->name('pos.checkout');
        Route::post('/pos/apply-promo', [\App\Http\Controllers\PosController::class, 'applyPromo'])->name('pos.apply-promo');
    });

    // Reports
    Route::get('/reports/transactions', [\App\Http\Controllers\ReportController::class, 'index'])->name('report.transactions');
    Route::post('/reports/transactions/{id}/void', [\App\Http\Controllers\ReportController::class, 'void'])->name('report.transactions.void');
    Route::get('/reports/transactions/export/csv', [\App\Http\Controllers\ReportController::class, 'exportCsv'])->name('report.transactions.export.csv');
    Route::get('/reports/transactions/export/print', [\App\Http\Controllers\ReportController::class, 'print'])->name('report.transactions.print');
    Route::get('/reports/transactions/{id}', [\App\Http\Controllers\ReportController::class, 'show'])->name('report.transactions.show');

    // Shifts Audit Report
    Route::get('/reports/shifts', [\App\Http\Controllers\ReportController::class, 'shifts'])->name('report.shifts');

    // Super Admin Routes
    Route::get('/admin/tenants', [\App\Http\Controllers\AdminController::class, 'tenants'])->name('admin.tenants');
    Route::post('/admin/tenants/{id}/approve', [\App\Http\Controllers\AdminController::class, 'approveTenant'])->name('admin.tenants.approve');
    Route::post('/admin/tenants/{id}/suspend', [\App\Http\Controllers\AdminController::class, 'suspendTenant'])->name('admin.tenants.suspend');
    Route::get('/admin/commissions', [\App\Http\Controllers\AdminController::class, 'commissions'])->name('admin.commissions');
    Route::put('/admin/tenants/{id}/commission', [\App\Http\Controllers\AdminController::class, 'updateCommission'])->name('admin.tenants.commission.update');

    // Top-Up Saldo Deposit
    Route::get('/topups', [\App\Http\Controllers\TopupController::class, 'index'])->name('topups.index');
    Route::get('/topups/success', [\App\Http\Controllers\TopupController::class, 'success'])->name('topups.success');
    Route::post('/topups', [\App\Http\Controllers\TopupController::class, 'store'])->name('topups.store');
    Route::post('/topups/{topup}/approve', [\App\Http\Controllers\TopupController::class, 'approve'])->name('topups.approve');
    Route::post('/topups/{topup}/reject', [\App\Http\Controllers\TopupController::class, 'reject'])->name('topups.reject');
});

require __DIR__.'/auth.php';
