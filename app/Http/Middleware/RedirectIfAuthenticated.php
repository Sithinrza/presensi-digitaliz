<?php

namespace App\Http\Middleware; // <-- Pastikan ini app/Http/Middleware

use Closure;
use Illuminate\Http\Request; // <--- INI PERLU DITAMBAHKAN
use Illuminate\Support\Facades\Auth; // <--- INI DIPERBAIKI (Tadi salah ketik)
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                // --- INI LOGIKA PENTINGNYA ---
                $user = Auth::user();
                $userRoles = $user->roles->pluck('name'); // Kita pakai cara bypass

                if ($userRoles->contains('admin')) {
                    // Jika 'admin' mencoba ke halaman login, tendang ke dashboard admin
                    return redirect('/admin/dashboard');

                } elseif ($userRoles->contains('karyawan')) {
                    // Jika 'karyawan' mencoba ke halaman login, tendang ke dashboard karyawan
                    return redirect('/karyawan/dashboard');

                } else {
                    // Jika rolenya aneh, tendang ke halaman / (yang akan error)
                    return redirect('/');
                }
                // --- BATAS LOGIKA ---
            }
        }

        return $next($request);
    }
}
