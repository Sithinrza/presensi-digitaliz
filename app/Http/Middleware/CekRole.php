<?php

namespace App\Http\Middleware; // <-- Pastikan namespace ini benar

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  // Ini adalah parameter dari route (misal: 'admin')
     */
   public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        // --- PASTIKAN PAKAI CARA BYPASS INI ---
        $userRoles = $user->roles->pluck('name');

        if ($userRoles->contains($role)) {
            // Jika role-nya sesuai, lanjutkan
            return $next($request);
        }
        // --- BATAS BYPASS ---

        abort(403, 'AKSES DITOLAK. Anda tidak punya hak akses.');
    }
}
