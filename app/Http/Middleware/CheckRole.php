<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            $userRole = auth()->user()->role;
            $homeRoute = $userRole === 'warga' ? 'warga.landing' : 'dashboard';

            return redirect()->route($homeRoute)->with('error', 'Akses ditolak. Halaman ini hanya untuk pengguna dengan role: ' . implode(', ', $roles) . '. Role Anda: ' . $userRole);
        }

        return $next($request);
    }
}
