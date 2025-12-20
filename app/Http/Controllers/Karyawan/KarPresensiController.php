<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiKaryawan;
use App\Models\JadwalKaryawan;
use App\Models\DetailJadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KarPresensiController extends Controller
{

    private const CI_TOLERANCE_MINUTES = 60; // Toleransi Check-In: 60 menit
    private const CO_TOLERANCE_MINUTES = 60; // Toleransi Check-Out: 60 menit


    public function index()
    {
        $karyawanId = Auth::id() ?? 99;
        $currentTime = Carbon::now();
        $todayDate = $currentTime->toDateString();
        $todayName = $currentTime->locale('id')->isoFormat('dddd');

        $isWorkingDay = false;
        $shiftStart = null;
        $shiftEnd = null;

        $jadwalKaryawan = JadwalKaryawan::where('id_karyawan', $karyawanId)->first();

        if ($jadwalKaryawan) {
            $detailJadwal = DetailJadwal::where('id_jadwal_kerja', $jadwalKaryawan->id_jadwal_kerja)
                ->where('hari', $todayName)
                ->first();

            if ($detailJadwal && $detailJadwal->hari_kerja == 1) {
                $isWorkingDay = true;
                $shiftStart = Carbon::parse($todayDate . ' ' . $detailJadwal->jam_masuk);
                $shiftEnd = Carbon::parse($todayDate . ' ' . $detailJadwal->jam_pulang);
            }
        }

        // Status Presensi hari ini

        $presensiHariIni = PresensiKaryawan::with('status')
            ->where('karyawan_id', $karyawanId)
            ->where('tanggal', $todayDate)
            ->first();

        $isCiDone = (bool) $presensiHariIni;
        $isCoDone = $presensiHariIni && $presensiHariIni->waktu_co !== null;

        // --- riwayat untuk dash
        $history = PresensiKaryawan::with('status')
            ->where('karyawan_id', $karyawanId)
            ->latest('tanggal')
            ->latest('waktu_ci')
            ->take(5)
            ->get();

        return view('karyawan.presensi.index', compact('history', 'isCiDone', 'isCoDone', 'presensiHariIni', 'shiftEnd', 'shiftStart', 'isWorkingDay'));
    }

     public function store(Request $request)
    {
        $karyawanId = Auth::id() ?? 99;
        $currentTime = Carbon::now();
        $todayDate = $currentTime->toDateString();
        $todayName = $currentTime->isoFormat('dddd');
        $folderPath = "presensi_photos/";

        $isWorkingDay = false;
        $shiftStart = null;
        $shiftEnd = null;

        $jadwalKaryawan = JadwalKaryawan::where('id_karyawan', $karyawanId)->first();

        if ($jadwalKaryawan) {
            $detailJadwal = DetailJadwal::where('id_jadwal_kerja', $jadwalKaryawan->id_jadwal_kerja)
                ->where('hari', $todayName)
                ->first();

            if ($detailJadwal && $detailJadwal->hari_kerja == 1) {
                $isWorkingDay = true;
                $shiftStart = Carbon::parse($todayDate . ' ' . $detailJadwal->jam_masuk);
                $shiftEnd = Carbon::parse($todayDate . ' ' . $detailJadwal->jam_pulang);
            }
        }

        $error = null;
        if (!$isWorkingDay || $shiftStart === null || $shiftEnd === null) {
            $error = 'Hari ini adalah hari libur atau di luar jadwal kerja Anda. Presensi ditolak.';
        }

        if (!$error) {
            $request->validate([
                'image' => 'required',
                'latitude' => 'required|numeric|not_in:0',
                'longitude' => 'required|numeric|not_in:0',
            ], [
                'latitude.not_in' => 'Gagal menyimpan data lokasi. Coba lagi GPS.',
                'longitude.not_in' => 'Gagal menyimpan data lokasi. Coba lagi GPS.',
            ]);
        }

        $fileName = null;
        if (!$error) {
            $img = $request->image;
            $image_parts = explode(";base64,", $img);

            if (count($image_parts) < 2) {
                $error = 'Format gambar tidak valid.';
            } else {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = $karyawanId . '_' . $currentTime->format('Ymd_His') . '.png';
                Storage::disk('public')->put($folderPath . $fileName, $image_base64);
            }
        }

        if ($error) {
            return redirect()->route('karyawan.presensi.index')->withErrors($error);
        }

        $presensiHariIni = PresensiKaryawan::where('karyawan_id', $karyawanId)
            ->where('tanggal', $todayDate)
            ->first();

        $presensi = null;

        if (!$presensiHariIni) {
            $toleranceStart = $shiftStart->copy()->addMinutes(self::CI_TOLERANCE_MINUTES);

            $statusId = $currentTime->greaterThan($toleranceStart) ? 2 : 1;

            $presensi = PresensiKaryawan::create([
                'karyawan_id' => $karyawanId,
                'status_presensi_id' => $statusId,
                'tanggal' => $todayDate,
                'waktu_ci' => $currentTime->toDateTimeString(),
                'foto_ci' => $folderPath . $fileName,
                'latitude_ci' => $request->latitude,
                'longitude_ci' => $request->longitude,
            ]);

        } elseif ($presensiHariIni && is_null($presensiHariIni->waktu_co)) {
            $toleranceEnd = $shiftEnd->copy()->addMinutes(self::CO_TOLERANCE_MINUTES);

            if ($currentTime->lessThan($shiftEnd)) {
                $error = 'Check-Out ditolak. Anda hanya diizinkan Check-Out pada atau setelah ' . $shiftEnd->format('H:i') . ' sesuai jadwal.';
            } else {

                $statusId = $presensiHariIni->status_presensi_id;

                if ($currentTime->greaterThan($toleranceEnd)) {
                    // Pulang LEBIH DARI 1 JAM SETELAH JAM PULANG
                    $statusId = 3; // 3: Terlambat Check-Out (Prioritas tertinggi saat CO terlambat)

                } elseif ($statusId == 1 && $currentTime->lessThanOrEqualTo($toleranceEnd)) {
                    //CI Tepat Waktu (ID 1) + CO di bawah toleransi
                    $statusId = 1; // 1: Status tetap Tepat Waktu

                } elseif ($statusId == 2 && $currentTime->lessThanOrEqualTo($toleranceEnd)) {
                    // CI Terlambat (ID 2) + CO di bawah toleransi
                    $statusId = 2; // 2: Status tetap Terlambat Check-In
                }

                // Jika ada kasus lain (misal status awalnya Lupa CO/Tidak Hadir, yang seharusnya tidak terjadi di sini)
                // Biarkan $statusId = $presensiHariIni->status_presensi_id; (status awal)

                $presensiHariIni->update([
                    'waktu_co' => $currentTime->toDateTimeString(),
                    'foto_co' => $folderPath . $fileName,
                    'latitude_co' => $request->latitude,
                    'longitude_co' => $request->longitude,
                    'status_presensi_id' => $statusId, // Update status akhir
                ]);

                $presensi = $presensiHariIni;
            }

        } else {
            $error = 'Anda sudah menyelesaikan presensi hari ini.';
        }

        if ($error) {
            if ($fileName) {
                Storage::disk('public')->delete($folderPath . $fileName);
            }

            return redirect()->route('karyawan.presensi.index')
                ->withErrors($error);
        }

        return redirect()->route('karyawan.presensi.photo', ['id' => $presensi->id]);
    }


    public function show(PresensiKaryawan $presensi)
    {
        $karyawanId = Auth::id();

        if ($presensi->karyawan_id != $karyawanId) {
            abort(403, 'Akses ditolak. Detail presensi ini bukan milik Anda.');
        }

        return view('karyawan.presensi.riwayat', compact('presensi'));
    }
 
    public function photo($id)
    {
        $presensi = PresensiKaryawan::findOrFail($id);
        $user = Auth::user();

        return view('karyawan.presensi.photo', compact('presensi', 'user'));
    }
}
