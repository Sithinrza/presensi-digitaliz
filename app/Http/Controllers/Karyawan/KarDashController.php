<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda; // 1. Import Model Agenda (Pastikan model Agenda ada)
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KarDashController extends Controller
{
    public function index(){
        // 1. Ambil data Karyawan dari User yang sedang login
        $karyawan = Auth::user()->karyawan;

        // Cek dulu, apakah user ini sudah terdaftar sebagai karyawan?
        if ($karyawan) {
            // 2. Jika ya, ambil agenda milik karyawan tersebut
            $agendaHariIni = $karyawan->agendas()
                                    ->whereDate('tanggal_agenda', Carbon::today())
                                    ->orderBy('waktu_mulai', 'asc')
                                    ->take(5)
                                    ->get();
        } else {
            // Jika user belum jadi karyawan / tidak punya data karyawan
            $agendaHariIni = collect(); // Kosongkan
        }
        // 4. Kirim data ke view menggunakan compact
        return view('karyawan.dashboard', compact('agendaHariIni'));;
    }
}
