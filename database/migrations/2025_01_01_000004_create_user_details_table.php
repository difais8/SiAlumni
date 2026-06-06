<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Riwayat Pekerjaan
        Schema::create('riwayat_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_perusahaan');
            $table->string('jabatan');
            $table->year('tahun_mulai');
            $table->year('tahun_selesai')->nullable();
            $table->timestamps();
        });

        // 2. Riwayat Pendidikan
        Schema::create('riwayat_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_institusi');
            $table->string('jenjang', 50);
            $table->string('jurusan')->nullable();
            $table->year('tahun_mulai');
            $table->year('tahun_selesai')->nullable();
            $table->timestamps();
        });

        // 3. Galeri Pengguna
        Schema::create('galeri_pengguna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('file_path');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_pengguna');
        Schema::dropIfExists('riwayat_pendidikan');
        Schema::dropIfExists('riwayat_pekerjaan');
    }
};