<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Angkatan;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
public function index(Request $request): View
    {
        
        $user = $request->user();
        
        // Load data relasi untuk ditampilkan
        $user->load([
            'profile.angkatan',
            'profile.angkatan2',
            'profile.angkatan3',
            'profile.riwayatPekerjaan', 
            'profile.riwayatPendidikan',
            'profile.galeri'
        ]);

        // Tentukan layout
        $layout = (Auth::user()->role == 'pengelola') ? 'layouts.admin' : 'layouts.alumni';

        return view('profile.show', compact('user', 'layout'));
    }

    /**
     * Menampilkan form edit profil (Biodata & Foto).
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        $user->load('profile');

        $dataAngkatan = Angkatan::orderBy('tahun_masuk', 'desc')->get();

        $layout = (Auth::user()->role == 'pengelola') ? 'layouts.admin' : 'layouts.alumni';

        return view('profile.edit', [
            'user' => $user,
            'layout' => $layout,
            'dataAngkatan' => $dataAngkatan 
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. VALIDASI MANUAL (Menggantikan ProfileUpdateRequest)
        $request->validate([
            // Validasi User
            'username' => ['required', 'string', 'max:100', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            
            // Validasi Profile
            'nama_lengkap' => 'required|string|max:255',
            'foto_profil' => 'nullable|image|max:2048', // Max 2MB
            'email_publik' => 'nullable|email|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'angkatan_id' => 'nullable|exists:angkatan,id',
            // Angkatan 2
            'angkatan2_id' => [
                'nullable',
                'exists:angkatan,id',
                'different:angkatan_id',          // Tidak boleh sama dengan Angkatan 1
                'prohibited_if:angkatan_id,null'  // DILARANG diisi jika Angkatan 1 masih kosong
            ],
            // Angkatan 3
            'angkatan3_id' => [
                'nullable',
                'exists:angkatan,id',
                'different:angkatan_id',          // Tidak boleh sama dengan Angkatan 1
                'different:angkatan2_id',         // Tidak boleh sama dengan Angkatan 2
                'prohibited_if:angkatan2_id,null' // DILARANG diisi jika Angkatan 2 masih kosong
            ],
        ], [

            'angkatan2_id.prohibited_if' => 'Mohon isi Angkatan 1 terlebih dahulu sebelum mengisi Angkatan 2.',
            'angkatan3_id.prohibited_if' => 'Mohon isi Angkatan 2 terlebih dahulu sebelum mengisi Angkatan 3.',
            'angkatan2_id.different' => 'Angkatan 2 tidak boleh sama dengan Angkatan 1.',
            'angkatan3_id.different' => 'Angkatan 3 tidak boleh sama dengan Angkatan 1 atau Angkatan 2.',
        ]);

        // 2. SIMPAN DATA USER (Tabel users)
        $user->username = $request->username;
        $user->email = $request->email;
        
        // Reset verifikasi email jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        
        $user->save();

        // 3. SIMPAN DATA PROFILE (Tabel profiles)
        $profile = $user->profile;
        
        $profile->nama_lengkap = $request->nama_lengkap;
        $profile->email_publik = $request->email_publik;
        $profile->nomor_telepon = $request->nomor_telepon;
        
        // Update Angkatan
        if ($request->has('angkatan_id')) $profile->angkatan_id = $request->angkatan_id;
        if ($request->has('angkatan2_id')) $profile->angkatan2_id = $request->angkatan2_id;
        if ($request->has('angkatan3_id')) $profile->angkatan3_id = $request->angkatan3_id;

        // 4. HANDLE FOTO PROFIL
        if ($request->hasFile('foto_profil')) {
            if ($profile->foto_profil_path) {
                Storage::disk('public')->delete($profile->foto_profil_path);
                if($profile->foto_profil_thumbnail) Storage::disk('public')->delete($profile->foto_profil_thumbnail);
            }

            $file = $request->file('foto_profil');
            $filename = hash('sha256', time() . $file->getClientOriginalName()) . '.jpg'; // Nama unik

            $pathOriginal = $file->storeAs('profil_photos', $filename, 'public');
            
            // 2. Buat & Simpan Thumbnail (Max 150x150px, Quality 70%)
            
            $source = imagecreatefromstring(file_get_contents($file));
            
            //  Hitung dimensi baru
            $width = imagesx($source);
            $height = imagesy($source);
            $thumbWidth = 150; // Lebar target
            $thumbHeight = floor($height * ($thumbWidth / $width)); // Tinggi proporsional
            
            // Buat kanvas kosong
            $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
            
            //Isi background dengan PUTIH
            $white = imagecolorallocate($thumb, 255, 255, 255);
            imagefilledrectangle($thumb, 0, 0, $thumbWidth, $thumbHeight, $white);
            
            //Resize gambar asli ke kanvas (Timpa di atas warna putih)
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
            
            //Simpan ke temporary path sebagai JPEG (Kualitas 70% sudah sangat cukup)
            $thumbPathTemp = sys_get_temp_dir() . '/thumb_' . $filename;
            imagejpeg($thumb, $thumbPathTemp, 70); 
            
            //Upload Thumbnail ke Storage Laravel
            $pathThumb = 'profil_photos/thumbs/thumb_' . $filename; // Pastikan folder 'thumbs' ada
            Storage::disk('public')->put($pathThumb, file_get_contents($thumbPathTemp));
            
            //Bersihkan memori
            imagedestroy($source);
            imagedestroy($thumb);
            unlink($thumbPathTemp); // Hapus file temp

            // --- SELESAI PROSES THUMBNAIL ---
            $profile->foto_profil_path = $pathOriginal;
            $profile->foto_profil_thumbnail = $pathThumb;
        }
        
        $profile->save();

        return Redirect::route('profile.index')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
