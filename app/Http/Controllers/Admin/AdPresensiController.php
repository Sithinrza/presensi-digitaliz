<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiKaryawan;
use App\Models\Karyawan; // Pastikan ini adalah Model yang benar untuk tabel 'karyawans'
use App\Models\StatusPresensi; // Pastikan ini adalah Model yang benar untuk tabel 'status_presensis'
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // Digunakan untuk foto

class AdPresensiController extends Controller
{
    /**
     * Menampilkan halaman utama riwayat presensi dengan filter dan statistik harian.
     */
    public function index(Request $request)
    {
        // ID KARYAWAN YANG AKAN DIKECUALIKAN (ID ADMIN)
        // ⚠️ PENTING: GANTI LOGIKA FILTER ADMIN INI SESUAI SISTEM ROLE ANDA
        $adminId = 1;

        // 1. Setup Tanggal dan Filter
        $currentDate = Carbon::now();
        $todayDate = $currentDate->toDateString();
        $tanggal_filter = $request->input('tanggal', $todayDate);
        $nama_filter = $request->input('nama');
        $status_filter = $request->input('status');

        // 2. Ambil Statistik Harian
        // Mengecualikan Admin dari total Karyawan
        $totalKaryawan = Karyawan::where('id', '!=', $adminId)->count();

        $presensiHariIniQuery = PresensiKaryawan::whereDate('tanggal', $tanggal_filter);
        $presensiHariIni = $presensiHariIniQuery->get();

        $stats = [
            1 => $presensiHariIni->where('status_presensi_id', 1)->count(), // Tepat Waktu
            2 => $presensiHariIni->where('status_presensi_id', 2)->count(), // Terlambat Check-In
            3 => $presensiHariIni->where('status_presensi_id', 3)->count(), // Terlambat Check-Out
            4 => $presensiHariIni->where('status_presensi_id', 4)->count(), // Lupa Check-Out
            5 => $presensiHariIni->where('status_presensi_id', 5)->count(), // Tidak Hadir
        ];

        // 3. Query Utama untuk Daftar Presensi
        $query = PresensiKaryawan::with(['karyawan:id,nama_lengkap', 'status:id,name'])
            ->whereDate('tanggal', $tanggal_filter)
            ->where('karyawan_id', '!=', $adminId) // Kecualikan Admin dari daftar presensi
            ->orderBy('waktu_ci', 'desc');

        // Filter Berdasarkan Nama Karyawan
        if ($nama_filter) {
            $query->whereHas('karyawan', function ($q) use ($nama_filter) {
                $q->where('nama_lengkap', 'like', '%' . $nama_filter . '%');
            });
        }

        // Filter Berdasarkan Status
        if ($status_filter) {
            $query->where('status_presensi_id', $status_filter);
        }

        $presensi_list = $query->get();

        // 4. Handle Kasus "Tidak Hadir" (Data Dummy untuk karyawan yang belum ada record)
        $karyawanYangBenarBenarTidakHadir = collect();
        if ($tanggal_filter == $todayDate && ($status_filter === null || $status_filter == 5)) {

            // Dapatkan ID Karyawan yang seharusnya presensi (bukan Admin)
            $karyawanIdsYangDiperhitungkan = Karyawan::where('id', '!=', $adminId)->pluck('id')->toArray();

            $presensiKaryawanIds = $presensiHariIni->pluck('karyawan_id')->toArray();

            // Saring hanya ID yang belum presensi DAN BUKAN Admin
            $karyawanIdsYangTidakPresensi = array_diff($karyawanIdsYangDiperhitungkan, $presensiKaryawanIds);

            $karyawanYangBenarBenarTidakHadir = Karyawan::whereIn('id', $karyawanIdsYangTidakPresensi)
                ->select('id', 'nama_lengkap')
                ->get()
                ->map(function ($karyawan) use ($tanggal_filter) {

                    // Membuat instance Model PresensiKaryawan agar merge tidak error
                    $dummyPresensi = new PresensiKaryawan();

                    $dummyPresensi->exists = true;
                    $dummyPresensi->id = null;
                    $dummyPresensi->setRelation('karyawan', $karyawan);
                    $dummyPresensi->karyawan_id = $karyawan->id;
                    $dummyPresensi->status_presensi_id = 5;
                    $dummyPresensi->setRelation('status', (object)['name' => 'Tidak Hadir']);
                    $dummyPresensi->tanggal = $tanggal_filter;
                    // Mengisi nilai null lainnya
                    $dummyPresensi->waktu_ci = null;
                    $dummyPresensi->waktu_co = null;
                    $dummyPresensi->latitude_ci = null;
                    $dummyPresensi->longitude_ci = null;
                    $dummyPresensi->foto_ci = null;

                    return $dummyPresensi; // Mengembalikan instance Model
                });

            if ($status_filter == 5) {
                $presensi_list = $karyawanYangBenarBenarTidakHadir;
            } elseif ($status_filter === null) {
                $presensi_list = $presensi_list->merge($karyawanYangBenarBenarTidakHadir);
            }
        }

        // 5. Data untuk View
        $statuses = StatusPresensi::orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.presensi.riwayat', compact('presensi_list', 'tanggal_filter', 'nama_filter', 'status_filter', 'statuses', 'totalKaryawan', 'stats', 'currentDate'));
    }

    /**
     * Menampilkan halaman rekap (kosong, sesuai permintaan).
     */
    public function rekap()
    {
        return view('admin.presensi.rekap');
    }

    /**
     * Menampilkan detail presensi.
     */
    public function detail($id)
    {
        $presensi = PresensiKaryawan::with(['karyawan:id,nama_lengkap', 'status:id,name'])->findOrFail($id);

        $officeLocation = [
            'lat' => -3.3286312,
            'long' => 114.6075395,
            'radius' => 500,
        ];

        return view('admin.presensi.detail', compact('presensi', 'officeLocation'));
    }
}
