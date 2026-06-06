<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Angkatan;
use App\Exports\AlumniExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class AlumniManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Ambil data angkatan untuk dropdown
        $semuaAngkatan = \App\Models\Angkatan::orderBy('tahun_masuk', 'desc')->get();

        // 2. Query dengan Filter
        $query = User::where('role', '!=', 'pengelola')->with('profile.angkatan');

        // 3. JOIN TABEL (Supaya bisa sorting berdasarkan kolom tabel lain)
        $query->join('profiles', 'users.id', '=', 'profiles.user_id')
              ->leftJoin('angkatan', 'profiles.angkatan_id', '=', 'angkatan.id')
              ->select('users.*'); // PENTING: Ambil data users saja agar ID tidak tertimpa ID profile

        // 4. Filter Angkatan (Jika ada)
        if ($request->has('angkatan') && $request->angkatan != '') {
            $angkatanId = $request->angkatan;
            $query->where(function($q) use ($angkatanId) {
                $q->where('profiles.angkatan_id', $angkatanId)
                  ->orWhere('profiles.angkatan2_id', $angkatanId)
                  ->orWhere('profiles.angkatan3_id', $angkatanId);
            });
        }

        // 5. SORTING (Angkatan DESC > Nama ASC > Role ASC)
        $query->orderBy('angkatan.tahun_masuk', 'desc') 
              ->orderBy('profiles.nama_lengkap', 'asc')
              ->orderBy('users.role', 'asc');

        // 6. Eksekusi
        $semuaAlumni = $query->get();

        return view('admin.alumni.index', compact('semuaAlumni', 'semuaAngkatan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        // 1. Ambil data User/Alumni (Logic lama kamu)
        $alumni = User::with(['profile.angkatan', 'profile.angkatan2', 'profile.angkatan3'])->findOrFail($id);

        // 2. TAMBAHKAN INI: Ambil semua data angkatan untuk dropdown
        $semuaAngkatan = Angkatan::orderBy('tahun_masuk', 'desc')->get();

        // 3. Kirim ke View (tambahkan 'semuaAngkatan' di compact)
        return view('admin.alumni.edit', compact('alumni', 'semuaAngkatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $alumni)
    {
        // 1. CEK HAK AKSES
        if (auth()->user()->role !== 'pengelola') {
            return redirect()->back()->with('error', 'Hanya Pengelola yang berhak mengubah Role pengguna.');
        }

        // 2. VALIDASI (Update: Tambah validasi jabatan_angkatan_id)
        $request->validate([
            'role' => ['required', 'string', \Illuminate\Validation\Rule::in(['alumni', 'ketua_angkatan', 'ketua_alumni'])],
            
            // Validasi Kondisional: Wajib diisi HANYA JIKA role = ketua_angkatan
            'jabatan_angkatan_id' => 'nullable|required_if:role,ketua_angkatan|exists:angkatan,id',
        ], [
            'jabatan_angkatan_id.required_if' => 'Harap pilih Angkatan mana yang dipimpin oleh Ketua ini.',
        ]);

        // 3. UPDATE USER (Role)
        $alumni->update(['role' => $request->role]);

        // 4. UPDATE PROFILE (Jabatan Angkatan) -- INI YANG BARU
        $profile = $alumni->profile;

        if ($request->role == 'ketua_angkatan') {
            // Jika jadi ketua, simpan ID angkatan yang dipimpin
            $profile->jabatan_angkatan_id = $request->jabatan_angkatan_id;
        } else {
            // Jika turun jabatan (jadi alumni biasa/ketua umum), hapus jabatannya
            $profile->jabatan_angkatan_id = null;
        }

        $profile->save(); // Simpan perubahan profil
        
        return redirect()->route('admin.alumni.index')->with('success', 'Role dan Jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $alumni)
    {
        // 1. Otorisasi: HANYA Pengelola yang boleh menghapus
        if (auth()->user()->role !== 'pengelola') {
            return redirect()->route('admin.alumni.index')
                             ->with('error', 'Hanya Pengelola yang berhak menghapus akun.');
        }

        // 2. Cek agar tidak menghapus diri sendiri
        if ($alumni->id === auth()->id()) {
             return redirect()->route('admin.alumni.index')
                              ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $alumni->delete();

        return redirect()->route('admin.alumni.index')
                        ->with('success', 'Akun alumni berhasil dihapus.');
    }
    
    public function export(Request $request)
    {
        // Ambil filter angkatan dari URL (jika ada)
        $angkatanId = $request->query('angkatan');

        // Buat nama file yang dinamis
        $namaFile = 'Data_Alumni';
        if($angkatanId) {
            $angkatan = \App\Models\Angkatan::find($angkatanId);
            if($angkatan) {
                $namaFile .= '_' . $angkatan->jenjang . '_' . $angkatan->tahun_masuk;
            }
        }
        $namaFile .= '_' . date('Y-m-d_H-i') . '.xlsx';

        // Download Excel
        return Excel::download(new AlumniExport($angkatanId), $namaFile);
    }
}
