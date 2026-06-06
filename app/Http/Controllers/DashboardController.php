<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Angkatan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pengelola') {
            $totalAlumni = User::where('role', '!=', 'pengelola')->count();
            $totalAngkatan = Angkatan::count();
            $hariIni = now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
            $totalPengumuman = Announcement::where(function($q) use ($hariIni) {
                                    $q->whereDate('tgl_selesai', '>=', $hariIni)
                                      ->orWhere(function($sub) use ($hariIni) {
                                          $sub->whereNull('tgl_selesai')
                                              ->whereDate('tgl_mulai', '>=', $hariIni);
                                      });
                                })->count();
            $userBaru = User::where('role', '!=', 'pengelola')
                            ->where('created_at', '>=', now()->subDays(30))
                            ->count();

            return view('admin.dashboard', compact('totalAlumni', 'totalAngkatan', 'totalPengumuman', 'userBaru')); 
        }

        $hariIni = now()->format('Y-m-d');
        
        $eventMendatang = Announcement::where(function($q) use ($hariIni) {
                                $q->whereDate('tgl_selesai', '>=', $hariIni)
                                  ->orWhere(function($sub) use ($hariIni) {
                                      $sub->whereNull('tgl_selesai')
                                          ->whereDate('tgl_mulai', '>=', $hariIni);
                                  });
                            })
                            ->orderBy('tgl_mulai', 'asc')
                            ->get();
                            
        return view('alumni.dashboard', compact('eventMendatang'));
    }
    
    public function showAnnouncement($id)
    {
        $pengumuman = Announcement::with('author.profile')->findOrFail($id);
        return view('alumni.pengumuman.show', compact('pengumuman'));
    }
}