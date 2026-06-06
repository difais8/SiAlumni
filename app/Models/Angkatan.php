<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    use HasFactory;
    
    protected $table = 'angkatan';
    protected $guarded = [];

    // Relasi ke profile (Satu angkatan punya banyak profile)
    public function profiles()
    {
        return $this->hasMany(profile::class);
    }

    // Relasi ke Chat (Satu angkatan punya banyak pesan)
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
