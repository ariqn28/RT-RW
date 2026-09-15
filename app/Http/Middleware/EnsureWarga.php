<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureWarga
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || $request->user()->role !== 'warga') {
            return response()->json(['message' => 'Akses API ini hanya untuk akun warga.'], 403);
        }

        return $next($request);
    }
}
