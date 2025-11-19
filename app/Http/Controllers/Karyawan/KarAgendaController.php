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
        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))->toDateString()
            : Carbon::today()->toDateString();

        $karyawan = Auth::user()->karyawan;

        $agendaHariIni = Agenda::whereHas('karyawans', function ($query) use ($karyawan) {
                $query->where('karyawan_id', $karyawan->id);
            })
            ->whereDate('tanggal_agenda', $date)
            ->orderBy('waktu_mulai')
            ->get();

        return view('karyawan.agenda.index', compact('agendaHariIni', 'date'));
    }


    // ============================
    //  AJAX: ambil agenda by date
    // ============================
    public function getAgendaByDate(Request $request)
    {
        // 🔍 Debugging — untuk cek tanggal dan hasil query
        \Log::info("AJAX DATE:", [$request->date]);

        $date = $request->date;

        if (!$date) {
            return response()->json(['error' => 'Tanggal tidak ditemukan'], 400);
        }

        $karyawan = Auth::user()->karyawan;

        $agenda = Agenda::whereHas('karyawans', function ($query) use ($karyawan) {
                $query->where('karyawan_id', $karyawan->id);
            })
            ->whereDate('tanggal_agenda', $date)
            ->orderBy('waktu_mulai')
            ->get();

        // 🔍 Debugging hasil query:
        \Log::info("AGENDA:", $agenda->toArray());

        return response()->json($agenda);
    }
}
