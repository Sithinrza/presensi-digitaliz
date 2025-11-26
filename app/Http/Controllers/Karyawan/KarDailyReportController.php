<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\DailyReportAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KarDailyReportController extends Controller
{
    // Tampilkan daftar laporan
    public function index(Request $request)
    {
        $employeeId = Auth::id(); // Ambil ID karyawan yang sedang login
        $tanggalFilter = $request->input('tanggal'); // Ambil filter tanggal dari URL

        $reportsQuery = DailyReport::with('attachments')
            ->where('employee_id', $employeeId);

        // Logika Filter
        if ($tanggalFilter && $tanggalFilter !== 'all') {
            try {
                // Konversi format tanggal dari 'd/m/Y' ke 'Y-m-d' untuk Query
                $date = Carbon::createFromFormat('d/m/Y', $tanggalFilter);

                $reportsQuery->whereDate('report_date', $date->toDateString());
            } catch (\Exception $e) {
                // Handle jika format tanggal tidak valid
            }
        }

        // Urutkan berdasarkan tanggal terbaru
        $reports = $reportsQuery->orderBy('report_date', 'desc')->paginate(10);

        // Data yang dilewatkan ke Blade
        return view('karyawan.report.index', [
            'reports' => $reports,
            'selected_date' => $tanggalFilter,
        ]);
    }

    // Simpan laporan baru (CREATE)
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'link' => 'nullable|url|max:255',
            'file' => 'nullable|file|max:5120', // Maks 5MB
        ]);

        // 1. Buat Laporan Utama
        $report = DailyReport::create([
            'employee_id' => Auth::id(),
            'title' => $request->judul,
            'description' => $request->deskripsi,
            'report_date' => now(),
        ]);

        // 2. Simpan Lampiran Tautan
        if ($request->filled('link')) {
            $report->attachments()->create([
                'type' => 'link',
                'url_or_path' => $request->link,
                'filename' => null,
            ]);
        }

        // 3. Simpan Lampiran File
        if ($request->hasFile('file')) {
            // Simpan file di storage/app/public/reports/
            $path = $request->file('file')->store('reports', 'public');

            $report->attachments()->create([
                'type' => 'file',
                'url_or_path' => $path,
                'filename' => $request->file('file')->getClientOriginalName(),
            ]);
        }

        return redirect()->route('karyawan.report.index')->with('success', 'Laporan berhasil dibuat!');
    }

    public function update(Request $request, DailyReport $report)
    {
        if ($report->employee_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'judul_edit' => 'required|string|max:150',
            'deskripsi_edit' => 'required|string',
            'link_edit' => 'nullable|url|max:255',
            'file_edit' => 'nullable|file|max:5120', // Maks 5MB
            'attachment_link_id' => 'nullable|exists:daily_report_attachments,id',
            'attachment_file_id' => 'nullable|exists:daily_report_attachments,id',
        ]);

        // 1. Update Laporan Utama
        $report->update([
            'title' => $request->judul_edit,
            'description' => $request->deskripsi_edit,
        ]);

        // 2. Update/Buat Lampiran Tautan
        if ($request->filled('link_edit')) {
            $linkAttachment = $report->attachments()->where('type', 'link')->first();
            if ($linkAttachment) {
                $linkAttachment->update(['url_or_path' => $request->link_edit]);
            } else {
                $report->attachments()->create([
                    'type' => 'link',
                    'url_or_path' => $request->link_edit,
                ]);
            }
        } else {
            // Hapus link jika dihilangkan
            $report->attachments()->where('type', 'link')->delete();
        }

        // 3. Update/Buat Lampiran File
        if ($request->hasFile('file_edit')) {
            // Hapus file lama jika ada
            $fileAttachment = $report->attachments()->where('type', 'file')->first();
            if ($fileAttachment) {
                Storage::disk('public')->delete($fileAttachment->url_or_path);
                $fileAttachment->delete();
            }

            // Simpan file baru
            $path = $request->file('file_edit')->store('reports', 'public');
            $report->attachments()->create([
                'type' => 'file',
                'url_or_path' => $path,
                'filename' => $request->file('file_edit')->getClientOriginalName(),
            ]);
        }
        // Catatan: Jika tidak ada file baru dan input file kosong, file lama tetap ada (kecuali ada tombol hapus file terpisah)

        return redirect()->route('karyawan.report.index')->with('success', 'Laporan berhasil diubah!');
    }

    // Hapus laporan (DELETE)
    public function destroy(DailyReport $report)
    {
        // Pastikan karyawan yang login adalah pemilik laporan
        if ($report->employee_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Hapus file lampiran dari storage sebelum menghapus record
        foreach ($report->attachments()->where('type', 'file')->get() as $attachment) {
            Storage::disk('public')->delete($attachment->url_or_path);
        }

        $report->delete();

        return redirect()->route('karyawan.report.index')->with('success', 'Laporan berhasil dihapus!');
    }
}
