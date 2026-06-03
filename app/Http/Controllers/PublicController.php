<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $tenants = $query->latest()->paginate(12);

        return view('welcome', compact('tenants'));
    }

    public function show($slug)
    {
        $tenant = Tenant::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $categories = $tenant->categories()
            ->where('is_active', true)
            ->with(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->get();

        return view('public.umkm', compact('tenant', 'categories'));
    }
}
