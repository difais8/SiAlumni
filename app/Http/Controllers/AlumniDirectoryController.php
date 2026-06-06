<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumniDirectoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data angkatan untuk dropdown
        $semuaAngkatan = \App\Models\Angkatan::orderBy('tahun_masuk', 'desc')->get();

        // 2. Query Dasar
        $query = \App\Models\User::with(['profile.angkatan'])
            ->where('role', '!=', 'pengelola');

        // 3. JOIN TABEL 
        $query->join('profiles', 'users.id', '=', 'profiles.user_id')
              ->leftJoin('angkatan', 'profiles.angkatan_id', '=', 'angkatan.id')
              ->select('users.*'); 

        // 4. Filter Angkatan 
        if ($request->has('angkatan') && $request->angkatan != '') {
            $angkatanId = $request->angkatan;
            $query->whereHas('profile', function($q) use ($angkatanId) {
                $q->where(function($sub) use ($angkatanId) {
                    $sub->where('angkatan_id', $angkatanId)
                        ->orWhere('angkatan2_id', $angkatanId)
                        ->orWhere('angkatan3_id', $angkatanId);
                });
            });
        }

        // 5. SORTING 
        $query->orderBy('angkatan.tahun_masuk', 'desc')
              ->orderBy('profiles.nama_lengkap', 'asc')
              ->orderBy('users.role', 'asc');

        // 6. Eksekusi
        $alumni = $query->get();

        // 7. Return View
        return view('alumni.direktori.index', [
            'alumni' => $alumni,
            'semuaAngkatan' => $semuaAngkatan
        ]);
    }

    /**
     * Menampilkan Detail Alumni
     */
    public function show($id)
    {
        // Ambil data target alumni
        $alumni = User::with([
            'profile.angkatan', 
            'profile.galeri', 
            'profile.riwayatPendidikan', 
            'profile.riwayatPekerjaan'
        ])->findOrFail($id);

        $viewer = Auth::user();
        $isPrivileged = false;

        // 1. Pengelola & Ketua Alumni -> Bisa lihat SEMUA
        if (in_array($viewer->role, ['pengelola', 'ketua_alumni'])) {
            $isPrivileged = true;
        } 
        
        // 2. Ketua Angkatan -> Hanya bisa lihat jika SATU ANGKATAN
        elseif ($viewer->role == 'ketua_angkatan') {
            $viewerAngkatan = $viewer->profile->angkatan_id;
            $targetAngkatan = $alumni->profile->angkatan_id;
            $targetAngkatan2 = $alumni->profile->angkatan2_id;
            $targetAngkatan3 = $alumni->profile->angkatan3_id;

            if (in_array($viewerAngkatan, [$targetAngkatan, $targetAngkatan2, $targetAngkatan3])) {
                $isPrivileged = true;
            }
        }

        return view('alumni.direktori.show', compact('alumni', 'isPrivileged'));
    }
}