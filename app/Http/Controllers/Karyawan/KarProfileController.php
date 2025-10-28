<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KarProfileController extends Controller
{
    public function index(){
        return view('karyawan.users.users');
    }
}
