<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hanya owner yang boleh akses manajemen pegawai
        if (!auth()->user()->isTenantOwner()) {
            abort(403, 'Akses ditolak.');
        }

        $tenantId = auth()->user()->tenant_id;
        
        // Ambil semua user dengan role 'cashier' di toko ini
        $staffs = User::where('tenant_id', $tenantId)
            ->where('role', 'cashier')
            ->latest()
            ->paginate(15);

        return view('staff.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isTenantOwner()) {
            abort(403, 'Akses ditolak.');
        }

        return view('staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isTenantOwner()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'cashier',
            'tenant_id' => auth()->user()->tenant_id,
        ]);

        return redirect()->route('menu.staff.index')->with('success', 'Akun kasir berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->isTenantOwner()) {
            abort(403, 'Akses ditolak.');
        }

        $staff = User::where('tenant_id', auth()->user()->tenant_id)
            ->where('role', 'cashier')
            ->findOrFail($id);

        return view('staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->isTenantOwner()) {
            abort(403, 'Akses ditolak.');
        }

        $staff = User::where('tenant_id', auth()->user()->tenant_id)
            ->where('role', 'cashier')
            ->findOrFail($id);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email,'.$staff->id],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        $staff->name = $request->name;
        $staff->email = $request->email;
        
        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        return redirect()->route('menu.staff.index')->with('success', 'Akun kasir berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->isTenantOwner()) {
            abort(403, 'Akses ditolak.');
        }

        $staff = User::where('tenant_id', auth()->user()->tenant_id)
            ->where('role', 'cashier')
            ->findOrFail($id);

        // TODO: Kita juga bisa mempertimbangkan untuk menggunakan SoftDeletes 
        // agar riwayat kasir di transaksi tidak hilang jika direlasikan kencang
        $staff->delete();

        return redirect()->route('menu.staff.index')->with('success', 'Akun kasir berhasil dihapus.');
    }
}
