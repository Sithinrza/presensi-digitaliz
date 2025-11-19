<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogHarian;
use App\Models\PresensiKaryawan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdLogHarianController extends Controller
{
    public function index(Request $request)
    {
        // Set locale Indonesia
        Carbon::setLocale('id');
        $now = now(); // Ambil waktu saat ini

        // 1. Ambil input tanggal. Default ke format D/M/Y (sesuai Flatpickr)
        $inputTanggal = trim($request->input('tanggal', $now->format('d/m/Y')));
        $filterNama = $request->input('nama_karyawan');

        // Inisialisasi default berdasarkan NOW()
        $queryTanggal = $now->toDateString(); // Default: YYYY-MM-DD hari ini
        $displayDate = $now->translatedFormat('l, d F Y'); // Default: Nama hari, tanggal bulan tahun

        $isFilterSuccessful = false;

        // 2. Cek dan Konversi input tanggal (D/M/Y)
        if (Carbon::hasFormat($inputTanggal, 'd/m/Y')) {
            try {
                // Konversi string input D/M/Y menjadi objek Carbon
                $dateObj = Carbon::createFromFormat('d/m/Y', $inputTanggal);

                $queryTanggal = $dateObj->toDateString(); // Nilai ini yang dicari di DB (Y-m-d)
                $displayDate = $dateObj->translatedFormat('l, d F Y'); // Nilai ini yang ditampilkan di header
                $isFilterSuccessful = true;

            } catch (\Exception $e) {
                // Biarkan default value hari ini tetap digunakan jika parsing gagal
            }
        }

        // --- LOGIKA PENTING: JIKA PARSING GAGAL DAN TANGGAL BUKAN HARI INI ---
        if (!$isFilterSuccessful) {
             // Jika input gagal di-parse, kita tidak tahu tanggal pastinya.
             // Untuk menghindari menampilkan data hari ini (now()) ketika user memilih tanggal lain:

             // Jika user memasukkan sesuatu yang BUKAN hari ini, dan parsing gagal,
             // kita paksa query menjadi string 'kosong' agar hasil query 0.
             if ($inputTanggal !== $now->format('d/m/Y')) {
                $queryTanggal = '9999-01-01'; // Dipaksa kosong

                // Coba tampilkan tanggal input user di header, jika memungkinkan
                $displayDate = "Tanggal: " . $inputTanggal;

                // Jika input tidak valid dan bukan hari ini, kita tidak akan menganggapnya sebagai filter sukses.
             } else {
                 // Jika input gagal di-parse TAPI nilainya adalah hari ini, kita pertahankan default now()
                 $queryTanggal = $now->toDateString();
                 $displayDate = $now->translatedFormat('l, d F Y');
             }
        }

        // <<< DEBUGGING LOG SEMENTARA >>>
        // Cek log di storage/logs/laravel.log setelah Anda klik tombol filter
        Log::info('LOG HARIAN FINAL DEBUG:');
        Log::info('1. Input Tanggal (dari URL): ' . $inputTanggal);
        Log::info('2. Tanggal untuk Query DB: ' . $queryTanggal);
        Log::info('3. Tanggal Tampilan (Di Halaman): ' . $displayDate);
        // <<< END DEBUGGING LOG >>>


        // 3. Query Data Log Harian
        $logs = LogHarian::with(['presensi.karyawan.user'])
            ->whereHas('presensi', function ($query) use ($queryTanggal) {
                // Gunakan $queryTanggal (format YYYY-MM-DD) untuk query DB
                $query->where('tanggal', $queryTanggal);
            })
            ->when($filterNama, function ($query, $filterNama) {
                $query->whereHas('presensi.karyawan.user', function ($subQuery) use ($filterNama) {
                    $subQuery->where('name', 'like', '%' . $filterNama . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. KELOMPOKKAN LOG BERDASARKAN KARYAWAN (ID Karyawan)
        $groupedLogs = $logs->groupBy(function ($item) {
            // Pastikan relasi presensi dan karyawan ada sebelum mengakses id
            return $item->presensi->karyawan->id ?? null;
        });

        // Kirim data yang sudah dikelompokkan
        return view('admin.log.log', compact('groupedLogs', 'inputTanggal', 'displayDate', 'filterNama'));
    }
}
