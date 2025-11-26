<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiKaryawan;
use App\Models\Karyawan;
use App\Models\StatusPresensi;
use App\Models\JadwalKaryawan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AdPresensiController extends Controller
{
    /**
     * Menampilkan halaman utama riwayat presensi dengan filter dan statistik harian.
     */
    public function index(Request $request)
    {
        // ID KARYAWAN YANG AKAN DIKECUALIKAN (ID ADMIN)
        $adminId = 1;

        // 1. Setup Tanggal dan Filter
        $currentDate = Carbon::now();
        $todayCarbon = $currentDate->copy()->startOfDay();
        $todayDate = $todayCarbon->toDateString(); // String format YYYY-MM-DD

        $tanggal_filter = $request->input('tanggal', $todayDate);
        $nama_filter = $request->input('nama');
        $status_filter = $request->input('status');

        $filterCarbon = Carbon::parse($tanggal_filter)->startOfDay();

        // 2. Ambil Data Karyawan Penuh (Inisialisasi)
        $karyawanIdsYangDiperhitungkan = Karyawan::where('id', '!=', $adminId)->pluck('id')->toArray();
        $totalKaryawan = count($karyawanIdsYangDiperhitungkan);

        // 3. Query Utama: Mulai dari Karyawan, Left Join Presensi
        $presensi_list_query = Karyawan::with([
            'presensiHariIni' => function ($query) use ($tanggal_filter) {
                $query->whereDate('tanggal', $tanggal_filter);
            },
            'jadwalKaryawan.jadwalKerja.detailJadwals'
        ])
            ->where('id', '!=', $adminId);

        // Filter Berdasarkan Nama Karyawan (diterapkan di query Karyawan)
        if ($nama_filter) {
            $presensi_list_query->where('nama_lengkap', 'like', '%' . $nama_filter . '%');
        }

        $presensi_list_existing = $presensi_list_query->get();


        // ⚠️ START MAPPING STATUS CI/CO YANG AKURAT (Applied to ALL employees)
        $presensi_list_mapped = $presensi_list_existing->map(function ($karyawan) use ($tanggal_filter, $currentDate, $filterCarbon, $todayCarbon) {

            $presensi = $karyawan->presensiHariIni ?? new PresensiKaryawan();

            // Logika Jadwal
            $jamMasukSeharusnya = null;
            $jamPulangSeharusnya = null;
            $hariIni = Carbon::parse($tanggal_filter)->translatedFormat('l');
            $ciToleranceMinutes = 10;
            $coToleranceMinutes = 60;

            if ($karyawan->jadwalKaryawan && $karyawan->jadwalKaryawan->jadwalKerja) {
                $detailJadwal = $karyawan->jadwalKaryawan->jadwalKerja->detailJadwals
                    ->where('hari', $hariIni)
                    ->first();

                if ($detailJadwal && $detailJadwal->hari_kerja) {
                    $jamMasukSeharusnya = $detailJadwal->jam_masuk;
                    $jamPulangSeharusnya = $detailJadwal->jam_pulang;
                }
            }

            // --- INI ADALAH NILAI DEFAULT SEMENTARA (NETRAL) ---
            $presensi->status_ci_id = null;
            $presensi->status_co_id = null;
            $presensi->status_presensi_id = null;

            // --- LOGIKA HANYA BERJALAN JIKA ADA WAKTU CI ---
            if ($presensi->waktu_ci) {

                // 1. Logika Status Check-In (CI)
                if ($jamMasukSeharusnya) {
                    $presensiCiTime = Carbon::parse($presensi->waktu_ci);
                    $jadwalCiTime = Carbon::parse($tanggal_filter . ' ' . $jamMasukSeharusnya);
                    $ciToleranceTime = $jadwalCiTime->copy()->addMinutes($ciToleranceMinutes);

                    $presensi->status_ci_id = $presensiCiTime->greaterThan($ciToleranceTime) ? 2 : 1; // 1 atau 2
                } else {
                    $presensi->status_ci_id = in_array($presensi->status_presensi_id, [2, 3, 4]) ? 2 : 1;
                }

                // 2. Logika Status Check-Out (CO)
                if (is_null($presensi->waktu_co)) {
                    if ($jamPulangSeharusnya) {
                        $jamPulangHariIni = Carbon::parse($tanggal_filter . ' ' . $jamPulangSeharusnya);

                        if ($filterCarbon->lessThan($todayCarbon)) {
                            $presensi->status_co_id = 4; // Lupa CO (Tanggal Lewat)
                        } elseif ($filterCarbon->equalTo($todayCarbon)) {
                            $hardCutoff = Carbon::parse($tanggal_filter . ' ' . $jamPulangSeharusnya)->addMinutes($coToleranceMinutes);
                            if ($currentDate->greaterThan($hardCutoff)) { // Jika lewat jam pulang + toleransi
                                $presensi->status_co_id = 4; // Lupa CO
                            }
                        } else {
                            $presensi->status_co_id = null; // Tanggal di masa depan
                        }
                    } else {
                        $presensi->status_co_id = null;
                    }
                } elseif ($presensi->waktu_co) {
                    $presensiCoTime = Carbon::parse($presensi->waktu_co);
                    $jadwalCoTime = Carbon::parse($tanggal_filter . ' ' . $jamPulangSeharusnya);
                    $toleranceCoTime = $jadwalCoTime->copy()->addMinutes($coToleranceMinutes);

                    $presensi->status_co_id = ($jamPulangSeharusnya && $presensiCoTime->greaterThan($toleranceCoTime)) ? 3 : 1; // 3 atau 1
                }

                // 3. Update status_presensi_id (Status Ringkasan Akhir)

                if ($presensi->status_co_id == 4) {
                     $statusRingkasan = 4; // Lupa CO (Prioritas Tertinggi)
                } elseif ($presensi->status_co_id == 3) {
                     $statusRingkasan = 3; // Terlambat CO
                } elseif ($presensi->status_ci_id == 2) {
                     $statusRingkasan = 2; // Terlambat CI
                } elseif ($presensi->status_ci_id == 1 && $presensi->status_co_id === 1) {
                     $statusRingkasan = 1; // Tepat Waktu (Hanya jika CI=1 DAN CO=1)
                } else {
                     $statusRingkasan = $presensi->status_ci_id; // Default, misal Tepat Waktu jika CI=1
                }

                $presensi->status_presensi_id = $statusRingkasan;
            } else {
                // 🚨 LOGIKA TIDAK ADA CI

                $isPassedCutoff = false;
                if ($jamPulangSeharusnya) {
                    $jamPulangHariIni = Carbon::parse($tanggal_filter . ' ' . $jamPulangSeharusnya);
                    $toleranceCoTime = $jamPulangHariIni->copy()->addMinutes($coToleranceMinutes);

                    // Pengecekan HARI INI dan SUDAH LEWAT batas CO
                    if ($filterCarbon->equalTo($todayCarbon) && $currentDate->greaterThan($toleranceCoTime)) {
                        $isPassedCutoff = true;
                    }
                }

                if ($filterCarbon->lessThan($todayCarbon) || $isPassedCutoff) {
                    // Jika tanggal sudah lewat ATAU sudah lewat batas waktu CO hari ini -> FINAL TIDAK HADIR
                    $presensi->status_ci_id = 5;
                    $presensi->status_co_id = 5;
                    $presensi->status_presensi_id = 5;
                } else {
                    // Jika tanggal HARI INI dan BELUM melewati batas CO -> BELUM PRESENSI (NULL)
                    $presensi->status_ci_id = null;
                    $presensi->status_co_id = null;
                    $presensi->status_presensi_id = null;
                }
            }

            // Pasang kembali objek presensi yang sudah di-mapping ke objek karyawan
            $karyawan->presensi_detail = $presensi;

            return $karyawan;
        });
        // ⚠️ END MAPPING

        // 4. Menerapkan Filter Status dan Menghitung Statistik
        $presensi_list = $presensi_list_mapped; // Daftar karyawan lengkap

        // Filter status hanya diterapkan di akhir
        if ($status_filter) {
            $presensi_list = $presensi_list->filter(function($karyawan) use ($status_filter) {
                // Khusus filter Terlambat CI, cari status_ci_id = 2
                if ($status_filter == 2) {
                    return $karyawan->presensi_detail->status_ci_id == 2;
                }
                // Khusus filter Tidak Hadir, cari status_presensi_id = 5
                if ($status_filter == 5) {
                    return $karyawan->presensi_detail->status_presensi_id == 5;
                }

                // Untuk status lain (1, 3, 4, 6), gunakan status ringkasan
                return $karyawan->presensi_detail->status_presensi_id == $status_filter;
            });
        }

        // 🚨 FINAL PERBAIKAN: Filter Semua harus menampilkan SEMUA KARYAWAN (termasuk yang NULL statusnya)

        // 🚨 HITUNG STATISTIK DARI $presensi_list_mapped LENGKAP
        // Kita hitung ID 5 (Tidak Hadir) dan ID 99 (Belum Ada Aktivitas) sebagai status final
        $stats = [
            1 => $presensi_list_mapped->where('presensi_detail.status_presensi_id', 1)->count(),
            2 => $presensi_list_mapped->where('presensi_detail.status_ci_id', 2)->count(), // CI Murni
            3 => $presensi_list_mapped->where('presensi_detail.status_presensi_id', 3)->count(),
            4 => $presensi_list_mapped->where('presensi_detail.status_presensi_id', 4)->count(),
            // 🚨 PENTING: Hitung ID 5 (TIDAK HADIR FINAL) dan ID NULL (BELUM CI)
            5 => $presensi_list_mapped->where('presensi_detail.status_presensi_id', 5)->count(),
        ];


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
