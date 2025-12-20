<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\PresensiKaryawan;
use App\Models\DailyReport;
use Carbon\Carbon;

class AdDashController extends Controller
{
    public function index()
    {
        $adminId = 1;
        $todayDate = Carbon::now()->toDateString();

        $totalKaryawan = Karyawan::where('id', '!=', $adminId)->count();

        $presensiHariIni = PresensiKaryawan::whereDate('tanggal', $todayDate)
            ->where('karyawan_id', '!=', $adminId)
            ->get();
        $totalHadir = 0;
        $totalTerlambat = 0;

        foreach ($presensiHariIni as $presensi) {
            $statusId = $presensi->status_presensi_id;

            if ($statusId !== 5) {
                $totalHadir++;
            }

            if (in_array($statusId, [2, 3, 4])) {
                $totalTerlambat++;
            }
        }

        $totalReportBaru = DailyReport::whereDate('report_date', $todayDate)->count();

        // Data yang dikirim ke View
        $stats = [
            'totalKaryawan' => $totalKaryawan,
            'hadirHariIni' => $totalHadir,
            'terlambat' => $totalTerlambat,
            'reportBaru' => $totalReportBaru,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
