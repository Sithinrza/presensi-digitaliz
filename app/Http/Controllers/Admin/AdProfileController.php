<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Karyawan;
use App\Models\PendidikanTerakhir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdProfileController extends Controller
{
   public function index(){
         $user = Auth::user();

        $karyawan = $user->karyawan;
        return view('admin.profile.users', compact('user', 'karyawan'));
    }


    public function detail(){
        $user = Auth::user();

        $karyawan = $user->karyawan->load([
            'agama',
            'jabatan',
            'divisi',
            'posisi',
            'pendidikanTerakhir'
        ]);

        $divisis = Divisi::all();
        $pendidikans = PendidikanTerakhir::all();

        return view('admin.profile.detail', compact('user', 'karyawan', 'divisis', 'pendidikans'));
    }

    public function edit(Karyawan $karyawan){

        $divisis = Divisi::all();
        $pendidikans = PendidikanTerakhir::all();

        $user = $karyawan->user;

        $karyawan->load([
            'user',
            'agama',
            'jabatan',
            'divisi',
            'posisi',
            'pendidikan_terakhir'
        ]);


        return view('admin.profile.detail', compact(
            'user',
            'karyawan',
            'divisis',
            'pendidikans'
        ));
    }


    public function update(Request $request)
    {

        $karyawan = Auth::user()->karyawan;

        $request->validate([
        ]);

        DB::beginTransaction();
        try {
      
            $karyawan->update([
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'pendidikan_terakhir_id' => $request->pendidikan_terakhir_id,
            ]);

            DB::commit();

            return redirect()->route('admin.profile.detail')
                             ->with('success', 'Profile berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan perubahan: ' . $e->getMessage())->withInput();
        }
    }
}
