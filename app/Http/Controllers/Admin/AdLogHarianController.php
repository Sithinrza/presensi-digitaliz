<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdLogHarianController extends Controller
{
    public function index(){
        return view('admin.log.log');
    }
}
