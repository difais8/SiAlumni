<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RiwayatPekerjaanController extends Controller
{
    public function create()
    {
        // Cek layout (Admin atau Alumni)
        $user = Auth::user();
        $layout = (Auth::user()->role == 'pengelola') ? 'layouts.admin' : 'layouts.alumni';

        return view('profile.riwayat-pekerjaan.create', compact('layout'));
    }

    /**
     * Menyimpan data pekerjaan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tahun_mulai' => 'required|integer|digits:4|min:1900|max:' . (date('Y')),
            'tahun_selesai' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y')) . '|gte:tahun_mulai',
        ]);

        RiwayatPekerjaan::create([
            'user_id' => Auth::id(), // PENTING: Pastikan ID user diambil dari Auth
            'nama_perusahaan' => $request->nama_perusahaan,
            'jabatan' => $request->jabatan,
            'tahun_mulai' => $request->tahun_mulai,
            'tahun_selesai' => $request->tahun_selesai,
        ]);

        return redirect()->route('profile.index')->with('success', 'Riwayat pekerjaan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(RiwayatPekerjaan $pekerjaan)
    {
        // Keamanan: Pastikan user hanya bisa edit punya sendiri
        if ($pekerjaan->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $layout = (Auth::user()->role == 'pengelola') ? 'layouts.admin' : 'layouts.alumni';

        return view('profile.riwayat-pekerjaan.edit', compact('pekerjaan', 'layout'));
    }

    /**
     * Update data.
     */
    public function update(Request $request, RiwayatPekerjaan $pekerjaan)
    {
        if ($pekerjaan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tahun_mulai' => 'required|integer|digits:4',
            'tahun_selesai' => 'nullable|integer|digits:4|gte:tahun_mulai',
        ]);

        $pekerjaan->update($request->all());

        return redirect()->route('profile.index')->with('success', 'Riwayat pekerjaan diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy(RiwayatPekerjaan $pekerjaan)
    {
        if ($pekerjaan->user_id !== Auth::id()) {
            abort(403);
        }

        $pekerjaan->delete();

        return redirect()->route('profile.index')->with('success', 'Riwayat pekerjaan dihapus.');
    }
}
