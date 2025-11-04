<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KarAgendaController extends Controller
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
        return view('karyawan.agenda.index', compact('agendaHariIni', 'date'));
    }
}
