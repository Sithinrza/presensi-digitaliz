<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KarReportController extends Controller
{
    public function index(){
        return view('karyawan.report.index');
    }

    public function show(){
        return view('karyawan.report.show');
    }
}
