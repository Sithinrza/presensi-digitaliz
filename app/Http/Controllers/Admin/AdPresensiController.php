<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdPresensiController extends Controller
{
    public function index(){
        return view('admin.presensi.riwayat');
    }

    public function rekap(){
        return view('admin.presensi.rekap');
    }
    
    public function detail(){
        return view('admin.presensi.detail');
    }
}
