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
        // 🚨 PERBAIKAN: Ubah $todayDate menjadi objek Carbon di awal, lalu ubah ke string hanya saat butuh
        $todayCarbon = $currentDate->copy()->startOfDay();
        $todayDate = $todayCarbon->toDateString();

        $tanggal_filter = $request->input('tanggal', $todayDate);
        $nama_filter = $request->input('nama');
        $status_filter = $request->input('status');

        // 🚨 PERBAIKAN: Konversi $tanggal_filter menjadi objek Carbon untuk perbandingan yang andal
        $filterCarbon = Carbon::parse($tanggal_filter)->startOfDay();


        // 2. Ambil Statistik Harian
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
        // PENTING: Eager Load relasi jadwal untuk menghindari N+1 problem dan mendapatkan jam kerja
        $query = PresensiKaryawan::with([
            'karyawan:id,nama_lengkap',
            'status:id,name',
            'jadwalKaryawan.jadwalKerja.detailJadwals'
        ])
            ->whereDate('tanggal', $tanggal_filter)
            ->where('karyawan_id', '!=', $adminId)
            ->orderBy('waktu_ci', 'desc');

        // Filter Berdasarkan Nama Karyawan
        if ($nama_filter) {
            $query->whereHas('karyawan', function ($q) use ($nama_filter) {
                $q->where('nama_lengkap', 'like', '%' . $nama_filter . '%');
            });
        }

        // Filter Berdasarkan Status (Terapkan filter ke status RINGKASAN yang sudah ada di DB)
        if ($status_filter) {
            $query->where('status_presensi_id', $status_filter);
        }

        $presensi_list = $query->get();

        // ⚠️ START PERBAIKAN: Menambahkan Status CI dan CO secara Terpisah DENGAN PENGECKAN JAM KERJA
        $presensi_list = $presensi_list->map(function ($presensi) use ($tanggal_filter, $currentDate, $filterCarbon, $todayCarbon) {

            // 0. Ambil Detail Jadwal Hari Ini
            $jamMasukSeharusnya = null;
            $jamPulangSeharusnya = null;
            $hariIni = Carbon::parse($tanggal_filter)->translatedFormat('l');

            $jadwalKaryawan = $presensi->jadwalKaryawan;

            if ($jadwalKaryawan && $jadwalKaryawan->jadwalKerja) {
                // Mencari detail jadwal untuk hari ini
                $detailJadwal = $jadwalKaryawan->jadwalKerja->detailJadwals
                    ->where('hari', $hariIni)
                    ->first();

                if ($detailJadwal && $detailJadwal->hari_kerja) {
                    $jamMasukSeharusnya = $detailJadwal->jam_masuk;
                    $jamPulangSeharusnya = $detailJadwal->jam_pulang;
                }
            }

            // 1. Logika Status Check-In (CI)
            if ($presensi->waktu_ci) {

                if ($jamMasukSeharusnya) {
                    $presensiCiTime = Carbon::parse($presensi->waktu_ci);
                    $jadwalCiTime = Carbon::parse($tanggal_filter . ' ' . $jamMasukSeharusnya);

                    // Jika waktu Check-In LEBIH DARI jam masuk seharusnya
                    if ($presensiCiTime->greaterThan($jadwalCiTime)) {
                        $presensi->status_ci_id = 2; // Terlambat Check-In
                    } else {
                        $presensi->status_ci_id = 1; // Tepat Waktu
                    }
                } else {
                    // Jika jadwal tidak ditemukan, fallback ke status yang disimpan di DB saat CI
                    $presensi->status_ci_id = in_array($presensi->status_presensi_id, [2, 3]) ? 2 : 1;
                }
            } else {
                $presensi->status_ci_id = 5; // Tidak Hadir / Lupa CI
            }

            // 2. Logika Status Check-Out (CO)
            if (is_null($presensi->waktu_co) && $presensi->waktu_ci) {
                // Kasus Karyawan sudah Check-In tapi belum Check-Out

                if ($jamPulangSeharusnya) {
                    $jamPulangHariIni = Carbon::parse($tanggal_filter . ' ' . $jamPulangSeharusnya);

                    // 🚨 PERBAIKAN: Gunakan Carbon::lessThan() untuk perbandingan tanggal yang akurat
                    if ($filterCarbon->lessThan($todayCarbon)) {
                        // Jika tanggalnya SUDAH LEWAT (Pasti Lupa CO)
                        $presensi->status_co_id = 4; // Lupa Check-Out
                    } elseif ($filterCarbon->equalTo($todayCarbon)) {
                        // Jika tanggalnya HARI INI
                        if ($currentDate->greaterThan($jamPulangHariIni)) {
                            // Jika jam sekarang SUDAH MELEWATI jam pulang
                            $presensi->status_co_id = 4; // Lupa Check-Out
                        } else {
                            // Jika jam sekarang BELUM MELEWATI jam pulang
                            $presensi->status_co_id = null; // Menunggu CO
                        }
                    } else {
                         $presensi->status_co_id = null; // Belum final (Tanggal di masa depan)
                    }
                } else {
                    // Jika jadwal tidak ditemukan, dan belum CO, set ke null (Menunggu CO)
                     $presensi->status_co_id = null;
                }
            } elseif ($presensi->waktu_co) {
                // Kasus Karyawan sudah Check-Out (Cek keterlambatan CO)
                $presensiCoTime = Carbon::parse($presensi->waktu_co);
                $jadwalCoTime = Carbon::parse($tanggal_filter . ' ' . $jamPulangSeharusnya);

                if ($jamPulangSeharusnya && $presensiCoTime->greaterThan($jadwalCoTime)) {
                     $presensi->status_co_id = 3; // Terlambat Check-Out
                } else {
                    $presensi->status_co_id = 1; // Tepat Waktu
                }
            } else {
                 $presensi->status_co_id = 5; // Tidak Hadir/N/A (Jika tidak CI dan tidak CO)
            }

            return $presensi;
        });
        // ⚠️ END PERBAIKAN

        // 4. Handle Kasus "Tidak Hadir" (Data Dummy)
        $karyawanYangBenarBenarTidakHadir = collect();
        if ($tanggal_filter == $todayDate && ($status_filter === null || $status_filter == 5)) {
            $karyawanIdsYangDiperhitungkan = Karyawan::where('id', '!=', $adminId)->pluck('id')->toArray();
            $presensiKaryawanIds = $presensiHariIni->pluck('karyawan_id')->toArray();
            $karyawanIdsYangTidakPresensi = array_diff($karyawanIdsYangDiperhitungkan, $presensiKaryawanIds);

            $karyawanYangBenarBenarTidakHadir = Karyawan::whereIn('id', $karyawanIdsYangTidakPresensi)
                ->select('id', 'nama_lengkap')
                ->get()
                ->map(function ($karyawan) use ($tanggal_filter) {
                    $dummyPresensi = new PresensiKaryawan();
                    $dummyPresensi->exists = true;
                    $dummyPresensi->id = null;
                    $dummyPresensi->setRelation('karyawan', $karyawan);
                    $dummyPresensi->karyawan_id = $karyawan->id;
                    $dummyPresensi->status_presensi_id = 5;
                    $dummyPresensi->setRelation('status', (object)['name' => 'Tidak Hadir']);
                    $dummyPresensi->tanggal = $tanggal_filter;
                    // --- Status CI/CO untuk Dummy ---
                    $dummyPresensi->status_ci_id = 5;
                    $dummyPresensi->status_co_id = 5;
                    // ... Mengisi nilai null lainnya ...
                    $dummyPresensi->waktu_ci = null;
                    $dummyPresensi->waktu_co = null;
                    $dummyPresensi->latitude_ci = null;
                    $dummyPresensi->longitude_ci = null;
                    $dummyPresensi->foto_ci = null;

                    return $dummyPresensi;
                });

            if ($status_filter == 5) {
                $presensi_list = $karyawanYangBenarBenarTidakHadir;
            } elseif ($status_filter === null) {
                // Gabungkan data presensi (yang sudah di-map) dengan data dummy
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
