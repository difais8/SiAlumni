{{-- File: resources/views/alumni/dashboard.blade.php --}}
@extends('layouts.alumni')

@section('title', 'Beranda Alumni')

@section('content')

{{-- BAGIAN 1: ONBOARDING ALERT (Jika Angkatan Belum Diisi) --}}
@if(is_null(auth()->user()->profile->angkatan_id))
<div class="alert alert-warning border-0 shadow-sm p-4 mb-5" role="alert" style="border-radius: 15px; background: #fff3cd;">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="alert-heading font-weight-bold"><i class="mdi mdi-alert-circle-outline"></i> Profil Belum Lengkap!</h4>
            <p class="mb-2">
                Halo <strong>{{ auth()->user()->username }}</strong>, selamat datang! <br>
                Agar bisa terhubung dengan teman seangkatan, silakan lengkapi profil Anda (terutama <strong>Tahun Angkatan</strong>).
            </p>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('profile.edit') }}" class="btn btn-warning font-weight-bold shadow-sm">
                Lengkapi Profil Sekarang <i class="mdi mdi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endif

{{-- BAGIAN 2: HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="font-weight-bold text-dark">Pengumuman & Event Terbaru</h3>
</div>

{{-- BAGIAN 3: DAFTAR EVENT (GRID) --}}
<div class="row">
    @forelse($eventMendatang as $event)
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card card-event shadow-sm h-100">
                {{-- Poster --}}
                <div style="height: 200px; overflow: hidden; background-color: #f0f0f0;" class="d-flex align-items-center justify-content-center position-relative">
                    @if($event->poster_path)
                        <img src="{{ asset('storage/' . $event->poster_path) }}" alt="poster" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(45deg, #6a11cb 0%, #2575fc 100%); display: flex; align-items: center; justify-content: center; color: white; flex-direction: column;">
                            <i class="mdi mdi-calendar-star" style="font-size: 3rem;"></i>
                            <span class="mt-2 font-weight-bold">Event Alumni</span>
                        </div>
                    @endif
                    <div class="position-absolute bg-white shadow-sm px-3 py-1 rounded font-weight-bold text-primary" style="bottom: 10px; left: 10px; font-size: 0.8rem;">
                        <i class="mdi mdi-calendar"></i> 
                        {{ $event->tgl_mulai ? \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y') : 'Segera' }}
                        @if($event->tgl_selesai && $event->tgl_selesai != $event->tgl_mulai)
                            s/d {{ \Carbon\Carbon::parse($event->tgl_selesai)->format('d M Y') }}
                        @endif
                    </div>
                </div>
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title font-weight-bold mb-2">{{ Str::limit($event->judul, 40) }}</h5>
                    
                    <p class="card-text text-muted mb-4 flex-grow-1">
                        {{ Str::limit(strip_tags($event->isi_konten), 100) }}
                    </p>
                    <a href="{{ route('alumni.pengumuman.show', $event->id) }}" class="btn btn-outline-primary btn-block rounded-pill">
                        Detail Event
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <img src="{{ asset('images/aset/usern.png') }}" alt="Empty" style="width: 100px; opacity: 0.5; filter: grayscale(100%);">
                <h5 class="mt-3 text-muted">Belum ada pengumuman atau event mendatang.</h5>
            </div>
        </div>
    @endforelse
</div>

@endsection