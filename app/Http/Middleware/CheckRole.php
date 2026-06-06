<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek jika user tidak login
        if (!Auth::check()) {
            return redirect('login');
        }

        // Ambil role user yang sedang login
        $userRole = Auth::user()->role;

        // Cek apakah role user ada di dalam daftar $roles yang diizinkan
        if (in_array($userRole, $roles)) {
            // Jika diizinkan, lanjutkan request
            return $next($request);
        }

        // Jika tidak diizinkan, lempar ke dashboard
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses.');
    }
}
