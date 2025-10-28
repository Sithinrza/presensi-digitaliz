<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Karyawan; // Pastikan Anda sudah membuat model Karyawan

class KarProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $karyawan = $user->karyawan;
        return view('karyawan.users.users', compact('user', 'karyawan'));
    }

    public function detail()
    {
        $user = Auth::user();

        // Pastikan semua relasi yang akan kamu tampilkan di view dimuat di sini (Eager Loading)
        $karyawan = $user->karyawan->load([
            'agama',
            'jabatan',
            'divisi',
            'posisi',
            'pendidikanTerakhir'
        ]);

        return view('karyawan.users.detail', compact('user', 'karyawan'));
    }
}
