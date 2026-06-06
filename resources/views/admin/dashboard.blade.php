@extends('layouts.admin')
@section('title', 'Dashboard Utama')

@section('content')

<div class="row">
    <div class="col-md-12 mb-4">
        <h3 class="font-weight-bold mb-3">Dashboard Admin</h3>
        <div class="row">
            <div class="col-12">
                @php
                    $nama = auth()->user()->profile->nama_lengkap ?? auth()->user()->username;
                @endphp
                <h4 class="font-weight-normal">Selamat Datang, {{ $nama }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Card 1: Total Alumni --}}
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card text-white card-body-icon" style="background-color: #499aac">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <p class="card-title mb-4 text-white">Total Alumni</p>
                    <p class="fs-30 mb-2 font-weight-bold">{{ $totalAlumni }}</p>
                    <p class="small">Orang Terdaftar</p>
                </div>
                <i class="mdi mdi-account-group mdi-48px" style="opacity: 0.5;"></i>
            </div>
        </div>
    </div>

    {{-- Card 2: Total Angkatan --}}
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-success text-white card-body-icon">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <p class="card-title mb-4 text-white">Total Angkatan</p>
                    <p class="fs-30 mb-2 font-weight-bold">{{ $totalAngkatan }}</p>
                    <p class="small">Angkatan Terdata</p>
                </div>
                <i class="mdi mdi-calendar-multiple mdi-48px" style="opacity: 0.5;"></i>
            </div>
        </div>
    </div>

    {{-- Card 3: Pengumuman Aktif --}}
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card text-white card-body-icon" style="background-color: #5292d6">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <p class="card-title mb-4 text-white">Event Aktif</p>
                    <p class="fs-30 mb-2 font-weight-bold">{{ $totalPengumuman }}</p>
                    <p class="small">Postingan Tayang</p>
                </div>
                <i class="mdi mdi-bullhorn mdi-48px" style="opacity: 0.5;"></i>
            </div>
        </div>
    </div>

    {{-- Card 4: User Baru --}}
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card text-white card-body-icon" style="background-color: #2981e5">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <p class="card-title mb-4 text-white">Pendaftar Baru</p>
                    <p class="fs-30 mb-2 font-weight-bold">{{ $userBaru }}</p>
                    <p class="small">30 Hari Terakhir</p>
                </div>
                <i class="mdi mdi-account-plus mdi-48px" style="opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</div>

{{-- Tambahkan row/kolom lain di sini jika perlu (misal: chart, statistik) --}}

@endsection