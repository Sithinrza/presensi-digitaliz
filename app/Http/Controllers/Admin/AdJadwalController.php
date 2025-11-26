<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalKerja;
use App\Models\DetailJadwal;
use App\Models\JadwalKaryawan;
use Carbon\Carbon;

class AdJadwalController extends Controller
{
    // LIST JADWAL
    public function index()
    {
        $jadwalKerjas = JadwalKerja::with('detailJadwals')->get();
        $penetapanJadwals = JadwalKaryawan::with(['karyawan', 'jadwalKerja'])->get();

        return view('admin.jadwal.index', compact('jadwalKerjas', 'penetapanJadwals'));
    }

    public function create()
    {
        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        return view('admin.jadwal.create', compact('days'));
    }

    public function store(Request $request)
    {
        // Validasi
       // dd($request->details);

       $request->validate([
            'name' => 'required|string|max:255|unique:jadwal_kerjas,name',
            'details' => 'required|array|size:6',

            'details.*.hari' => 'required|string',
            'details.*.jam_masuk' => 'nullable|date_format:H:i',
            'details.*.jam_pulang' => 'nullable|date_format:H:i',
        ]);


        try {
            DB::beginTransaction();

            // SIMPAN TEMPLATE
            $jadwal = JadwalKerja::create([
                'name' => $request->name
            ]);

            // SIMPAN DETAIL
            foreach ($request->details as $detail) {
                DetailJadwal::create([
                    'id_jadwal_kerja' => $jadwal->id,
                    'hari'            => $detail['hari'],
                    'hari_kerja'      => isset($detail['hari_kerja']) ? 1 : 0,
                    'jam_masuk'       => $detail['jam_masuk'] ?? null,
                    'jam_pulang'      => $detail['jam_pulang'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.jadwal.index')
                ->with('success', 'Template jadwal berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }


    public function edit($id)
    {
        $jadwal = JadwalKerja::with('detailJadwals')->findOrFail($id);

        $orderedDays = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $jadwal->detailJadwals = $jadwal->detailJadwals->sortBy(function($d) use ($orderedDays){
            return array_search($d->hari, $orderedDays);
        });

        return view('admin.jadwal.edit', compact('jadwal'));
    }


    public function update(Request $request, $id)
    {
        foreach ($request->jam_masuk as $key => $jam) {
            $request->merge([
                'jam_masuk.'.$key => $jam ? date('H:i', strtotime($jam)) : null,
                'jam_pulang.'.$key => $request->jam_pulang[$key] ? date('H:i', strtotime($request->jam_pulang[$key])) : null,
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'jam_masuk.*' => 'nullable|date_format:H:i',
            'jam_pulang.*' => 'nullable|date_format:H:i|after:jam_masuk.*',
        ]);

        $jadwal = JadwalKerja::findOrFail($id);
        $jadwal->update(['name' => $request->name]);

        foreach ($jadwal->detailJadwals as $detail) {
            $detailId = $detail->id;
            $isWorking = $request->has("hari_kerja.$detailId") ? 1 : 0;

            $detail->update([
                'hari_kerja'  => $isWorking,
                'jam_masuk'   => $isWorking ? $request->jam_masuk[$detailId] ?? null : null,
                'jam_pulang'  => $isWorking ? $request->jam_pulang[$detailId] ?? null : null,
            ]);
        }


        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Template jadwal berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $jadwal = JadwalKerja::findOrFail($id);
        DetailJadwal::where('id_jadwal_kerja', $id)->delete();
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Template jadwal berhasil dihapus.');
    }
}
