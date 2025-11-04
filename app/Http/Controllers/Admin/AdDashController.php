<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class AdDashController extends Controller
{
     public function index(){

        $totalKaryawan = Karyawan::count();
        return view('admin.dashboard', compact('totalKaryawan'));
    }
}
