<?php

namespace App\Http\Controllers\Auth;

use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;



class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi HANYA data registrasi (ANGKATAN_ID DIHAPUS)
        $request->validate([
            'name' => ['required', 'string', 'max:255'], // Ini akan jadi 'nama_lengkap'
            'username' => ['required', 'string', 'max:100', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Gunakan Transaksi Database
        try {
            DB::beginTransaction();

            // 3. Buat entri di tabel 'users'
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'alumni', // Default role
            ]);

            // 4. Buat entri di tabel 'profiles' (Minimal)
            Profile::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->name,
            ]);

            DB::commit(); // Sukses

        } catch (\Exception $e) {
            DB::rollBack(); // Gagal
            return redirect()->back()->with('error', 'Registrasi gagal, silakan coba lagi.');
        }
        
        // 5. Login user yang baru dibuat
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
