<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdReportController extends Controller
{
    public function index(){
        return view('admin.report.index');
    }

    public function show(){
        return view('admin.report.show');
    }
}
