<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $todayName = $currentTime->isoFormat('dddd');

        // --- 1. AMBIL JADWAL KERJA HARI INI ---
        $shiftStart = Carbon::today()->setTime(8, 30, 0); // Default CI
        $shiftEnd = Carbon::today()->setTime(10, 14, 0); // Default CO

        $jadwalKaryawan = JadwalKaryawan::where('id_karyawan', $karyawanId)->first();

        if ($jadwalKaryawan) {
            $detailJadwal = DetailJadwal::where('id_jadwal_kerja', $jadwalKaryawan->id_jadwal_kerja)
                ->where('hari', $todayName)
                ->first();

            if ($detailJadwal && $detailJadwal->hari_kerja == 1) { // Hanya jika hari kerja
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

        // --- 3. AMBIL RIWAYAT ---
        $history = PresensiKaryawan::with('status')
            ->where('karyawan_id', $karyawanId)
            ->latest('tanggal')
            ->latest('waktu_ci')
            ->take(5)
            ->get();

        // Kirim semua data status dan jadwal ke view
        return view('karyawan.presensi.index', compact('history', 'isCiDone', 'isCoDone', 'presensiHariIni', 'shiftEnd'));
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

        // 2. Ambil Jadwal Kerja Dinamis (Logika yang sama dengan index)
        $shiftStart = Carbon::today()->setTime(8, 30, 0);
        $shiftEnd = Carbon::today()->setTime(10, 14, 0);

        $jadwalKaryawan = JadwalKaryawan::where('id_karyawan', $karyawanId)->first();

        if ($jadwalKaryawan) {
            $detailJadwal = DetailJadwal::where('id_jadwal_kerja', $jadwalKaryawan->id_jadwal_kerja)
                ->where('hari', $todayName)
                ->first();

            if ($detailJadwal && $detailJadwal->hari_kerja == 1) {
                $shiftStart = Carbon::parse($todayDate . ' ' . $detailJadwal->jam_masuk);
                $shiftEnd = Carbon::parse($todayDate . ' ' . $detailJadwal->jam_pulang);
            }
        }

        // 3. Validasi Input
        $request->validate([
            'image' => 'required',
            'latitude' => 'required|numeric|not_in:0',
            'longitude' => 'required|numeric|not_in:0',
        ], [
            'latitude.not_in' => 'Gagal menyimpan data lokasi. Coba lagi GPS.',
            'longitude.not_in' => 'Gagal menyimpan data lokasi. Coba lagi GPS.',
        ]);


        // 4. Proses Foto dan Simpan ke Storage
        $img = $request->image;
        $image_parts = explode(";base64,", $img);

        if (count($image_parts) < 2) {
             return back()->withErrors('Format gambar tidak valid.');
        }

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $karyawanId . '_' . $currentTime->format('Ymd_His') . '.png';
        Storage::disk('public')->put($folderPath . $fileName, $image_base64);


        // 5. Tentukan Mode: Check-In (CI) atau Check-Out (CO)
        $presensiHariIni = PresensiKaryawan::where('karyawan_id', $karyawanId)
            ->where('tanggal', $todayDate)
            ->first();

        $presensi = null;

        if (!$presensiHariIni) {
            // --- LOGIKA CHECK-IN (CI) ---

            // Tentukan Status CI: Terlambat (2) atau Tepat Waktu (1)
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

            // VALIDASI WAKTU CO: Block jika Check-Out terlalu cepat
            if ($currentTime->lessThan($shiftEnd)) {
                // Hapus foto yang baru diunggah untuk CO yang gagal
                Storage::disk('public')->delete($folderPath . $fileName);
                return redirect()->route('karyawan.presensi.index')
                    ->withErrors('Check-Out ditolak. Anda hanya diizinkan Check-Out pada atau setelah ' . $shiftEnd->format('H:i') . ' sesuai jadwal.');
            }

            // Cek Status CO: Terlambat Check-Out (3)
            $statusId = $presensiHariIni->status_presensi_id;
            if ($currentTime->greaterThan($shiftEnd->copy()->addMinutes(15))) {
                $statusId = 3; // 3: Terlambat Check-Out (Jika lebih dari toleransi)
            }

            $presensiHariIni->update([
                'waktu_co' => $currentTime->toDateTimeString(),
                'foto_co' => $folderPath . $fileName,
                'latitude_co' => $request->latitude,
                'longitude_co' => $request->longitude,
                'status_presensi_id' => $statusId,
            ]);

            $presensi = $presensiHariIni;

        } else {
            // Sudah CI dan sudah CO.
            // Hapus foto yang baru diunggah
            Storage::disk('public')->delete($folderPath . $fileName);
            return redirect()->route('karyawan.dashboard')->with('error', 'Anda sudah menyelesaikan presensi hari ini.');
        }

        // 6. Redirect ke halaman konfirmasi foto
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
