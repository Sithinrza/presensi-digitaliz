<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // PASTIKAN INI ADA!
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PresensiKaryawan;
use App\Models\JadwalKaryawan;
use App\Models\DetailJadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KarPresensiController extends Controller
{
    /**
     * Menampilkan halaman utama presensi (Webcam) dan memuat data riwayat/status.
     * Route: GET /karyawan/presensi (karyawan.presensi.index)
     */
    public function index()
    {
        $karyawanId = Auth::id() ?? 99;
        $currentTime = Carbon::now();
        $todayDate = $currentTime->toDateString();
        // Pastikan locale Carbon diatur ke 'id' untuk mencocokkan nama hari di DB
        $todayName = $currentTime->locale('id')->isoFormat('dddd');

        // --- 1. AMBIL JADWAL KERJA HARI INI SECARA DINAMIS ---

        $isWorkingDay = false;
        $shiftStart = null;
        $shiftEnd = null;

        $jadwalKaryawan = JadwalKaryawan::where('id_karyawan', $karyawanId)->first();

        if ($jadwalKaryawan) {
            $detailJadwal = DetailJadwal::where('id_jadwal_kerja', $jadwalKaryawan->id_jadwal_kerja)
                ->where('hari', $todayName)
                ->first();

            // Tentukan apakah hari ini adalah hari kerja
            if ($detailJadwal && $detailJadwal->hari_kerja == 1) {
                $isWorkingDay = true;
                $shiftStart = Carbon::parse($todayDate . ' ' . $detailJadwal->jam_masuk);
                $shiftEnd = Carbon::parse($todayDate . ' ' . $detailJadwal->jam_pulang);
            }
        }

        // --- 2. AMBIL STATUS PRESENSI HARI INI ---

        $presensiHariIni = PresensiKaryawan::with('status')
            ->where('karyawan_id', $karyawanId)
            ->where('tanggal', $todayDate)
            ->first();

        $isCiDone = (bool) $presensiHariIni;
        $isCoDone = $presensiHariIni && $presensiHariIni->waktu_co !== null;


        // --- KRITIS: 3. LOGIKA ASUMSI STATUS SETELAH JAM KERJA BERAKHIR ---

        $assumptionError = null;

        if ($isWorkingDay && $shiftEnd) {
            // Batas Potong Keras (Hard Cutoff): Shift_End + 1 jam toleransi
            $hardCutoffTime = $shiftEnd->copy()->addHours(1);

            if ($currentTime->greaterThan($hardCutoffTime)) {

                if (!$presensiHariIni) {
                    // Skenario A: Sudah lewat jam pulang + 1 jam, tapi BELUM CI
                    $isCiDone = true;
                    $isCoDone = true; // Paksa tombol jadi 'Selesai' / Non-aktif
                    $assumptionError = 'Tidak Hadir. Waktu Check-In terlewat.';

                } elseif (!$isCoDone) {
                    // Skenario B: Ada CI tapi BELUM CO (Setelah jam pulang + 1 jam)
                    $isCoDone = true; // Paksa tombol jadi 'Selesai' / Non-aktif
                    $assumptionError = 'Lupa Check-Out. Batas Check-Out terlewat.';
                }

                // Catatan: Status ID 4 atau 5 akan dikerjakan oleh CRON JOB (dimalam hari).
            }
        }
        // --- AKHIR LOGIKA ASUMSI STATUS ---


        // --- 4. AMBIL RIWAYAT (Untuk Dashboard) ---
        $history = PresensiKaryawan::with('status')
            ->where('karyawan_id', $karyawanId)
            ->latest('tanggal')
            ->latest('waktu_ci')
            ->take(5)
            ->get();

        // Kirim semua data status dan jadwal ke view
        return view('karyawan.presensi.index', compact('history', 'isCiDone', 'isCoDone', 'presensiHariIni', 'shiftEnd', 'shiftStart', 'isWorkingDay', 'assumptionError'));
    }
    /**
     * Menyimpan data presensi (CI atau CO) ke database.
     * Route: POST /karyawan/presensi (karyawan.presensi.store)
     */
      public function store(Request $request)
    {
        // 1. Setup Data Waktu & Karyawan
        $karyawanId = Auth::id() ?? 99;
        $currentTime = Carbon::now();
        $todayDate = $currentTime->toDateString();
        $todayName = $currentTime->isoFormat('dddd');
        $folderPath = "presensi_photos/";

        // 2. Ambil Jadwal Kerja Dinamis
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

        // --- VALIDASI HARI KERJA ---
        $error = null;
        if (!$isWorkingDay || $shiftStart === null || $shiftEnd === null) {
            $error = 'Hari ini adalah hari libur atau di luar jadwal kerja Anda. Presensi ditolak.';
        }

        // 3. Validasi Input (Hanya jika belum ada error hari kerja)
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


        // 4. Proses Foto dan Simpan ke Storage (Hanya jika belum ada error)
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

        // Cek error setelah upload foto
        if ($error) {
            return redirect()->route('karyawan.presensi.index')->withErrors($error);
        }

        // 5. Tentukan Mode: Check-In (CI) atau Check-Out (CO)
        $presensiHariIni = PresensiKaryawan::where('karyawan_id', $karyawanId)
            ->where('tanggal', $todayDate)
            ->first();

        $presensi = null;

        if (!$presensiHariIni) {
            // --- LOGIKA CHECK-IN (CI) ---

            // Status Awal: Terlambat Check-In (2) atau Tepat Waktu (1)
            $statusId = $currentTime->greaterThan($shiftStart) ? 2 : 1;

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
            // --- LOGIKA CHECK-OUT (CO) ---

            // Toleransi Check-Out: Shift_End + 60 Menit
            $toleranceEnd = $shiftEnd->copy()->addMinutes(60);

            // VALIDASI WAKTU CO: Block jika Check-Out terlalu cepat (sebelum shiftEnd)
            if ($currentTime->lessThan($shiftEnd)) {
                $error = 'Check-Out ditolak. Anda hanya diizinkan Check-Out pada atau setelah ' . $shiftEnd->format('H:i') . ' sesuai jadwal.';
            } else {

                // Tentukan status ID akhir
                $statusId = $presensiHariIni->status_presensi_id; // Ambil status CI (1 atau 2)

                if ($currentTime->greaterThan($toleranceEnd)) {
                    // Skenario 1: Pulang LEBIH DARI 1 JAM SETELAH JAM PULANG
                    $statusId = 3; // 3: Terlambat Check-Out (Prioritas tertinggi saat CO terlambat)

                } elseif ($statusId == 1 && $currentTime->lessThanOrEqualTo($toleranceEnd)) {
                    // Skenario 2: CI Tepat Waktu (ID 1) + CO di bawah toleransi
                    $statusId = 1; // 1: Status tetap Tepat Waktu

                } elseif ($statusId == 2 && $currentTime->lessThanOrEqualTo($toleranceEnd)) {
                    // Skenario 3: CI Terlambat (ID 2) + CO di bawah toleransi
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
            // Sudah CI dan sudah CO.
            $error = 'Anda sudah menyelesaikan presensi hari ini.';
        }

        // 6. Penanganan Error dan Redirect
        if ($error) {
            // Hapus foto yang baru diunggah untuk presensi yang gagal
            if ($fileName) {
                Storage::disk('public')->delete($folderPath . $fileName);
            }

            // Redirect ke halaman index dengan pesan error
            return redirect()->route('karyawan.presensi.index')
                ->withErrors($error);
        }

        // 7. Redirect ke halaman konfirmasi foto
        return redirect()->route('karyawan.presensi.photo', ['id' => $presensi->id]);
    }

    /**
     * Menampilkan halaman konfirmasi foto presensi.
     * Route: GET /karyawan/presensi/photo/{id} (karyawan.presensi.photo)
     */
    public function photo($id)
    {
        $presensi = PresensiKaryawan::findOrFail($id);
        $user = Auth::user();

        return view('karyawan.presensi.photo', compact('presensi', 'user'));
    }
}
