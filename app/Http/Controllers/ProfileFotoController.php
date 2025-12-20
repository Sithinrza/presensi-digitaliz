<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileFotoController extends Controller
{
    public function update(Request $request)

    {


        $request->validate([
            'foto_profil' => 'required|image|max:2048',
        ]);

        $user = Auth::user();

        if (!$user->karyawan) {
            abort(403);
        }

        $karyawan = $user->karyawan;

        if ($karyawan->foto_profil && Storage::disk('public')->exists($karyawan->foto_profil)) {
            Storage::disk('public')->delete($karyawan->foto_profil);
        }

        $path = $request->file('foto_profil')->store('foto-profil', 'public');

        $karyawan->update([
            'foto_profil' => $path
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function delete()
    {
        $user = Auth::user();

        if (!$user->karyawan) {
            abort(403);
        }

        $karyawan = $user->karyawan;

        if ($karyawan->foto_profil && Storage::disk('public')->exists($karyawan->foto_profil)) {
            Storage::disk('public')->delete($karyawan->foto_profil);
        }

        $karyawan->update([
            'foto_profil' => null
        ]);

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}
