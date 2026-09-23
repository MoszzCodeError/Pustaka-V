<?php

namespace App\Http\Middleware;

use Closure; // <-- TAMBAHKAN 'use' DI SINI (tadi tertulis cuma 'Closure;')
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login atau bukan admin, tendang ke dashboard biasa/login
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses admin!');
        }

        return $next($request);
    }
}