<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriPengguna extends Model
{
    use HasFactory;
    protected $table = 'galeri_pengguna';
    protected $guarded = []; // Izinkan mass assignment
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
