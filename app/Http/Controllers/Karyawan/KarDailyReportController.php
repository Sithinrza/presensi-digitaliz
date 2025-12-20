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
    public function index(Request $request)
    {
        $employeeId = Auth::id();
        $tanggalFilter = $request->input('tanggal');

        $reportsQuery = DailyReport::with('attachments')
            ->where('employee_id', $employeeId);

        // Logika Filter
        if ($tanggalFilter && $tanggalFilter !== 'all') {
            try {

                $date = Carbon::createFromFormat('d/m/Y', $tanggalFilter);

                $reportsQuery->whereDate('report_date', $date->toDateString());
            } catch (\Exception $e) {

            }
        }

        // Urutkan berdasarkan tanggal terbaru
        $reports = $reportsQuery->orderBy('report_date', 'desc')->paginate(10);

        return view('karyawan.report.index', [
            'reports' => $reports,
            'selected_date' => $tanggalFilter,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'link' => 'nullable|url|max:255',
            'file' => 'nullable|file|max:5120', // Maks 5MB
        ]);


        $report = DailyReport::create([
            'employee_id' => Auth::id(),
            'title' => $request->judul,
            'description' => $request->deskripsi,
            'report_date' => now(),
        ]);

        if ($request->filled('link')) {
            $report->attachments()->create([
                'type' => 'link',
                'url_or_path' => $request->link,
                'filename' => null,
            ]);
        }


        if ($request->hasFile('file')) {
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


        $report->update([
            'title' => $request->judul_edit,
            'description' => $request->deskripsi_edit,
        ]);

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
            $report->attachments()->where('type', 'link')->delete();
        }

        if ($request->hasFile('file_edit')) {

            $fileAttachment = $report->attachments()->where('type', 'file')->first();
            if ($fileAttachment) {
                Storage::disk('public')->delete($fileAttachment->url_or_path);
                $fileAttachment->delete();
            }

            $path = $request->file('file_edit')->store('reports', 'public');
            $report->attachments()->create([
                'type' => 'file',
                'url_or_path' => $path,
                'filename' => $request->file('file_edit')->getClientOriginalName(),
            ]);
        }

        return redirect()->route('karyawan.report.index')->with('success', 'Laporan berhasil diubah!');
    }

    public function destroy(DailyReport $report)
    {
        if ($report->employee_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        foreach ($report->attachments()->where('type', 'file')->get() as $attachment) {
            Storage::disk('public')->delete($attachment->url_or_path);
        }

        $report->delete();

        return redirect()->route('karyawan.report.index')->with('success', 'Laporan berhasil dihapus!');
    }
}
