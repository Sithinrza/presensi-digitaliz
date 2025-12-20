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
        Carbon::setLocale('id');
        $now = now();

        $inputTanggal = trim($request->input('tanggal', $now->format('d/m/Y')));
        $filterNama = $request->input('nama_karyawan');

        $queryTanggal = $now->toDateString();
        $displayDate = $now->translatedFormat('l, d F Y');

        $isFilterSuccessful = false;

        if (Carbon::hasFormat($inputTanggal, 'd/m/Y')) {
            try {
                $dateObj = Carbon::createFromFormat('d/m/Y', $inputTanggal);

                $queryTanggal = $dateObj->toDateString();
                $displayDate = $dateObj->translatedFormat('l, d F Y');
                $isFilterSuccessful = true;

            } catch (\Exception $e) {

            }
        }

        if (!$isFilterSuccessful) {

             if ($inputTanggal !== $now->format('d/m/Y')) {
                $queryTanggal = '9999-01-01';

                $displayDate = "Tanggal: " . $inputTanggal;

             } else {
                 $queryTanggal = $now->toDateString();
                 $displayDate = $now->translatedFormat('l, d F Y');
             }
        }

        Log::info('LOG HARIAN FINAL DEBUG:');
        Log::info('1. Input Tanggal (dari URL): ' . $inputTanggal);
        Log::info('2. Tanggal untuk Query DB: ' . $queryTanggal);
        Log::info('3. Tanggal Tampilan (Di Halaman): ' . $displayDate);

        $logs = LogHarian::with(['presensi.karyawan.user'])
            ->whereHas('presensi', function ($query) use ($queryTanggal) {
                $query->where('tanggal', $queryTanggal);
            })
            ->when($filterNama, function ($query, $filterNama) {
                $query->whereHas('presensi.karyawan.user', function ($subQuery) use ($filterNama) {
                    $subQuery->where('name', 'like', '%' . $filterNama . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedLogs = $logs->groupBy(function ($item) {
            return $item->presensi->karyawan->id ?? null;
        });

        return view('admin.log.log', compact('groupedLogs', 'inputTanggal', 'displayDate', 'filterNama'));
    }
}
