<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Halaman Utama Chatting
     */
    public function index()
    {
        $user = Auth::user();
        $angkatanList = [];

        // Jika Pengelola, ambil semua angkatan untuk filter
        if ($user->role == 'pengelola') {
            $angkatanList = Angkatan::orderBy('tahun_masuk', 'desc')->get();
        }

        return view('chat.index', compact('angkatanList'));
    }

    /**
     * API: Mengambil Pesan (AJAX)
     */
    public function fetchMessages(Request $request)
    {
        $user = Auth::user();
        $roomId = $request->room_id; // null = global, angka = angkatan_id

        // KEAMANAN: Cek Hak Akses Room
        if ($roomId) {
            $p = $user->profile;
            $isMyRoom = ($p->angkatan_id == $roomId || $p->angkatan2_id == $roomId || $p->angkatan3_id == $roomId);
            
            if ($user->role !== 'pengelola' && !$isMyRoom) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }
        
        $query = ChatMessage::with('sender.profile')
            ->where('angkatan_id', $roomId) // null = global, angka = angkatan
            ->latest(); // Ambil dari yang paling baru

        // Ambil 500 per halaman
        $messages = $query->paginate(500); 

        return response()->json([
            'data' => collect($messages->items())->reverse()->values(),
            'current_page' => $messages->currentPage(),
            'last_page' => $messages->lastPage(),
            'next_page_url' => $messages->nextPageUrl()
        ]);
    }

    /**
     * API: Mengirim Pesan (AJAX)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required', // Isi pesan (HTML Summernote)
            'room_id' => 'nullable|exists:angkatan,id', 
        ]);

        $user = Auth::user();
        $roomId = $request->room_id;

        // KEAMANAN: Cek sebelum kirim
        if ($roomId) {
            $p = $user->profile;
            // Izinkan jika room ID cocok dengan salah satu angkatan user
            $isMyRoom = ($p->angkatan_id == $roomId || $p->angkatan2_id == $roomId || $p->angkatan3_id == $roomId);
            
            if ($user->role !== 'pengelola' && !$isMyRoom) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        $chat = ChatMessage::create([
            'sender_id' => $user->id,
            'angkatan_id' => $roomId,
            'message' => $request->message,
        ]);

        // Kembalikan data pesan baru beserta info pengirim
        return response()->json($chat->load('sender.profile'));
    }

    /**
     * Menghapus Pesan
     */
    public function destroy($id)
    {
        $chat = ChatMessage::findOrFail($id);
        $user = Auth::user();

        // LOGIKA HAPUS (Sesuai Matriks)
        $canDelete = false;

        // 1. Pemilik pesan selalu bisa hapus
        if ($chat->sender_id == $user->id) {
            $canDelete = true;
        }
        // 2. Pengelola bisa hapus pesan siapa saja (Global & Angkatan)
        elseif ($user->role == 'pengelola') {
            $canDelete = true;
        }
        // 3. Ketua (Y/Z) bisa hapus pesan orang lain HANYA DI CHAT ANGKATANNYA
        elseif ($user->role=='ketua_alumni') {
            // Cek apakah chat ini ada di room angkatan milik si ketua
            if ($chat->angkatan_id != null && $chat->angkatan_id == $user->profile->angkatan_id) {
                $canDelete = true;
            }
        }

        elseif ($user->role == 'ketua_angkatan') {
            // Cek apakah angkatan chat ini SAMA dengan angkatan jabatannya
            if ($chat->angkatan_id == $user->profile->jabatan_angkatan_id) {
                $canDelete = true;
            }
        }

        if (!$canDelete) {
            return response()->json(['error' => 'Tidak diizinkan menghapus pesan ini.'], 403);
        }

        $chat->delete();

        return response()->json(['success' => 'Pesan dihapus.']);
    }
}