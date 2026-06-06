<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $guarded = []; // Izinkan mass assignment
    
    // Relasi ke User (Satu profile dimiliki satu user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Angkatan (Satu profile dimiliki satu angkatan)
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }

    public function angkatan2()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan2_id');
    }

    public function angkatan3()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan3_id');
    }

    // Relasi ke Riwayat Pekerjaan
    public function riwayatPekerjaan()
    {
        return $this->hasMany(RiwayatPekerjaan::class, 'user_id', 'user_id');
    }

    // Relasi ke Riwayat Pendidikan
    public function riwayatPendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'user_id', 'user_id');
    }

    // Relasi ke Galeri
    public function galeri()
    {
        return $this->hasMany(GaleriPengguna::class, 'user_id', 'user_id');
    }

    // Relasi ke Jabatan
    public function jabatanAngkatan()
    {
        return $this->belongsTo(Angkatan::class, 'jabatan_angkatan_id');
    }
}
