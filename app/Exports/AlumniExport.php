<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AlumniExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $angkatanId;

    public function __construct($angkatanId = null)
    {
        $this->angkatanId = $angkatanId;
    }

    public function query()
    {
        $query = User::query()
            ->whereIn('role', ['alumni', 'ketua_angkatan', 'ketua_alumni'])
            ->with([
                'profile.angkatan', 
                'profile.angkatan2', 
                'profile.angkatan3',
                'profile.jabatanAngkatan' 
            ]);

        // JOIN TABEL (Wajib di sini juga biar bisa orderBy)
        $query->join('profiles', 'users.id', '=', 'profiles.user_id')
              ->leftJoin('angkatan', 'profiles.angkatan_id', '=', 'angkatan.id')
              ->select('users.*'); // Penting!

        // Filter
        if ($this->angkatanId) {
            $angkatanId = $this->angkatanId;
            $query->where(function($q) use ($angkatanId) {
                $q->where('profiles.angkatan_id', $angkatanId)
                  ->orWhere('profiles.angkatan2_id', $angkatanId)
                  ->orWhere('profiles.angkatan3_id', $angkatanId);
            });
        }

        // SORTING SAMA PERSIS DENGAN VIEW
        return $query->orderBy('angkatan.tahun_masuk', 'desc')
                     ->orderBy('profiles.nama_lengkap', 'asc')
                     ->orderBy('users.role', 'asc');
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Role / Jabatan',
            'Username',
            'Email Akun',
            'Email Publik',
            'Nomor Telepon',
            'Angkatan 1',
            'Angkatan 2',
            'Angkatan 3',
            'Tanggal Daftar',
        ];
    }

    public function map($user): array
    {
        // 1. AMBIL PROFILE SECARA AMAN
        $p = $user->profile;

        // 2. CEK JIKA PROFILE KOSONG (Ini penyebab Blank Page sebelumnya)
        if (!$p) {
            return [
                '(Profil Belum Diisi)', 
                ucfirst($user->role),   
                $user->username,
                $user->email,
                '-', '-', '-', '-', '-', 
                $user->created_at->format('d-m-Y')
            ];
        }

        // 3. LOGIKA ROLE TEKS
        $roleText = 'Alumni';

        if ($user->role === 'ketua_alumni') {
            $roleText = 'Ketua Alumni (Umum)';
        } 
        elseif ($user->role === 'ketua_angkatan') {
            // Gunakan optional chaning atau null check agar tidak error
            $jabatan = $p->jabatanAngkatan ?? null;
            
            if ($jabatan) {
                $roleText = "Ketua Angkatan {$jabatan->jenjang} {$jabatan->tahun_masuk} - {$jabatan->nama_angkatan}";
            } else {
                $roleText = "Ketua Angkatan (Data Angkatan Tidak Ditemukan)";
            }
        }

        // 4. RETURN DATA (Gunakan ?? '-' untuk handle nilai null)
        return [
            $p->nama_lengkap ?? '-',
            $roleText,
            $user->username,
            $user->email,
            $p->email_publik ?? '-',
            $p->nomor_telepon ?? '-',
            
            // Cek Angkatan 1
            $p->angkatan ? "{$p->angkatan->jenjang} {$p->angkatan->tahun_masuk} - {$p->angkatan->nama_angkatan}" : '-',
            // Cek Angkatan 2
            $p->angkatan2 ? "{$p->angkatan2->jenjang} {$p->angkatan2->tahun_masuk} - {$p->angkatan2->nama_angkatan}" : '-',
            // Cek Angkatan 3
            $p->angkatan3 ? "{$p->angkatan3->jenjang} {$p->angkatan3->tahun_masuk} - {$p->angkatan3->nama_angkatan})" : '-',
            
            $user->created_at->format('d-m-Y'),
        ];
    }
}