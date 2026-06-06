<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;


class PengelolaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semuaPengelola = User::where('role', 'pengelola')
                                ->with('profile')
                                ->latest()
                                ->get();

        return view('admin.pengelola.index', [
            'semuaPengelola' => $semuaPengelola
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pengelola.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Gunakan Transaksi
        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pengelola',
                'email_verified_at' => now()
            ]);

            Profile::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'angkatan_id' => null,
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }

        // 3. Redirect
        return redirect()->route('admin.pengelola.index')
                         ->with('success', 'Akun pengelola baru berhasil ditambahkan.');
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
    public function edit(User $pengelola)
    {
        $pengelola->load('profile');
        
        return view('admin.pengelola.edit', [
            'pengelola' => $pengelola
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $pengelola)
    {
        // 1. Validasi (termasuk validasi 'unique' yang mengabaikan ID saat ini)
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users')->ignore($pengelola->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($pengelola->id)],
        ]);

        // 2. Gunakan Transaksi
        try {
            DB::beginTransaction();

            // Update tabel 'users'
            $pengelola->update([
                'username' => $request->username,
                'email' => $request->email,
            ]);

            // Update tabel 'profiles'
            $pengelola->profile->update([
                'nama_lengkap' => $request->nama_lengkap,
            ]);
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal update akun: ' . $e->getMessage());
        }
        
        // 3. Redirect
        return redirect()->route('admin.pengelola.index')
                        ->with('success', 'Akun pengelola berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $pengelola)
    {
        // 1. Validasi agar tidak menghapus diri sendiri
        if ($pengelola->id == Auth::id()) {
            return redirect()->route('admin.pengelola.index')
                            ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // 2. Hapus user (profile akan terhapus otomatis via onDelete('cascade'))
        $pengelola->delete();

        // 3. Redirect
        return redirect()->route('admin.pengelola.index')
                        ->with('success', 'Akun pengelola berhasil dihapus.');
    }
}
