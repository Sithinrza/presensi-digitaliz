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

        // Filter Tanggal (Asumsi input Blade default YYYY-MM-DD)
        if ($tanggalFilter) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $tanggalFilter);
                $reportsQuery->whereDate('daily_reports.report_date', $date->toDateString());
            } catch (\Exception $e) {}
        }

        // Filter Pencarian Karyawan
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
}
