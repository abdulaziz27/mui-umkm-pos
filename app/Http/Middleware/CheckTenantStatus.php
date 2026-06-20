<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->isSuperAdmin()) {
            $tenant = auth()->user()->tenant;
            
            // Jika tenant tidak ada atau statusnya pending/suspended, blokir akses POS
            if (!$tenant || $tenant->status !== 'active') {
                return redirect()->route('dashboard')->with('error', 'Akses ditolak. UMKM Anda masih dalam peninjauan atau dibekukan.');
            }
        }

        return $next($request);
    }
}
