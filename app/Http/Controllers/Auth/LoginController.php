<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Memproses data login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $userRoles = $user->roles->pluck('name');

            if ($userRoles->contains('admin')) {
                return redirect()->intended('/dashboard/admin');

            } elseif ($userRoles->contains('karyawan')) {
                return redirect()->intended('/dashboard/karyawan');

            } else {
                Auth::logout();
                return redirect('/')->withErrors('Role tidak dikenal.');
            }
            // --- BATAS BYPASS ---
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
