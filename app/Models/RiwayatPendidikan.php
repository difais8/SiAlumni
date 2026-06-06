<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPendidikan extends Model
{
    use HasFactory;
    protected $table = 'riwayat_pendidikan';
    protected $guarded = []; // Izinkan mass assignment
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
