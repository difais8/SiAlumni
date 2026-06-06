<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use Illuminate\Http\Request;

class AngkatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semuaAngkatan = Angkatan::orderBy('tahun_masuk', 'desc')->get();

        // 3. Kirim data ke view
        return view('admin.angkatan.index', [
            'semuaAngkatan' => $semuaAngkatan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.angkatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk
        $request->validate([
            'jenjang' => 'required|string|max:50',
            'tahun_masuk' => 'required|integer|digits:4|min:1990|max:' . (date('Y') + 1),
            'nama_angkatan' => 'required|string|max:255',
        ]);

        // 2. Jika validasi lolos, buat data baru
        Angkatan::create([
            'jenjang' => $request->jenjang,
            'tahun_masuk' => $request->tahun_masuk,
            'nama_angkatan' => $request->nama_angkatan,
        ]);

        // 3. Alihkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.angkatan.index')
                        ->with('success', 'Data angkatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Angkatan $angkatan)
    {
        // Kirim data angkatan yang mau diedit ke view
        return view('admin.angkatan.edit', [
            'angkatan' => $angkatan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Angkatan $angkatan)
    {
        // 1. Validasi data
        $request->validate([
            'jenjang' => 'required|string|max:50',
            'tahun_masuk' => 'required|integer|digits:4|min:1990|max:' . (date('Y') + 1),
            'nama_angkatan' => 'required|string|max:255',
        ]);

        // 2. Update data di database
        $angkatan->update([
            'jenjang' => $request->jenjang,
            'tahun_masuk' => $request->tahun_masuk,
            'nama_angkatan' => $request->nama_angkatan,
        ]);

        // 3. Alihkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.angkatan.index')
                        ->with('success', 'Data angkatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Angkatan $angkatan)
    {
        // Hapus data
        $angkatan->delete();

        // Alihkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.angkatan.index')
                        ->with('success', 'Data angkatan berhasil dihapus.');
    }
}
