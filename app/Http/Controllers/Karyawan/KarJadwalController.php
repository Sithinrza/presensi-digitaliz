<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Karyawan; // Pastikan ini di-use
use App\Models\Agenda; // Pastikan ini di-use

class KarJadwalController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date'))->toDateString() : Carbon::today()->toDateString();

        $karyawan = Auth::user()->karyawan;

        $agendaHariIni = Agenda::whereHas('karyawans', function ($query) use ($karyawan) {
            $query->where('karyawan_id', $karyawan->id);
        })
        ->whereDate('tanggal_agenda', $date) 
        ->orderBy('waktu_mulai')
        ->get();

        // 4. Kirim data ke view
        return view('karyawan.jadwal.index', compact('agendaHariIni', 'date'));
    }
}
