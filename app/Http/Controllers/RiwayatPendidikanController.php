<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPendidikanController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $layout = (Auth::user()->role == 'pengelola') ? 'layouts.admin' : 'layouts.alumni';

        return view('profile.riwayat-pendidikan.create', compact('layout'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'jenjang' => 'required|string|max:50',
            'jurusan' => 'nullable|string|max:255',
            'tahun_mulai' => 'required|integer|digits:4|min:1900|max:' . (date('Y')),
            'tahun_selesai' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 5) . '|gte:tahun_mulai',
        ]);

        RiwayatPendidikan::create([
            'user_id' => Auth::id(),
            'nama_institusi' => $request->nama_institusi,
            'jenjang' => $request->jenjang,
            'jurusan' => $request->jurusan,
            'tahun_mulai' => $request->tahun_mulai,
            'tahun_selesai' => $request->tahun_selesai,
        ]);

        return redirect()->route('profile.index')->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    public function edit(RiwayatPendidikan $pendidikan)
    {
        // Keamanan: Cek kepemilikan
        if ($pendidikan->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $layout = (Auth::user()->role == 'pengelola') ? 'layouts.admin' : 'layouts.alumni';

        return view('profile.riwayat-pendidikan.edit', compact('pendidikan', 'layout'));
    }

    public function update(Request $request, RiwayatPendidikan $pendidikan)
    {
        if ($pendidikan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'jenjang' => 'required|string|max:50',
            'jurusan' => 'nullable|string|max:255',
            'tahun_mulai' => 'required|integer|digits:4',
            'tahun_selesai' => 'nullable|integer|digits:4|gte:tahun_mulai',
        ]);

        $pendidikan->update($request->all());

        return redirect()->route('profile.index')->with('success', 'Riwayat pendidikan diperbarui.');
    }

    public function destroy(RiwayatPendidikan $pendidikan)
    {
        if ($pendidikan->user_id !== Auth::id()) {
            abort(403);
        }

        $pendidikan->delete();

        return redirect()->route('profile.index')->with('success', 'Riwayat pendidikan dihapus.');
    }
}