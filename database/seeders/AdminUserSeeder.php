<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kita gunakan DB Transaction untuk keamanan
        DB::beginTransaction();
        try {
            // 1. Buat User (untuk Login)
            $adminUser = User::create([
                'username' => 'admin',
                'email' => 'admin@alumni.com',
                'password' => Hash::make('11111111'), // Ganti 'password' ini
                'role' => 'pengelola',
                'email_verified_at' => now()
            ]);

            // 2. Buat Profile (untuk Data Diri)
            Profile::create([
                'user_id' => $adminUser->id,
                'nama_lengkap' => 'Admin Pengelola',
                'angkatan_id' => null, // Admin tidak terikat angkatan
            ]);

            DB::commit();

            $this->command->info('Akun Pengelola (admin) berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Gagal membuat akun admin: ' . $e->getMessage());
        }
    }
}
