<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyReport;
use Carbon\Carbon;

class AdDailyReportController extends Controller
{
    public function index(Request $request)
    {
        $tanggalFilter = $request->input('tanggal');
        $searchKaryawan = $request->input('search');

        $reportsQuery = DailyReport::with(['employee', 'attachments'])
            ->join('users', 'daily_reports.employee_id', '=', 'users.id')
            ->select('daily_reports.*');

        if ($tanggalFilter) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $tanggalFilter);
                $reportsQuery->whereDate('daily_reports.report_date', $date->toDateString());
            } catch (\Exception $e) {}
        }

        if ($searchKaryawan) {
            $reportsQuery->where(function ($query) use ($searchKaryawan) {
                $query->where('users.name', 'like', '%' . $searchKaryawan . '%');
            });
        }

        $reports = $reportsQuery->orderBy('daily_reports.report_date', 'desc')->paginate(10);

        return view('admin.report.index', [
            'reports' => $reports,
            'selected_date' => $tanggalFilter,
            'search_query' => $searchKaryawan,
        ]);
    }

        public function approve($id)
    {
        DailyReport::findOrFail($id)->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Laporan berhasil disetujui');
    }

    public function reject($id)
    {
        DailyReport::findOrFail($id)->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Laporan berhasil ditolak');
    }

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,approved,rejected',
    ]);

    $report = DailyReport::findOrFail($id);

    $report->update([
        'status' => $request->status,
    ]);

    return back()->with('success', 'Status laporan diperbarui');
}

}
