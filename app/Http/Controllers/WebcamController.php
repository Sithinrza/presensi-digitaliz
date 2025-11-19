<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User; // Digunakan untuk mencari data user
use App\Models\PresensiKaryawan; // Asumsi model presensi Anda
use Illuminate\Support\Facades\Auth; // Wajib untuk sistem presensi
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // Untuk manajemen waktu

class WebcamController extends Controller
{
    /**
     * Menampilkan halaman utama kamera untuk Check-In.
     * Route: GET /webcam (webcam.index)
     */
    public function index()
    {
        // Ganti dengan data user yang sedang login
        $user = Auth::user();
        // return view('webcam', compact('user'));
        return view('karyawan.presensi.index');
    }

    /**
     * Menyimpan data presensi dan foto ke database.
     * Route: POST /webcam-store (webcam.store)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Tambahkan validasi lain sesuai kebutuhan)
        $request->validate([
            'image' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            // Pastikan data lokasi dan foto ada
        ]);

        // Ganti dengan ID user yang sedang login
        $karyawanId = Auth::id() ?? 99; // Contoh: Gunakan ID 99 jika Auth belum diatur
        $currentTime = Carbon::now();
        $folderPath = "presensi_photos/"; // Folder yang lebih spesifik

        // 2. Proses Gambar Base64
        $img = $request->image;
        $image_parts = explode(";base64,", $img);

        if (count($image_parts) < 2) {
             return back()->withErrors('Format gambar tidak valid.');
        }

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $karyawanId . '_' . $currentTime->format('Ymd_His') . '.png';

        // 3. Simpan Foto ke Storage (disk 'public')
        Storage::disk('public')->put($folderPath . $fileName, $image_base64);

        // 4. Tentukan Status Presensi (Logika Bisnis - Perlu disempurnakan)
        // Asumsi jam kerja mulai 08:30:00
        $shiftStart = Carbon::today()->setTime(8, 30, 0);

        if ($currentTime->greaterThan($shiftStart)) {
            // Terlambat Check-In (ID 2 di tabel status_presensi)
            $statusId = 2;
        } else {
            // Tepat Waktu (ID 1 di tabel status_presensi)
            $statusId = 1;
        }

        // 5. Simpan Data ke Tabel PresensiKaryawan
        // Catatan: Ini adalah logika Check-In (CI). Jika ada data presensi hari ini, harusnya ini adalah Check-Out (CO).

        $presensi = PresensiKaryawan::create([
            'id_karyawan' => $karyawanId,
            'status_presensi_id' => $statusId,
            'tanggal' => $currentTime->toDateString(),
            'waktu_ci' => $currentTime->toTimeString(),
            'foto_ci' => $folderPath . $fileName,
            'latitude_ci' => $request->latitude,
            'longitude_ci' => $request->longitude,
            // Kolom Check-Out (waktu_co, foto_co, dll.) dikosongkan (NULL)
        ]);

        // 6. Redirect ke halaman konfirmasi foto
        // Jika route 'photo' tidak didefinisikan, ini akan gagal. PASTIKAN ROUTE TERDEFINISI.
        return redirect()->route('photo', ['id' => $presensi->id]);
    }


    public function photo($id)
    {
        // Ambil data presensi yang baru disimpan
        $presensi = PresensiKaryawan::findOrFail($id);

        // Ganti dengan data user yang sedang login
        $user = User::where('id', $presensi->id_karyawan)->first();

        return view('photo', compact('presensi', 'user'));
    }

}
