@extends('layouts.admin')
@section('title', $pengumuman->judul)

@section('content')
<h3 class="font-weight-bold">Detail Pengumuman/Event</h3>
<div class="mb-4">
    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-light btn-sm">
        <i class="mdi mdi-arrow-left"></i> Kembali ke List
    </a>
</div>

<div class="card shadow-lg">
    
    {{-- BAGIAN ATAS: POSTER / BANNER --}}
    <div class="position-relative" style="background-color: #e3f2fd; min-height: 300px;">
        @if($pengumuman->poster_path)
        <img src="{{ asset('storage/' . $pengumuman->poster_path) }}" 
            alt="Poster Event" 
            class="w-100 h-100" 
            style="object-fit: contain; max-height: 500px; display: block; margin: 0 auto;">
        @else
        <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
            <h3 class="text-muted">Tidak ada poster</h3>
        </div>
        @endif
    </div>
    
    <div class="card-body px-5 py-5">
        
        {{-- JUDUL BESAR --}}
        <h1 class="font-weight-bold text-dark mb-2">{{ $pengumuman->judul }}</h1>
        
        {{-- INFO TANGGAL & WAKTU SINGKAT --}}
        <div class="d-flex text-muted mb-4">
            <div class="mr-4">
                <i class="mdi mdi-calendar"></i> 
                @if($pengumuman->tgl_mulai == $pengumuman->tgl_selesai)
                {{ $pengumuman->tgl_mulai ? \Carbon\Carbon::parse($pengumuman->tgl_mulai)->translatedFormat('d F Y') : 'TBA' }}
                @else
                {{ $pengumuman->tgl_mulai ? \Carbon\Carbon::parse($pengumuman->tgl_mulai)->translatedFormat('d F Y') : 'TBA' }}
                {{ $pengumuman->tgl_selesai ? ' - ' . \Carbon\Carbon::parse($pengumuman->tgl_selesai)->translatedFormat('d F Y') : 'TBA' }}
                @endif
            </div>
            <div>
                <i class="mdi mdi-clock"></i>
                {{ $pengumuman->waktu_mulai ? \Carbon\Carbon::parse($pengumuman->waktu_mulai)->format('H:i') : '' }}
                {{ $pengumuman->waktu_selesai ? ' - ' . \Carbon\Carbon::parse($pengumuman->waktu_selesai)->format('H:i') : '' }}
                WIB
            </div>
        </div>
        
        <hr>
        
        <div class="row mt-4">
            {{-- KOLOM KIRI: KONTEN SUMMERNOTE --}}
            <div class="col-md-8">
                <h4 class="mb-3 font-weight-bold">Detail Pengumuman</h4>
                <div class="content-body text-justify text-dark">
                    {{-- PENTING: Gunakan {!! !!} untuk merender HTML dari Summernote --}}
                    {!! $pengumuman->isi_konten !!}
                </div>
            </div>
            
            {{-- KOLOM KANAN: SIDEBAR INFO --}}
            <div class="col-md-4">
                <div class="p-4 rounded">
                    
                    {{-- Lokasi --}}
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-secondary">Location</h5>
                        <p class="text-dark mb-0">
                            {{ $pengumuman->lokasi ?? 'Lokasi belum ditentukan' }}
                        </p>
                    </div>
                    
                    {{-- Sosmed / Info Tambahan --}}
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-secondary">Social Media / Link</h5>
                        <p class="text-dark mb-0">
                            {{ $pengumuman->sosial_media ?? '-' }}
                        </p>
                    </div>
                    
                    {{-- Author --}}
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-secondary">Dibuat Oleh</h5>
                        <p class="text-dark mb-0">
                            {{ $pengumuman->author->profile->nama_lengkap ?? 'Admin' }}
                            <br>
                            <small class="text-muted">Pada {{ $pengumuman->created_at->format('d M Y') }}</small>
                        </p>
                    </div>
                    
                    {{-- Tombol Aksi Admin --}}
                    <div class="mt-4 pt-3 border-top">
                        {{-- TOMBOL EDIT --}}
                        <a href="{{ route('admin.pengumuman.edit', $pengumuman->id) }}" class="btn btn-info btn-block mb-2">
                            <i class="mdi mdi-pencil"></i> Edit Pengumuman
                        </a>
                        {{-- TOMBOL HAPUS --}}
                        <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id) }}" 
                            method="POST" 
                            class="d-block w-100 delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="mdi mdi-delete"></i> Hapus Pengumuman
                            </button>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
</div>


@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            inisialisasiTombolHapusV2();
        });
    </script>
@endpush