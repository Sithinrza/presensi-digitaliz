<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\LogHarian;
use App\Models\PresensiKaryawan;
use App\Models\StatusPresensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogHarianController extends Controller
{

    public function index()
    {
        $karyawan = Auth::user()->karyawan;
        $today = Carbon::today()->toDateString();
        $logs = collect([]);

        $presensi = PresensiKaryawan::where('karyawan_id', $karyawan->id)
                                    ->where('tanggal', $today)
                                    ->first();

        $isPresensiValid = ($presensi && $presensi->status_presensi_id != 4 && $presensi->status_presensi_id != 5);

        if ($presensi) {
            $logs = LogHarian::where('presensi_karyawan_id', $presensi->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        return view('karyawan.log.log', compact('logs', 'today', 'isPresensiValid'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'catatan_log' => 'required|string',
        ]);

        $today = Carbon::today()->toDateString();
        $karyawan = Auth::user()->karyawan;

        $presensi = PresensiKaryawan::where('karyawan_id', $karyawan->id)
                                    ->where('tanggal', $today)
                                    ->first();

        if (!$presensi) {
            $statusHadir = StatusPresensi::where('name', 'Hadir')->first();

            if (!$statusHadir) {
                return back()->with('error', 'Status "Hadir" belum ada di database master.');
            }

            $presensi = PresensiKaryawan::create([
                'karyawan_id' => $karyawan->id,
                'status_presensi_id' => $statusHadir->id,
                'tanggal' => $today,
                'waktu_ci' => Carbon::now()->toDateTimeString(),
            ]);
           
        }

        LogHarian::create([
            'presensi_karyawan_id' => $presensi->id,
            'catatan_log' => $request->catatan_log,
        ]);

        return redirect()->route('karyawan.log.index')->with('success', 'Log aktivitas berhasil dicatat!');
    }
}
