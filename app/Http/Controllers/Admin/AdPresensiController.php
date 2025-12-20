<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiKaryawan;
use App\Models\Karyawan;
use App\Models\StatusPresensi;
use Carbon\Carbon;

class AdPresensiController extends Controller
{
    public function index(Request $request)
    {
        $adminId = 1;

        $currentDate = Carbon::now();
        $todayCarbon = $currentDate->copy()->startOfDay();
        $todayDate = $todayCarbon->toDateString();

        $tanggal_filter = $request->input('tanggal', $todayDate);
        $nama_filter = $request->input('nama');
        $status_filter = $request->input('status');

        $filterCarbon = Carbon::parse($tanggal_filter)->startOfDay();

        $karyawanIdsYangDiperhitungkan = Karyawan::where('id', '!=', $adminId)->pluck('id')->toArray();
        $totalKaryawan = count($karyawanIdsYangDiperhitungkan);

        $presensi_list_query = Karyawan::with([
            'presensiHariIni' => function ($query) use ($tanggal_filter) {
                $query->whereDate('tanggal', $tanggal_filter);
            },
            'jadwalKaryawan.jadwalKerja.detailJadwals'
        ])
            ->where('id', '!=', $adminId);

        if ($nama_filter) {
            $presensi_list_query->where('nama_lengkap', 'like', '%' . $nama_filter . '%');
        }

        $presensi_list_existing = $presensi_list_query->get();


        $presensi_list_mapped = $presensi_list_existing->map(function ($karyawan) use ($tanggal_filter, $currentDate, $filterCarbon, $todayCarbon) {

            $presensi = $karyawan->presensiHariIni ?? new PresensiKaryawan();

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

            $presensi->status_ci_id = null;
            $presensi->status_co_id = null;
            $presensi->status_presensi_id = null;

            if ($presensi->waktu_ci) {

                if ($jamMasukSeharusnya) {
                    $presensiCiTime = Carbon::parse($presensi->waktu_ci);
                    $jadwalCiTime = Carbon::parse($tanggal_filter . ' ' . $jamMasukSeharusnya);
                    $ciToleranceTime = $jadwalCiTime->copy()->addMinutes($ciToleranceMinutes);

                    $presensi->status_ci_id = $presensiCiTime->greaterThan($ciToleranceTime) ? 2 : 1; // 1 atau 2
                } else {
                    $presensi->status_ci_id = in_array($presensi->status_presensi_id, [2, 3, 4]) ? 2 : 1;
                }

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


                if ($presensi->status_co_id == 4) {
                     $statusRingkasan = 4; // Lupa CO
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

                $isPassedCutoff = false;
                if ($jamPulangSeharusnya) {
                    $jamPulangHariIni = Carbon::parse($tanggal_filter . ' ' . $jamPulangSeharusnya);
                    $toleranceCoTime = $jamPulangHariIni->copy()->addMinutes($coToleranceMinutes);

                    if ($filterCarbon->equalTo($todayCarbon) && $currentDate->greaterThan($toleranceCoTime)) {
                        $isPassedCutoff = true;
                    }
                }

                if ($filterCarbon->lessThan($todayCarbon) || $isPassedCutoff) {
                    $presensi->status_ci_id = 5;
                    $presensi->status_co_id = 5;
                    $presensi->status_presensi_id = 5;
                } else {
                    $presensi->status_ci_id = null;
                    $presensi->status_co_id = null;
                    $presensi->status_presensi_id = null;
                }
            }

            $karyawan->presensi_detail = $presensi;

            return $karyawan;
        });
        $presensi_list = $presensi_list_mapped;
        if ($status_filter) {
            $presensi_list = $presensi_list->filter(function($karyawan) use ($status_filter) {
                if ($status_filter == 2) {
                    return $karyawan->presensi_detail->status_ci_id == 2;
                }
                if ($status_filter == 5) {
                    return $karyawan->presensi_detail->status_presensi_id == 5;
                }

                return $karyawan->presensi_detail->status_presensi_id == $status_filter;
            });
        }

        $stats = [
            1 => $presensi_list_mapped->where('presensi_detail.status_presensi_id', 1)->count(),
            2 => $presensi_list_mapped->where('presensi_detail.status_ci_id', 2)->count(),
            3 => $presensi_list_mapped->where('presensi_detail.status_presensi_id', 3)->count(),
            4 => $presensi_list_mapped->where('presensi_detail.status_presensi_id', 4)->count(),
            5 => $presensi_list_mapped->where('presensi_detail.status_presensi_id', 5)->count(),
        ];


        $statuses = StatusPresensi::orderBy('id')->pluck('name', 'id')->toArray();

        return view('admin.presensi.riwayat', compact('presensi_list', 'tanggal_filter', 'nama_filter', 'status_filter', 'statuses', 'totalKaryawan', 'stats', 'currentDate'));
    }

    public function rekap()
    {
        return view('admin.presensi.rekap');
    }

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
