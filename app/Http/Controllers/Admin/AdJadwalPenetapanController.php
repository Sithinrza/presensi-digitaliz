<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\JadwalKerja;
use App\Models\JadwalKaryawan;

class AdJadwalPenetapanController extends Controller
{
    /**
     * Tampilkan halaman master template + penetapan karyawan
     */
    public function index()
{
    try {
        // Semua template jadwal kerja
        $jadwalKerjas = JadwalKerja::with('detailJadwals')->get();

        // Semua karyawan
        $karyawans = Karyawan::with('jabatan')->get(); // cukup jabatan

        // Semua penetapan jadwal aktif
        $penetapanJadwals = JadwalKaryawan::with(['karyawan', 'jadwalKerja'])->get();

    } catch (\Exception $e) {
        $jadwalKerjas = collect();
        $karyawans = collect();
        $penetapanJadwals = collect();

        return view('admin.jadwal.index', compact('jadwalKerjas', 'karyawans', 'penetapanJadwals'))
            ->with('error', 'Gagal memuat data: '.$e->getMessage());
    }

    // Panggil view dari jadwal.index tapi URL tetap /admin/penetapan
    return view('admin.jadwal.index', compact('jadwalKerjas', 'karyawans', 'penetapanJadwals'));
}


    /**
     * Simpan relasi baru (assign jadwal ke karyawan)
     */
    // app/Http/Controllers/Admin/PenetapanController.php (atau Controller yang relevan)

public function store(Request $request)
{
    // Gunakan validate() untuk mendapatkan data yang sudah divalidasi
    $validatedData = $request->validate([
        'id_karyawan' => 'required|exists:karyawans,id',
        'id_jadwal_kerja' => 'required|exists:jadwal_kerjas,id',
    ]);

    // 1. Cek Duplikasi: Cari apakah id_karyawan sudah ada di tabel JadwalKaryawan
    $exists = JadwalKaryawan::where('id_karyawan', $validatedData['id_karyawan'])->exists();

    if ($exists) {
        // Jika sudah ada, kembalikan ke halaman sebelumnya dengan pesan error
        return redirect()->back()
            ->withInput($validatedData) // Pertahankan input form
            ->with('error', 'Karyawan ini sudah memiliki jadwal penetapan. Gunakan halaman Edit untuk mengubahnya.');
    }

    // 2. Jika tidak ada duplikasi, lakukan CREATE (Simpan data baru)
    try {
        JadwalKaryawan::create($validatedData);

        // Arahkan ke index
        return redirect()->route('admin.penetapan.index')
             ->with('success', 'Penetapan jadwal baru berhasil disimpan.');

    } catch (\Exception $e) {
        // Tangani error database jika terjadi
        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
}
public function create()
{
    $karyawans = Karyawan::with('jabatan')->get();
    $jadwalKerjas = JadwalKerja::with('detailJadwals')->get();

    return view('admin.jadwal.penetapan.create', compact('karyawans','jadwalKerjas'));
}

public function edit(JadwalKaryawan $penetapan)
{
    $karyawans = Karyawan::with('jabatan')->get(); // sesuaikan relasi
    $jadwalKerjas = JadwalKerja::with('detailJadwals')->get();

    return view('admin.jadwal.penetapan.edit', compact('penetapan','karyawans','jadwalKerjas'));
}



    /**
     * Update relasi
     */
   public function update(Request $request, JadwalKaryawan $penetapan)
{
    $request->validate([
        'id_jadwal_kerja' => 'required|exists:jadwal_kerjas,id',
    ]);

    $penetapan->update(['id_jadwal_kerja' => $request->id_jadwal_kerja]);

    return redirect()->route('admin.jadwal.index')
        ->with('success', 'Relasi jadwal berhasil diperbarui.');
}

public function destroy(JadwalKaryawan $penetapan)
{
    try {
        $penetapan->delete();
        return back()->with('success', 'Relasi jadwal karyawan berhasil diputuskan.');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal memutuskan relasi: '.$e->getMessage());
    }
}

}
