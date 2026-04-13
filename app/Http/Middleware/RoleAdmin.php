<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  <-- Tambahkan dokumentasi ini
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // --- UBAH BARIS INI: Tambahkan parameter $role ---
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login (auth()->user() tidak null)
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // --- UBAH BARIS INI: Bandingkan dengan parameter $role, bukan hardcode 'admin' ---
        if (auth()->user()->role !== $role) {
            abort(403, 'Anda tidak memiliki akses sebagai ' . $role);
        }

        return $next($request);
    }
}