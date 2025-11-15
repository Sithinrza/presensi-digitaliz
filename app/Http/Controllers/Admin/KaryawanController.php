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
use Carbon\Carbon;
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

    public function store(Request $request, Karyawan $karyawan)
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
            'tanggal_lahir' => 'nullable|date_format:d/m/Y',
            'no_telepon' => 'nullable|string|max:20',
            'jenis_kelamin'=>'required|in:Laki-Laki,Perempuan',
            'status_karyawan'=>'required|in:Aktif,Tidak Aktif',
            'tanggal_bergabung'=>'required|date_format:d/m/Y',

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

            $tglBergabung = Carbon::createFromFormat('d/m/Y', $request->tanggal_bergabung)->format('Y-m-d');

            // Ubah format input d-m-Y ke format database Y-m-d, atau null jika tidak diisi
            $tglLahir = $request->filled('tanggal_lahir')
                ? Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d')
                : null;

            Karyawan::create([
                'user_id' => $newUser->id,
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_bergabung' => $tglBergabung,
                'status_karyawan' => $request->status_karyawan,

                'foto_profil' => null,

                'agama_id' => $request->agama_id,
                'jabatan_id' => $request->jabatan_id,
                'divisi_id' => $request->divisi_id,
                'posisi_id' => $request->posisi_id,
                'pendidikan_terakhir_id' => $request->pendidikan_terakhir_id,

                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $tglLahir,
                'no_telepon' => $request->no_telepon,
            ]);

            DB::commit();

            return redirect()->route('admin.karyawan.index')
                             ->with('success', 'Karyawan baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            dd("Transaksi Gagal! Error: " . $e->getMessage(), $e->getTrace());
            // return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            //              ->withInput();
        }
    }

    public function show(Karyawan $karyawan)
    {
        $karyawan->load(
            'user',
            'jabatan',
            'divisi',
            'posisi',
            'agama',
            'pendidikanTerakhir');
        return view('admin.karyawan.detail', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        // 1. Ambil semua data master untuk dropdown (sama seperti fungsi create)
        $roles = Role::all();
        $agamas = Agama::all();
        $jabatans = Jabatan::all();
        $divisis = Divisi::all();
        $posisis = Posisi::all();
        $pendidikans = PendidikanTerakhir::all();

        $karyawan->load('user', 'user.roles');

        return view('admin.karyawan.update', compact(
            'karyawan',
            'roles',
            'agamas',
            'jabatans',
            'divisis',
            'posisis',
            'pendidikans'
        ));
    }

   public function update(Request $request, Karyawan $karyawan)
{
    $request->validate([
        // 'nip' => 'required|string|unique:karyawans,nip,'.$karyawan->id,
        // 'nama_lengkap' => 'nullable|string', // HAPUS KOMENTAR INI
        // 'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',

        'tanggal_bergabung' => 'required|date_format:d/m/Y',
        'alamat' => 'required|string', // HARUS DIVALIDASI

        // 'tempat_lahir' => 'nullable|string|max:100',
        //'tanggal_lahir' => 'nullable|date_format:d/m/Y',
        'no_telepon' => 'required|string|max:20',
        'status_karyawan'=>'required|in:Aktif,Tidak Aktif',

        // 'email' => 'required|email|unique:users,email,'.$karyawan->user_id,
        // 'password' => 'nullable|string|min:8',
        // 'role_name' => 'required|string|exists:roles,name',

        'agama_id' => 'required|exists:agamas,id',
        'jabatan_id' => 'required|exists:jabatans,id',
        'divisi_id' => 'required|exists:divisis,id',
        'posisi_id' => 'required|exists:posisis,id',
        'pendidikan_terakhir_id' => 'required|exists:pendidikan_terakhirs,id',
    ]);
    $selectedRole = Role::where('name', $request->role_name)->first();
    // if (!$selectedRole) { return back()->with('error', 'Role tidak valid.'); }

    DB::beginTransaction();

    try {
        
       $tglBergabung = Carbon::createFromFormat('d/m/Y', $request->tanggal_bergabung)->format('Y-m-d');

            // Ubah format input d-m-Y ke format database Y-m-d, atau null jika tidak diisi
        // $tglLahir = $request->filled('tanggal_lahir')
        //     ? Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d')
        //     : null;


        $userData = [];

        if ($request->has('nama_lengkap')) {
            $userData['name'] = $request->nama_lengkap;
        }
        if ($request->has('email')) {
            $userData['email'] = $request->email;
        }
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Jalankan update jika ada data yang akan di-update
        if (!empty($userData)) {
            $karyawan->user->update($userData);
        }

        // UPDATE ROLE (Hanya jika 'role_name' dikirim dan valid)
        if ($request->has('role_name') && $selectedRole) {
            $karyawan->user->roles()->sync([$selectedRole->id]);
        }
        // Jika Anda tidak mengupdate role, baris di atas harus di-skip.


        // 2. UPDATE DATA KARYAWAN
        $karyawanData = [
            // Field yang selalu di-update karena REQUIRED atau pasti diproses:
            'tanggal_bergabung' => $tglBergabung,
            'no_telepon' => $request->no_telepon,
            'status_karyawan' => $request->status_karyawan,
            'alamat' => $request->alamat,
            //'tanggal_lahir' => $tglLahir,

            // Foreign Keys (FK)
            'agama_id' => $request->agama_id,
            'jabatan_id' => $request->jabatan_id,
            'divisi_id' => $request->divisi_id,
            'posisi_id' => $request->posisi_id,
            'pendidikan_terakhir_id' => $request->pendidikan_terakhir_id,
        ];

        // Field yang optional/dikomentari di validasi, HANYA di-update jika field itu ada di request:
        if ($request->has('nip')) { $karyawanData['nip'] = $request->nip; }
        if ($request->has('nama_lengkap')) { $karyawanData['nama_lengkap'] = $request->nama_lengkap; }
        if ($request->has('jenis_kelamin')) { $karyawanData['jenis_kelamin'] = $request->jenis_kelamin; }
        if ($request->has('tempat_lahir')) { $karyawanData['tempat_lahir'] = $request->tempat_lahir; }


        $karyawan->update($karyawanData);

        DB::commit();

        return redirect()->route('admin.karyawan.index')
                         ->with('success', 'Data karyawan '.$karyawan->nama_lengkap.' berhasil diperbarui.');

    } catch (\Exception $e) {
        DB::rollBack();
        dd("Update Gagal! Error: " . $e->getMessage(), $e->getTrace());
    }
}

    public function destroy(Karyawan $karyawan)
    {
        DB::beginTransaction();

        try {
            if ($karyawan->user) {
                $karyawan->user->delete();
            }
            $karyawan->delete();

            DB::commit();

            return redirect()->route('admin.karyawan.index')
                             ->with('success', 'Data karyawan dan akun login berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

}
