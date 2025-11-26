<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\PresensiKaryawan;
use App\Models\DailyReport;    // PENTING: Import Model DailyReport
use Carbon\Carbon;

class AdDashController extends Controller
{
    /**
     * Menampilkan Dashboard dengan statistik presensi hari ini dan laporan baru.
     */
    public function index()
    {
        // ID KARYAWAN YANG AKAN DIKECUALIKAN (ID ADMIN)
        $adminId = 1;
        $todayDate = Carbon::now()->toDateString();

        // 1. Ambil Total Karyawan
        $totalKaryawan = Karyawan::where('id', '!=', $adminId)->count();

        // 2. Ambil data Presensi Hari Ini (untuk Hadir dan Terlambat)
        $presensiHariIni = PresensiKaryawan::whereDate('tanggal', $todayDate)
            ->where('karyawan_id', '!=', $adminId)
            ->get();

        // 3. Hitung Statistik Presensi
        $totalHadir = 0;
        $totalTerlambat = 0;

        foreach ($presensiHariIni as $presensi) {
            $statusId = $presensi->status_presensi_id;

            // Hadir Hari Ini: Setiap status kecuali 5 (Tidak Hadir)
            if ($statusId !== 5) {
                $totalHadir++;
            }

            // Terlambat: Status ringkasan 2, 3, atau 4 mencerminkan adanya masalah
            if (in_array($statusId, [2, 3, 4])) {
                $totalTerlambat++;
            }
        }

        // 4. Hitung Laporan Report Baru (Hari Ini)
        // 🚨 PERBAIKAN: Hanya hitung dari tabel DailyReport (Laporan Utama)
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
