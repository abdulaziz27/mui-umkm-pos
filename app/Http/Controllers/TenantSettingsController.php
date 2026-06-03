<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTenantSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TenantSettingsController extends Controller
{
    /**
     * Show the form for editing the tenant settings.
     */
    public function edit(Request $request): View
    {
        $tenant = $request->user()->tenant;

        return view('settings.shop', [
            'tenant' => $tenant,
        ]);
    }

    /**
     * Update the tenant settings.
     */
    public function update(UpdateTenantSettingsRequest $request): RedirectResponse
    {
        $tenant = $request->user()->tenant;
        
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($tenant->logo_path) {
                Storage::disk('public')->delete($tenant->logo_path);
            }

            $path = $request->file('logo')->store('tenant_logos', 'public');
            $validated['logo_path'] = $path;
        }

        $tenant->update($validated);

        return redirect()->route('settings.shop')->with('status', 'shop-updated');
    }
}
