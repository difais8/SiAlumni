@extends($layout)
@section('title', 'Profil Saya')

@section('content')
@if(session('success'))
    <div class="alert  alert-success alert-dismissible fade show mb-3">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif
@if(session('error'))
    <div class="alert  alert-danger alert-dismissible fade show mb-3">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif


<h3 class="font-weight-bold mb-4">Profil Saya</h3>

@if (session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Profil berhasil diperbarui!
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row">
    {{-- KOLOM KIRI: FOTO & TOMBOL EDIT --}}
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-4">
            <div class="mb-3">
                <img src="{{ $user->profile->foto_profil_path ? asset('storage/' . $user->profile->foto_profil_path) : asset('images/aset/usern.png') }}" 
                     alt="Foto Profil" 
                     class="img-fluid img-clickable rounded shadow" 
                     style="max-width: 300px; max-height: 300px; object-fit: cover;">
            </div>
            <h3 class="font-weight-bold text-dark">{{ $user->username}}</h3>
            <h6 class="col-md-6 text-center mx-auto badge badge-info shadow-sm px-3 py-2 mb-3 mt-2">
                @if($user->role == 'ketua_angkatan')
                        Ketua Angkatan
                        <br>
                        {{ $user->profile->jabatanAngkatan->jenjang }} {{ $user->profile->jabatanAngkatan->tahun_masuk }} - {{ $user->profile->jabatanAngkatan->nama_angkatan}}
                @elseif($user->role == 'ketua_alumni')Ketua Alumni
                @else{{ ucfirst($user->role) }}</span>
                @endif
            </h6>

            <h6 class="text-muted">{{$user->email}}</h6>

            <hr class="my-4">

            {{-- TOMBOL AKSI --}}
            <div class="d-grid gap-2">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-block mb-2">
                    <i class="mdi mdi-pencil"></i> Edit Profil & Foto
                </a>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: INFO DETAIL --}}
    <div class="col-md-8">
        
        {{-- 1. DATA DIRI --}}
        <div class="card shadow-sm mb-4 p-1">
            <div class="card-header bg-white">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="mdi mdi-account-card-details"></i> Data Diri</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Nama Lengkap</div>
                    <div class="col-sm-8 font-weight-bold">{{ $user->profile->nama_lengkap }}</div>
                </div>
                @if($user->role != 'pengelola')
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">Angkatan</div>
                        <div class="col-sm-8 font-weight-bold">
                            @if($user->profile->angkatan)
                                <div class="mb-1"><span class="badge badge-primary">1</span> {{ $user->profile->angkatan->jenjang }} {{ $user->profile->angkatan->tahun_masuk }} {{$user->profile->angkatan->nama_angkatan}}</div>
                            @endif
                            @if($user->profile->angkatan2)
                                <div class="mb-1"><span class="badge badge-info">2</span> {{ $user->profile->angkatan2->jenjang }} {{ $user->profile->angkatan2->tahun_masuk }} {{$user->profile->angkatan2->nama_angkatan}}</div>
                            @endif
                            @if($user->profile->angkatan3)
                                <div class="mb-1"><span class="badge badge-secondary">3</span> {{ $user->profile->angkatan3->jenjang }} {{ $user->profile->angkatan3->tahun_masuk }} {{$user->profile->angkatan3->nama_angkatan}}</div>
                            @endif
                            @if(!$user->profile->angkatan)
                                <span class="text-danger">Belum diatur</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">Email publik</div>
                        <div class="col-sm-8 font-weight-bold">{{ $user->profile->email_publik ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">No HP</div>
                        <div class="col-sm-8 font-weight-bold">{{ $user->profile->nomor_telepon ?? '-' }}</div>
                    </div>
                @endif
            </div>
        </div>

        @if($user->role != 'pengelola')
            {{-- 2. RIWAYAT PEKERJAAN --}}
            <div class="card shadow-sm mb-4 p-1">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="mdi mdi-briefcase"></i> Riwayat Pekerjaan</h5>
                    <a href="{{ route('riwayat-pekerjaan.create') }}" class="btn btn-sm btn-outline-primary"><i class="mdi mdi-plus"></i> Tambah</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($user->profile->riwayatPekerjaan as $kerja)
                            <li class="list-group-item">
                                <h6 class="mb-1 font-weight-bold">{{ $kerja->nama_perusahaan }}</h6>
                                <p class="mb-0 text-muted small">{{ $kerja->jabatan }} | {{ $kerja->tahun_mulai }} - {{ $kerja->tahun_selesai ?? 'Sekarang' }}</p>
                                <div>
                                    <a href="{{ route('riwayat-pekerjaan.edit', $kerja->id) }}" class="text-warning mr-2">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('riwayat-pekerjaan.destroy', $kerja->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 " style="border:none; background:none;">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-3">Belum ada data pekerjaan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- 3. RIWAYAT PENDIDIKAN --}}
            <div class="card shadow-sm mb-4 p-1">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="mdi mdi-school"></i> Riwayat Pendidikan</h5>
                    <a href="{{ route('riwayat-pendidikan.create') }}" class="btn btn-sm btn-outline-primary"><i class="mdi mdi-plus"></i> Tambah</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($user->profile->riwayatPendidikan as $pendidikan)
                            <li class="list-group-item">
                                <h6 class="mb-1 font-weight-bold">{{ $pendidikan->nama_institusi }}</h6>
                                <p class="mb-0 text-muted small">
                                    {{ $pendidikan->jenjang }} {{ $pendidikan->jurusan }} | {{ $pendidikan->tahun_mulai }} - {{ $pendidikan->tahun_selesai ?? 'Sekarang' }}
                                </p>
                                <div>
                                    <a href="{{ route('riwayat-pendidikan.edit', $pendidikan->id) }}" class="text-warning mr-2">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('riwayat-pendidikan.destroy', $pendidikan->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0" style="border:none; background:none;">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-3">Belum ada data pendidikan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- 4. GALERI PENGGUNA --}}
            <div class="card shadow-sm mb-4 p-1">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="mdi mdi-image-multiple"></i> Galeri Foto</h5>
                    
                    {{-- Tombol Trigger Modal Upload --}}
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#uploadGaleriModal">
                        <i class="mdi mdi-upload"></i> Upload Foto
                    </button>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        @forelse ($user->profile->galeri as $foto)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    {{-- Gambar --}}
                                    <div style="height: 200px; overflow: hidden; border-radius: 8px 8px 0 0; position: relative;" class="bg-light">
                                        <img src="{{ asset('storage/' . $foto->file_path) }}" 
                                            alt="Galeri" 
                                            class="img-clickable"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                        
                                        {{-- Tombol Hapus --}}
                                        <div class="position-absolute" style="top: 10px; right: 10px;">
                                            <form action="{{ route('galeri.destroy', $foto->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Hapus Foto">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    {{-- Caption / Keterangan --}}
                                    <div class="card-body p-3 text-center">
                                        <p class="card-text text-muted mb-0">
                                            {{ $foto->keterangan ?? 'Tanpa keterangan' }}
                                        </p>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            Diunggah {{ $foto->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="mdi mdi-image-off" style="font-size: 3rem;"></i>
                                <p>Belum ada foto di galeri Anda.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>


            <div class="modal fade" id="uploadGaleriModal" tabindex="-1" role="dialog" aria-labelledby="uploadGaleriLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadGaleriLabel">Upload Foto ke Galeri</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('galeri.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0 small">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            $('#uploadGaleriModal').modal('show');
                                        });
                                    </script>
                                @endif
                                <div class="form-group">
                                    <label>Pilih Foto *</label>
                                    <input type="file" name="foto" class="form-control-file" accept="image/*" required>
                                    <small class="text-muted">Format: JPG, PNG. Maks: 5MB.</small>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan / Caption (Opsional)</label>
                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan atau caption untuk foto"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

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