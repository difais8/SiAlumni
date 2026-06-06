@extends('layouts.alumni') 

@section('title', $pengumuman->judul)

@section('content')

<div class="col-md-12">
    <h3 class="font-weight-bold mb-3">Detail Pengumuman/Event</h3>
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm text-dark">
            <i class="mdi mdi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
        <div class="position-relative bg-light text-center">
            @if($pengumuman->poster_path)
                <img src="{{ asset('storage/' . $pengumuman->poster_path) }}" 
                     alt="Poster Event" 
                     class="img-fluid" 
                     style="max-height: 500px; width: 100%; object-fit: contain; background-color: #e3f2fd;">
            @else
                <div class="py-5">
                    <i class="mdi mdi-image-off text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted">Tidak ada poster</p>
                </div>
            @endif
        </div>

        <div class="card-body px-5 py-5">
            <h2 class="font-weight-bold text-dark mb-3">{{ $pengumuman->judul }}</h2>
            <div class="d-flex flex-wrap text-muted mb-4 align-items-center">
                <div class="mr-4 mb-2">
                    <i class="mdi mdi-calendar text-primary mr-1"></i> 
                    {{ $pengumuman->tgl_mulai ? \Carbon\Carbon::parse($pengumuman->tgl_mulai)->translatedFormat('d F Y') : '-' }}
                    @if($pengumuman->tgl_selesai && $pengumuman->tgl_selesai != $pengumuman->tgl_mulai)
                        s/d {{ \Carbon\Carbon::parse($pengumuman->tgl_selesai)->translatedFormat('d F Y') }}
                    @endif
                </div>
                <div class="mr-4 mb-2">
                    <i class="mdi mdi-clock text-primary mr-1"></i>
                    {{ $pengumuman->waktu_mulai ? \Carbon\Carbon::parse($pengumuman->waktu_mulai)->format('H:i') : '' }}
                    {{ $pengumuman->waktu_selesai ? ' - ' . \Carbon\Carbon::parse($pengumuman->waktu_selesai)->format('H:i') : '' }}
                    WIB
                </div>
                <div class="mb-2">
                    <i class="mdi mdi-account-edit text-primary mr-1"></i>
                    Diposting oleh: {{ $pengumuman->author->profile->nama_lengkap ?? 'Pengurus' }}
                </div>
            </div>
            
            <hr>

            <div class="row mt-4">
                {{-- KOLOM KIRI: KONTEN --}}
                <div class="col-md-8 mb-4">
                    <h4 class="mb-3 font-weight-bold">Deskripsi Event</h4>
                    <div class="content-body text-justify text-dark" style="font-size: 1.05rem; line-height: 1.8;">
                        {!! $pengumuman->isi_konten !!}
                    </div>
                </div>

                {{-- KOLOM KANAN: INFO TAMBAHAN --}}
                <div class="col-md-4">
                    <div class="bg-light p-4 rounded shadow-sm">
                        <div class="mb-4">
                            <h6 class="font-weight-bold text-uppercase text-secondary mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">Lokasi</h6>
                            <p class="text-dark font-weight-bold mb-0">
                                <i class="mdi mdi-map-marker text-danger mr-1"></i>
                                {{ $pengumuman->lokasi ?? 'Lokasi belum ditentukan' }}
                            </p>
                        </div>
                        <div class="mb-2">
                            <h6 class="font-weight-bold text-uppercase text-secondary mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">Sosial Media / Link</h6>
                            <p class="text-dark mb-0">
                                {{ $pengumuman->sosial_media ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection