<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke Angkatan (Multi-angkatan)
            $table->foreignId('angkatan_id')->nullable()->constrained('angkatan')->onDelete('set null');
            $table->foreignId('angkatan2_id')->nullable()->constrained('angkatan')->onDelete('set null');
            $table->foreignId('angkatan3_id')->nullable()->constrained('angkatan')->onDelete('set null');
            
            // Jabatan Ketua Angkatan
            $table->foreignId('jabatan_angkatan_id')->nullable()->constrained('angkatan')->onDelete('set null');

            // Data Diri
            $table->string('nama_lengkap');
            $table->string('foto_profil_path')->nullable();
            $table->string('foto_profil_thumbnail')->nullable(); // Dari revisi add_thumbnail
            $table->string('email_publik')->nullable();
            $table->string('nomor_telepon')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};