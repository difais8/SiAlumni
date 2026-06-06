<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $hariIni = now()->format('Y-m-d');

        // Logika Event Mendatang
        $eventMendatang = Announcement::where(function($q) use ($hariIni) {
                                $q->whereDate('tgl_selesai', '>=', $hariIni)
                                  ->orWhere(function($sub) use ($hariIni) {
                                      $sub->whereNull('tgl_selesai')
                                          ->whereDate('tgl_mulai', '>=', $hariIni);
                                  });
                            })
                            ->orderBy('tgl_mulai', 'asc')
                            ->get();

        // Logika Event Lewat
        $eventLewat = Announcement::where(function($q) use ($hariIni) {
                                $q->whereDate('tgl_selesai', '<', $hariIni)
                                  ->orWhere(function($sub) use ($hariIni) {
                                      $sub->whereNull('tgl_selesai')
                                          ->whereDate('tgl_mulai', '<', $hariIni);
                                  });
                            })
                            ->orderBy('tgl_selesai', 'desc')
                            ->get();

        return view('admin.pengumuman.index', [
            'eventMendatang' => $eventMendatang,
            'eventLewat' => $eventLewat
        ]);
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi_konten' => 'required',
            // Hapus validasi dimensi, cukup validasi tipe dan ukuran (Max 5MB)
            'poster' => 'nullable|image|max:5120', 
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        Announcement::create([
            'author_id' => Auth::id(),
            'judul' => $request->judul,
            'isi_konten' => $request->isi_konten,
            'poster_path' => $posterPath,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'lokasi' => $request->lokasi,
            'sosial_media' => $request->sosial_media,
        ]);

        return redirect()->route('admin.pengumuman.index')
                        ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function show($id)
    {
        $pengumuman = Announcement::with('author.profile')->findOrFail($id);
        return view('admin.pengumuman.show', ['pengumuman' => $pengumuman]);
    }

    public function edit($id)
    {
        $pengumuman = Announcement::findOrFail($id);

        if (auth()->user()->role !== 'pengelola' && auth()->id() !== $pengumuman->author_id) {
            abort(403, 'Anda hanya dapat mengedit pengumuman yang Anda buat sendiri.');
        }
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Announcement::findOrFail($id);
        
        if (auth()->user()->role !== 'pengelola' && auth()->id() !== $pengumuman->author_id) {
            abort(403, 'Anda hanya dapat mengupdate pengumuman yang Anda buat sendiri.');
        }
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi_konten' => 'required',
            'poster' => 'nullable|image|max:5120',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
        ]);

        if ($request->hasFile('poster')) {
            if ($pengumuman->poster_path) {
                Storage::disk('public')->delete($pengumuman->poster_path);
            }
            $pengumuman->poster_path = $request->file('poster')->store('posters', 'public');
        }

        $pengumuman->judul = $request->judul;
        $pengumuman->isi_konten = $request->isi_konten;
        $pengumuman->tgl_mulai = $request->tgl_mulai;
        $pengumuman->tgl_selesai = $request->tgl_selesai;
        $pengumuman->waktu_mulai = $request->waktu_mulai;
        $pengumuman->waktu_selesai = $request->waktu_selesai;
        $pengumuman->lokasi = $request->lokasi;
        $pengumuman->sosial_media = $request->sosial_media;
        
        $pengumuman->save();
        return redirect()->route('admin.pengumuman.index')
                         ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengumuman = Announcement::findOrFail($id);

        if (auth()->user()->role !== 'pengelola' && auth()->id() !== $pengumuman->author_id) {
            abort(403, 'Anda hanya dapat menghapus pengumuman yang Anda buat sendiri.');
        }
        
        if ($pengumuman->poster_path) {
            Storage::disk('public')->delete($pengumuman->poster_path);
        }

        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
                        ->with('success', 'Pengumuman berhasil dihapus.');
    }
}