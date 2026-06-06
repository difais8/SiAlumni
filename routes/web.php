<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiwayatPekerjaanController;
use App\Http\Controllers\RiwayatPendidikanController;
use App\Http\Controllers\GaleriPenggunaController;
use App\Http\Controllers\AlumniDirectoryController;
use App\Http\Controllers\ChatController;

// Admin Controllers
use App\Http\Controllers\Admin\AngkatanController;
use App\Http\Controllers\Admin\AlumniManagementController;
use App\Http\Controllers\Admin\PengelolaController;
use App\Http\Controllers\Admin\AnnouncementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// ========================================================================
// GRUP 1: GLOBAL AUTH (Alumni, Ketua, Pengelola bisa akses ini)
// ========================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Dashboard & Pengumuman (Read Only)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pengumuman/{id}', [DashboardController::class, 'showAnnouncement'])->name('alumni.pengumuman.show');

    // 2. Manajemen Profil & Akun
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. Riwayat Hidup
    Route::resource('riwayat-pekerjaan', RiwayatPekerjaanController::class)
        ->except(['index', 'show'])
        ->parameters(['riwayat-pekerjaan' => 'pekerjaan']);
        
    Route::resource('riwayat-pendidikan', RiwayatPendidikanController::class)
        ->except(['index', 'show'])
        ->parameters(['riwayat-pendidikan' => 'pendidikan']);

    // 4. Galeri Pengguna
    Route::post('/galeri', [GaleriPenggunaController::class, 'store'])->name('galeri.store');
    Route::delete('/galeri/{galeri}', [GaleriPenggunaController::class, 'destroy'])->name('galeri.destroy');

    // 5. Direktori Alumni
    Route::get('/direktori', [AlumniDirectoryController::class, 'index'])->name('alumni.direktori.index');
    Route::get('/direktori/{id}', [AlumniDirectoryController::class, 'show'])->name('alumni.direktori.show');

    // 6. FITUR CHATTING
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/messages', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::delete('/chat/message/{id}', [ChatController::class, 'destroy'])->name('chat.delete');

});


// ========================================================================
// GRUP 2: KHUSUS PENGELOLA (Admin Full Control)
// ========================================================================
Route::middleware(['auth', 'verified', 'role:pengelola'])
    ->prefix('admin')
    ->name('admin.') 
    ->group(function () {

    Route::get('alumni/export', [AlumniManagementController::class, 'export'])
        ->name('alumni.export');
    // 1. Manajemen Master Data
    Route::resource('angkatan', AngkatanController::class);
    
    // 2. Manajemen User (Alumni & Ketua)
    Route::resource('alumni', AlumniManagementController::class)
        ->parameters(['alumni' => 'alumni']);
    

    // 3. Manajemen Sesama Pengelola
    Route::resource('pengelola', PengelolaController::class)
        ->parameters(['pengelola' => 'pengelola']);

    // 4. Manajemen Pengumuman (Full CRUD)
    Route::resource('pengumuman', AnnouncementController::class);
});

require __DIR__.'/auth.php';

Route::get('/preview-email', function () {
    
    // 1. Ambil sembarang user (untuk contoh nama)
    $user = App\Models\User::first();

    // 2. Tampilkan View langsung
    return view('emails.reset_password', [
        'notifiable' => $user ?? new App\Models\User(['username' => 'Contoh User']), // Fallback jika DB kosong
        'url' => 'http://localhost:8000/reset-password/contoh-token-dummy', // URL bohongan
    ]);
});