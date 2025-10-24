<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agama;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\PendidikanTerakhir;
use App\Models\Posisi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{

    public function index()
    {
        $karyawans = Karyawan::with('user')->latest()->get();
        return view('admin.karyawan.index', compact('karyawans'));
    }

    public function create()
    {

        $roles = Role::all();
        $agamas = Agama::all();
        $jabatans = Jabatan::all();
        $divisis = Divisi::all();
        $posisis = Posisi::all();
        $pendidikans = PendidikanTerakhir::all();

        return view('admin.karyawan.create', compact(
            'roles',
            'agamas',
            'jabatans',
            'divisis',
            'posisis',
            'pendidikans'
        ));
    }

    public function store(Request $request)
    {

        $request->validate([
            //table users
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:8',
            'role_name' => 'required|string|exists:roles,name',

            //table karyawana
            'nip'=>'required|string|unique:karyawans,nip',
            'nama_lengkap'=>'required|string',
            'alamat' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'no_telepon' => 'nullable|string|max:20',
            'jenis_kelamin'=>'required|in:Laki-Laki,Perempuan',
            'status_karyawan'=>'required|in:Aktif,Tidak Aktif',
            'tanggal_bergabung'=>'required|date',


            //dropdown fk
            'agama_id' => 'required|exists:agamas,id',
            'jabatan_id' => 'required|exists:jabatans,id',
            'divisi_id' => 'required|exists:divisis,id',
            'posisi_id' => 'required|exists:posisis,id',
            'pendidikan_terakhir_id' => 'required|exists:pendidikan_terakhirs,id',
        ]);

        $selectedRole = Role::where('name', $request->role_name)->first();

        if (!$selectedRole) {
            return back()->with('error', 'Role yang dipilih tidak valid.');
        }

        DB::beginTransaction();

        try {
            $newUser = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $newUser->roles()->attach($selectedRole->id);

            Karyawan::create([
                'user_id' => $newUser->id,
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_bergabung' => $request->tanggal_bergabung,
                'status_karyawan' => $request->status_karyawan,

                'agama_id' => $request->agama_id,
                'jabatan_id' => $request->jabatan_id,
                'divisi_id' => $request->divisi_id,
                'posisi_id' => $request->posisi_id,
                'pendidikan_terakhir_id' => $request->pendidikan_terakhir_id,

                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_telepon' => $request->no_telepon,
            ]);

            DB::commit();

            return redirect()->route('admin.karyawan.index')
                             ->with('success', 'Karyawan baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                         ->withInput();
        }
    }

}
