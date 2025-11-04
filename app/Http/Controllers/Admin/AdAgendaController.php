<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Agenda;
use App\Models\Karyawan;
use App\Models\Divisi;
use Carbon\Carbon;

class AdAgendaController extends Controller
{
    /**
     * Tampilkan halaman daftar agenda.
     */
    public function index(Request $request){
        $filterDate = $request->input('tanggal');

        $query = Agenda::query();

        if ($filterDate && $filterDate !== 'all') {
            try {
                if (Carbon::hasFormat($filterDate, 'Y-m-d')) {
                    $query->whereDate('tanggal_agenda', $filterDate);
                }
            } catch (\Exception $e) {
            }
        }

        // Ambil data agenda
        $agendas = $query->orderBy('tanggal_agenda', 'desc')
                         ->orderBy('waktu_mulai', 'asc')
                         ->paginate(10);

        return view('admin.agenda.index', compact('agendas'));
    }

    public function create()
    {
        $divisis = Divisi::all();
        $karyawans = Karyawan::with('divisi')->get();

        return view('admin.agenda.create', compact('divisis', 'karyawans'));
    }

    /**
     * Simpan agenda baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            //'deskripsi' => 'required|string',
            'catatan' => 'nullable|string',
            'tanggal_agenda' => 'required|date_format:m/d/Y',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'lokasi_alamat' => 'nullable|string',
            'ruang' => 'nullable|string',

            // Validasi untuk Peserta (Array ID)
            'peserta_karyawan' => 'nullable|array',
            'peserta_divisi' => 'nullable|array',
        ]);

        if (empty($request->peserta_karyawan) && empty($request->peserta_divisi)) {
            return back()->with('error', 'Minimal satu Karyawan atau satu Divisi harus dipilih sebagai peserta.')->withInput();
        }


        DB::beginTransaction();

        try {
            $tanggal_agenda_db = Carbon::createFromFormat('m/d/Y', $request->tanggal_agenda)->format('Y-m-d');

            $agenda = Agenda::create([
                'judul' => $request->judul,
                //'deskripsi' => $request->deskripsi,
                'tanggal_agenda' => $tanggal_agenda_db,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'lokasi_alamat' => $request->lokasi_alamat,
                'ruang' => $request->ruang,
                'catatan' => $request->catatan,
            ]);

            $karyawanIds = collect([]);


            if ($request->has('peserta_karyawan') && !empty($request->peserta_karyawan)) {
                $karyawanIds = $karyawanIds->merge($request->peserta_karyawan);
            }

            // Ambil ID yang sudah dikumpulkan perorangan
            $excludedIds = $karyawanIds->unique()->all();

            // 4. LOGIKA PENUGASAN PESERTA PER DIVISI (PRIORITAS 2: EXCLUDE YANG SUDAH DIPILIH)
            if ($request->has('peserta_divisi') && !empty($request->peserta_divisi)) {
                $karyawanDivisiIds = Karyawan::whereIn('divisi_id', $request->peserta_divisi)
                                             ->whereNotIn('id', $excludedIds) // <<< KRITIS: EXCLUDE KARYAWAN PERORANGAN
                                             ->pluck('id');

                $karyawanIds = $karyawanIds->merge($karyawanDivisiIds);
            }

            // 5. Lampirkan (Attach) semua ID Karyawan ke Agenda (Tabel Pivot)
            if ($karyawanIds->isNotEmpty()) {
                $agenda->karyawans()->sync($karyawanIds->unique()->all());
            }
            DB::commit();

            return redirect()->route('admin.agenda.index')->with('success', 'Agenda berhasil dibuat dan peserta ditugaskan.');

        } catch (\Exception $e) {
            DB::rollBack();

            // throw $e;

            return back()->with('error', 'Gagal membuat agenda: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Agenda $agenda)
    {
        // 1. Ambil data master untuk dropdown
        $divisis = Divisi::all();
        // Load relasi user dan divisi untuk tampilan karyawan
        $karyawans = Karyawan::with('user', 'divisi')->get();

        // 2. Ambil ID Karyawan yang sudah ditugaskan (untuk pre-select)
        $selectedKaryawanIds = $agenda->karyawans->pluck('id')->toArray();

        // 3. Cari Divisi unik dari Karyawan yang ditugaskan (untuk pre-select)
        $selectedDivisiIds = Karyawan::whereIn('id', $selectedKaryawanIds)
                                     ->whereNotNull('divisi_id')
                                     ->pluck('divisi_id')
                                     ->unique()
                                     ->toArray();

        // Catatan: Jika ada masalah relasi user di Blade, pastikan Model Karyawan memiliki relasi 'user'.

        return view('admin.agenda.update', compact(
            'agenda',
            'divisis',
            'karyawans',
            'selectedKaryawanIds',
            'selectedDivisiIds'
        ));
    }

    /**
     * Menyimpan perubahan Agenda (UPDATE).
     */
    public function update(Request $request, Agenda $agenda)
    {
        // 1. Validasi Data

        $request->merge([
            'waktu_mulai' => $request->waktu_mulai === '' ? null : $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai === '' ? null : $request->waktu_selesai,
        ]);

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_agenda' => 'required|date_format:m/d/Y',
            'waktu_mulai' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
    'waktu_selesai' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],

            'lokasi_alamat' => 'nullable|string',
            'ruang' => 'nullable|string',
            'catatan' => 'nullable|string', // Hanya menggunakan 'catatan'

            // Validasi Peserta
            'peserta_karyawan' => 'nullable|array',
            'peserta_divisi' => 'nullable|array',
        ]);

        // Cek minimal peserta
        if (empty($request->peserta_karyawan) && empty($request->peserta_divisi)) {
            return back()->with('error', 'Minimal satu Karyawan atau satu Divisi harus dipilih sebagai peserta.')->withInput();
        }

        DB::beginTransaction();

        try {
            // KRITIS: Konversi format tanggal dari m/d/Y (input) ke Y-m-d (DB)
            $tanggal_agenda_db = Carbon::createFromFormat('m/d/Y', $request->tanggal_agenda)->format('Y-m-d');

            // 1. Update Entri Agenda Utama
            $agenda->update([
                'judul' => $request->judul,
                'tanggal_agenda' => $tanggal_agenda_db,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'lokasi_alamat' => $request->lokasi_alamat,
                'ruang' => $request->ruang,
                'catatan' => $request->catatan,
                // Kolom 'deskripsi' dihapus dari sini
            ]);

           $karyawanIds = collect([]);

            // 3. LOGIKA PENUGASAN PESERTA PERORANGAN (PRIORITAS 1)
            if ($request->has('peserta_karyawan') && !empty($request->peserta_karyawan)) {
                $karyawanIds = $karyawanIds->merge($request->peserta_karyawan);
            }

            // Ambil ID yang sudah dikumpulkan perorangan
            $excludedIds = $karyawanIds->unique()->all();

            // 4. LOGIKA PENUGASAN PESERTA PER DIVISI (PRIORITAS 2: EXCLUDE YANG SUDAH DIPILIH)
            if ($request->has('peserta_divisi') && !empty($request->peserta_divisi)) {
                $karyawanDivisiIds = Karyawan::whereIn('divisi_id', $request->peserta_divisi)
                                             ->whereNotIn('id', $excludedIds) // <<< KRITIS: EXCLUDE KARYAWAN PERORANGAN
                                             ->pluck('id');

                $karyawanIds = $karyawanIds->merge($karyawanDivisiIds);
            }

            // 5. Perbarui (Sync) tabel pivot
            $agenda->karyawans()->sync($karyawanIds->unique()->all());

            DB::commit();

            return redirect()->route('admin.agenda.index')->with('success', 'Agenda berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui agenda: ' . $e->getMessage())->withInput();
        }
    }


    public function destroy(Agenda $agenda)
    {
        DB::beginTransaction();

        try {
            // Hapus relasi pivot terlebih dahulu (Walaupun cascade delete di DB bisa mengurusnya, ini lebih aman di sisi aplikasi)
            $agenda->karyawans()->detach();

            // Hapus agenda utama
            $agenda->delete();

            DB::commit();

            return redirect()->route('admin.agenda.index')->with('success', 'Agenda berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Jika Anda mengaktifkan throw $e; di sini, Anda akan melihat error database (e.g., foreign key violation)
            return back()->with('error', 'Gagal menghapus agenda: ' . $e->getMessage());
        }
    }

}
