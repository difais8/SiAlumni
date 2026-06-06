<?php

namespace App\Http\Controllers;

use App\Models\GaleriPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GaleriPenggunaController extends Controller
{
    /**
     * Menyimpan foto baru ke galeri.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        // --- LOGIKA BATASAN 20 FOTO ---
        $jumlahFoto = $user->profile->galeri()->count(); // Hitung foto saat ini
        
        if ($jumlahFoto >= 10) {
            // Jika sudah 20, tolak upload
            return redirect()->back()->with('error', 'Kuota Penuh! Batas maksimal galeri adalah 10 foto. Hapus foto lama untuk menambah baru.');
        }

        $request->validate([
            'foto' => 'required|image|max:5120', // Maks 5MB
            'keterangan' => 'nullable|string|max:255',
        ]);

        $path = $request->file('foto')->store('galeri_users', 'public');

        GaleriPengguna::create([
            'user_id' => Auth::id(),
            'file_path' => $path,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('profile.index')->with('success', 'Foto berhasil ditambahkan ke galeri.');
    }

    /**
     * Menghapus foto dari galeri.
     */
    public function destroy(GaleriPengguna $galeri)
    {
        // Keamanan: Pastikan hanya pemilik yang bisa hapus
        if ($galeri->user_id !== Auth::id()) {
            abort(403);
        }

        // Hapus file fisik
        if ($galeri->file_path && Storage::disk('public')->exists($galeri->file_path)) {
            Storage::disk('public')->delete($galeri->file_path);
        }

        // Hapus data di database
        $galeri->delete();

        return redirect()->route('profile.index')->with('success', 'Foto berhasil dihapus dari galeri.');
    }
}