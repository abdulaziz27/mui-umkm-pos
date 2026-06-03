<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Owner Validation
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // Tenant Validation
            'tenant_name' => ['required', 'string', 'max:255'],
            'tenant_type' => ['required', 'in:retail,fnb,service,other'],
            'tenant_phone' => ['required', 'string', 'max:20'],
            'tenant_address' => ['required', 'string'],
        ]);

        $user = DB::transaction(function () use ($request) {
            // 1. Create Tenant (UMKM) with 'pending' status
            $tenant = Tenant::create([
                'name' => $request->tenant_name,
                'slug' => Str::slug($request->tenant_name) . '-' . Str::random(4),
                'type' => $request->tenant_type,
                'phone' => $request->tenant_phone,
                'address' => $request->tenant_address,
                'status' => 'pending', // Requires Super Admin approval
                'mdr_fee_percentage' => 2.00, // Default MVP MDR Fee 2%
            ]);

            // 2. Create Owner User linked to Tenant
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'tenant_owner',
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))->with('success', 'Pendaftaran berhasil! Akun UMKM Anda sedang dalam status Menunggu Persetujuan (Pending).');
    }
}
