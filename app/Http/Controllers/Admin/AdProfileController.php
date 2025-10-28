<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdProfileController extends Controller
{
    public function index(){
         $user = Auth::user();

        $karyawan = $user->karyawan;
        return view('admin.users.users', compact('user', 'karyawan'));
    }

    public function detail(){
        $user = Auth::user();

        // Pastikan semua relasi yang akan kamu tampilkan di view dimuat di sini (Eager Loading)
        $karyawan = $user->karyawan->load([
            'agama',
            'jabatan',
            'divisi',
            'posisi',
            'pendidikanTerakhir'
        ]);

        return view('admin.users.detail', compact('user', 'karyawan'));
    }
}
