<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PresensiKaryawan;
use App\Models\Karyawan;
use App\Models\LogHarian;
use App\Models\DailyReport;

class KarDashController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $todayDate = Carbon::now()->toDateString();
        $isWorkingDay = true;

        $karyawan = Karyawan::where('user_id', $userId)->first();
        $karyawanId = $karyawan->id ?? null;

        $presensiHariIni = PresensiKaryawan::where('karyawan_id', $karyawanId)
            ->where('tanggal', $todayDate)
            ->first();

        $isWorkingDay = true;

        $agendaHariIni = collect();

        if ($karyawan) {
             $agendaHariIni = $karyawan->agendas()
                ->whereDate('agendas.tanggal_agenda', $todayDate)
                ->latest()
                ->get();
        }

        $logAktivitasHariIni = collect();

        if ($presensiHariIni) {
            $logAktivitasHariIni = LogHarian::where('presensi_karyawan_id', $presensiHariIni->id)
                ->latest()
                ->take(5)
                ->get();
        }

        $dailyReportHariIni = DailyReport::where('employee_id', $karyawanId)
            ->whereDate('report_date', $todayDate)
            ->latest()
            ->first();


        return view('karyawan.dashboard', compact(
            'agendaHariIni',
            'logAktivitasHariIni',
            'dailyReportHariIni',
            'presensiHariIni',
            'isWorkingDay',
            'todayDate'
        ));
    }
}
