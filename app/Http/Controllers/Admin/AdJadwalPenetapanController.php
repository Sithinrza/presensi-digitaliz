<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\JadwalKerja;
use App\Models\JadwalKaryawan;

class AdJadwalPenetapanController extends Controller
{
    public function index()
    {
        try {
            $jadwalKerjas = JadwalKerja::with('detailJadwals')->get();

            $karyawans = Karyawan::with('jabatan')->get();

            $penetapanJadwals = JadwalKaryawan::with(['karyawan', 'jadwalKerja'])->get();

        } catch (\Exception $e) {
            $jadwalKerjas = collect();
            $karyawans = collect();
            $penetapanJadwals = collect();

            return view('admin.jadwal.index', compact('jadwalKerjas', 'karyawans', 'penetapanJadwals'))
                ->with('error', 'Gagal memuat data: '.$e->getMessage());
        }

        return view('admin.jadwal.index', compact('jadwalKerjas', 'karyawans', 'penetapanJadwals'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_karyawan' => 'required|exists:karyawans,id',
            'id_jadwal_kerja' => 'required|exists:jadwal_kerjas,id',
        ]);

        $exists = JadwalKaryawan::where('id_karyawan', $validatedData['id_karyawan'])->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput($validatedData)
                ->with('error', 'Karyawan ini sudah memiliki jadwal penetapan. Gunakan halaman Edit untuk mengubahnya.');
        }

        try {
            JadwalKaryawan::create($validatedData);

            return redirect()->route('admin.penetapan.index')
                ->with('success', 'Penetapan jadwal baru berhasil disimpan.');

        } catch (\Exception $e) {
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
