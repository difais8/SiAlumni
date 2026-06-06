<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';
    protected $guarded = [];

    // Relasi ke Pengirim (User)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relasi ke Angkatan (Room)
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }
}