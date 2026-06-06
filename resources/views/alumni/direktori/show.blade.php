@extends('layouts.alumni')
@section('title', 'Profil Alumni')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <h3 class="font-weight-bold mb-3">Detail Alumni</h3>
        <div class="mb-3">
            <a href="{{ route('alumni.direktori.index') }}" class="btn btn-light btn-sm"><i class="mdi mdi-arrow-left"></i> Kembali</a>
        </div>

        <div class="card shadow-lg">
            <div class="card-body text-center pt-5 pb-4">
                <img src="{{ $alumni->profile->foto_profil_path ? asset('storage/' . $alumni->profile->foto_profil_path) : asset('images/aset/usern.png') }}" 
                     class="img-fluid img-clickable rounded shadow mb-3" style="max-width: 300px; max-height: 300px; object-fit: cover;">
                
                <h3 class="font-weight-bold text-dark mb-1">{{ $alumni->profile->nama_lengkap }}</h3>
                <p class="text-muted">{{ $alumni->username }}</p>
                
                <div class="badge badge-info px-3 py-2 mt-2">
                    @if($alumni->role == 'alumni') Alumni 
                    @elseif($alumni->role == 'pengelola') Pengelola
                    @elseif($alumni->role == 'ketua_angkatan') 
                        Ketua Angkatan
                        <br>
                        {{ $alumni->profile->jabatanAngkatan->jenjang}} {{ $alumni->profile->jabatanAngkatan->tahun_masuk}} - {{ $alumni->profile->jabatanAngkatan->nama_angkatan}}
                    @elseif($alumni->role == 'ketua_alumni') Ketua Alumni
                    @endif
                    
                </div>
            </div>

            <hr class="my-0">

            <div class="card-body ml-2">
                <h5 class="font-weight-bold mb-3"><i class="mdi mdi-information-outline"></i> Informasi</h5>
                
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Angkatan</div>
                    @if($alumni->profile->angkatan)
                        <div class="col-sm-8">
                            {{ $alumni->profile->angkatan->jenjang }} {{ $alumni->profile->angkatan->tahun_masuk }} - {{ $alumni->profile->angkatan->nama_angkatan }}
                        </div>
                        @if($alumni->profile->angkatan2)
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                {{ $alumni->profile->angkatan2->jenjang }} {{ $alumni->profile->angkatan2->tahun_masuk }} - {{ $alumni->profile->angkatan2->nama_angkatan }}
                            </div>
                        @endif
                        @if($alumni->profile->angkatan3)
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                {{ $alumni->profile->angkatan3->jenjang }} {{ $alumni->profile->angkatan3->tahun_masuk }} - {{ $alumni->profile->angkatan3->nama_angkatan }}
                            </div>
                        @endif
                    @else
                        <div class="col-sm-8">
                            <span class="text-danger">Belum memilih angkatan</span>
                        </div>
                    @endif
                </div>

                {{-- DATA SENSITIF / LENGKAP (HANYA UNTUK ADMIN/KETUA) --}}
                @if($isPrivileged)
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">Email</div>
                        <div class="col-sm-8">{{ $alumni->email }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">No HP</div>
                        <div class="col-sm-8">{{ $alumni->profile->nomor_telepon ?? '-' }}</div>
                    </div>
                    
                    {{-- Riwayat Pekerjaan (Hanya Admin/Ketua) --}}
                    <h6 class="font-weight-bold mt-4 mb-3">Riwayat Pekerjaan</h6>
                    <ul class="list-group list-group-flush mb-3">
                        @forelse($alumni->profile->riwayatPekerjaan as $kerja)
                            <li class="list-group-item px-0">
                                <strong>{{ $kerja->nama_perusahaan }}</strong> - {{ $kerja->jabatan }} 
                                <small class="d-block text-muted">{{ $kerja->tahun_mulai }} - {{ $kerja->tahun_selesai ?? 'Sekarang' }}</small>
                            </li>
                        @empty
                            <li class="text-muted small">Tidak ada data.</li>
                        @endforelse
                    </ul>

                    {{-- Riwayat Pendidikan (Hanya Admin/Ketua) --}}
                    <h6 class="font-weight-bold mt-4 mb-3">Riwayat Pendidikan</h6>
                    <ul class="list-group list-group-flush mb-3">
                        @forelse($alumni->profile->riwayatPendidikan as $pendidikan)
                            <li class="list-group-item px-0">
                                <strong>{{ $pendidikan->nama_institusi }}</strong> - {{ $pendidikan->jenjang }} {{ $pendidikan->jurusan }}
                                <small class="d-block text-muted">{{ $pendidikan->tahun_mulai }} - {{ $pendidikan->tahun_selesai ?? 'Sekarang' }}</small>
                            </li>
                        @empty
                            <li class="text-muted small">Tidak ada data.</li>
                        @endforelse
                    </ul>
                @else
                    {{-- PESAN UNTUK ALUMNI BIASA --}}
                    <div class="alert alert-light mt-4 border text-center">
                        <small class="text-muted"><i class="mdi mdi-lock"></i> Informasi kontak dan riwayat detail disembunyikan untuk privasi.</small>
                    </div>
                @endif

                {{-- GALERI (SEMUA BISA LIHAT) --}}
                <h5 class="font-weight-bold mt-5 mb-3"><i class="mdi mdi-image-multiple"></i> Galeri Foto</h5>
                <div class="row">
                    @forelse($alumni->profile->galeri as $foto)
                        <div class="col-6 col-md-3 mb-3">
                            <div class="card border-0">
                                <img src="{{ asset('storage/' . $foto->file_path) }}" class="card-img-top rounded img-clickable" alt="foto" style="height: 150px; object-fit: cover;">
                                <small class="text-muted mt-1 d-block text-center">{{ $foto->keterangan ?? 'Tidak ada keterangan' }}</small>
                                <small class="text-muted mt-1 d-block text-center">{{ $foto->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted mb-3">Belum ada foto.</div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</div>
@endsection