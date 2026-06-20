{{-- File: resources/views/admin/pengumuman/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Pengumuman & Event')

@section('content')
@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="font-weight-bold">Pengumuman & Event</h3>
        <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary">
            <i class="mdi mdi-plus"></i> Tambah Pengumuman
        </a>
    </div>
    
    {{-- BAGIAN 1: EVENT MENDATANG --}}
    <h4 class="mb-3 text-primary"><i class="mdi mdi-calendar-clock"></i> Pengumuman / Event Mendatang</h4>
    <div class="row">
        @forelse($eventMendatang as $event)
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card shadow-sm">
                    {{-- Gambar Poster --}}
                    <div style="height: 200px; overflow: hidden; background-color: #e9ecef;" class="d-flex align-items-center justify-content-center">
                        @if($event->poster_path)
                            <img src="{{ asset('storage/' . $event->poster_path) }}" alt="poster" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span class="text-muted">Tidak ada poster</span>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold mb-2">{{ Str::limit($event->judul, 30) }}</h5>
                        
                        {{-- Tanggal Singkat --}}
                        <p class="text-muted mb-2" style="font-size: 0.9em;">
                            <i class="mdi mdi-calendar"></i> 
                            @if($event->tgl_mulai == $event->tgl_selesai)
                                {{ $event->tgl_mulai ? \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y') : '-' }}
                            @else
                                {{ $event->tgl_mulai ? \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y') : '-' }}
                                s/d
                                {{ $event->tgl_selesai ? \Carbon\Carbon::parse($event->tgl_selesai)->format('d M Y') : '-' }}
                            @endif
                        </p>
                        {{-- Excerpt (Cuplikan Isi - Strip Tags agar HTML summernote hilang) --}}
                        <p class="card-text text-muted mb-3">
                            {{ Str::limit(strip_tags($event->isi_konten), 80) }}
                        </p>
                        
                        <a href="{{ route('admin.pengumuman.show', $event->id) }}" class="btn btn-outline-primary btn-sm btn-block">
                            Detail Pengumuman / Event
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light text-center border">Belum ada pengumuman / event mendatang.</div>
            </div>
        @endforelse
    </div>
    
    {{-- PEMBATAS GARIS --}}
    <hr class="my-5" style="border-top: 2px dashed #ccc;">
    
    {{-- BAGIAN 2: EVENT TERLEWAT (OVERDUE) --}}
    <h4 class="mb-3 text-muted"><i class="mdi mdi-history"></i> Pengumuman / Event Selesai</h4>
    <div class="row">
        @forelse($eventLewat as $event)
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card shadow-sm opacity-75"> {{-- Opacity biar kelihatan non-aktif --}}
                    <div style="height: 200px; overflow: hidden; background-color: #e9ecef;" class="d-flex align-items-center justify-content-center">
                        @if($event->poster_path)
                            <img src="{{ asset('storage/' . $event->poster_path) }}" alt="poster" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(80%);">
                        @else
                            <span class="text-muted">Tidak ada poster</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold mb-2 text-muted">{{ Str::limit($event->judul, 30) }}</h5>
                        <p class="text-muted mb-2" style="font-size: 0.9em;">
                            <i class="mdi mdi-calendar"></i> 
                            {{-- Tanggal --}}
                            {{ $event->tgl_selesai ? \Carbon\Carbon::parse($event->tgl_selesai)->format('d M Y') : '-' }} (Selesai)
                        </p>
                        <a href="{{ route('admin.pengumuman.show', $event->id) }}" class="btn btn-outline-secondary btn-sm btn-block">
                            Lihat Arsip
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">Belum ada arsip pengumuman / event.</p>
            </div>
        @endforelse
    </div>
@endsection