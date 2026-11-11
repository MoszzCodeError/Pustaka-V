<?php

namespace App\Http\Middleware;

use Closure; // <-- TAMBAHKAN 'use' DI SINI (tadi tertulis cuma 'Closure;')
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
{
    if (auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin'])) {
        return $next($request);
    }

    return redirect('/')->with('error', 'Anda tidak memiliki hak akses.');
}
}