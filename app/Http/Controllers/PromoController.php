<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        $promos = Promo::where('tenant_id', $tenant->id)->latest()->get();
        return view('promos.index', compact('promos'));
    }

    public function create()
    {
        return view('promos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'type' => 'required|in:nominal,percentage',
            'value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
        ]);

        $tenant = auth()->user()->tenant;
        $code = strtoupper(Str::slug($request->code, ''));

        if (Promo::where('tenant_id', $tenant->id)->where('code', $code)->exists()) {
            return back()->with('error', 'Kode promo sudah ada.');
        }

        Promo::create([
            'tenant_id' => $tenant->id,
            'code' => $code,
            'type' => $request->type,
            'value' => $request->value,
            'expires_at' => $request->expires_at,
            'is_active' => true,
        ]);

        return redirect()->route('menu.promos.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promo $promo)
    {
        if ($promo->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return view('promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        if ($promo->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if ($request->has('toggle_active')) {
            $promo->update(['is_active' => !$promo->is_active]);
            return back()->with('success', 'Status promo berhasil diperbarui.');
        }

        $request->validate([
            'type' => 'required|in:nominal,percentage',
            'value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
        ]);

        $promo->update([
            'type' => $request->type,
            'value' => $request->value,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('menu.promos.index')->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        if ($promo->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $promo->delete();

        return back()->with('success', 'Promo berhasil dihapus.');
    }
}
