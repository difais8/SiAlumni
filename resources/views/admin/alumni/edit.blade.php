@extends('layouts.admin')

@section('title', 'Detail Alumni: ' . $alumni->profile->nama_lengkap)

@section('content')

{{-- 1. KARTU INFO UTAMA (TETAP SAMA) --}}
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Informasi Profil</h4>
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ $alumni->profile->foto_profil_path ? asset('storage/'.$alumni->profile->foto_profil_path) : asset('images/aset/usern.png') }}" 
                             class="img-fluid img-clickable shadow rounded" alt="Foto Profil" style="max-width: 300px; max-height: 300px; object-fit: cover;">
                        <h6 class="badge badge-info shadow-sm mt-3 px-3 py-2 col-md-6 mx-auto">
                            @if($alumni->role == 'alumni') Alumni 
                            @elseif($alumni->role == 'pengelola') Pengelola
                            @elseif($alumni->role == 'ketua_angkatan')
                                Ketua Angkatan
                                <br>
                                {{ $alumni->profile->jabatanAngkatan->jenjang}} {{ $alumni->profile->jabatanAngkatan->tahun_masuk}} - {{ $alumni->profile->jabatanAngkatan->nama_angkatan}}
                            @elseif($alumni->role == 'ketua_alumni') Ketua Alumni
                            @endif
                        </h6>
                    </div>
                    <div class="col-md-9">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Nama Lengkap:</strong>
                                <span>{{ $alumni->profile->nama_lengkap ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Username:</strong>
                                <span>{{ $alumni->username }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Email Akun:</strong>
                                <span>{{ $alumni->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Email Publik:</strong>
                                <span>{{ $alumni->profile->email_publik ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Angkatan:</strong>
                                <span>
                                    @if($alumni->profile->angkatan)
                                        {{ $alumni->profile->angkatan->jenjang }} - {{ $alumni->profile->angkatan->tahun_masuk }} ({{ $alumni->profile->angkatan->nama_angkatan }})
                                        @if($alumni->profile->angkatan2)
                                            <br>
                                            {{ $alumni->profile->angkatan2->jenjang }} - {{ $alumni->profile->angkatan2->tahun_masuk }} ({{ $alumni->profile->angkatan2->nama_angkatan }})
                                        @endif
                                        @if($alumni->profile->angkatan3)
                                            <br>
                                            {{ $alumni->profile->angkatan3->jenjang }} - {{ $alumni->profile->angkatan3->tahun_masuk }} ({{ $alumni->profile->angkatan3->nama_angkatan }})
                                        @endif
                                    @else
                                        <span class="text-danger">Belum memilih angkatan</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Nomor Telepon:</strong>
                                <span>{{ $alumni->profile->nomor_telepon ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 2. KARTU RIWAYAT PEKERJAAN (TETAP SAMA) --}}
<div class="row mt-4">
    <div class="col-md-12 grid-margin">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Riwayat Pekerjaan</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Perusahaan</th>
                                <th>Jabatan</th>
                                <th>Tahun Mulai</th>
                                <th>Tahun Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($alumni->profile->riwayatPekerjaan as $pekerjaan)
                                <tr>
                                    <td>{{ $pekerjaan->nama_perusahaan }}</td>
                                    <td>{{ $pekerjaan->jabatan }}</td>
                                    <td>{{ $pekerjaan->tahun_mulai }}</td>
                                    <td>{{ $pekerjaan->tahun_selesai ?? 'Sekarang' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">Belum ada data riwayat pekerjaan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. KARTU RIWAYAT PENDIDIKAN (TETAP SAMA) --}}
<div class="row mt-4">
    <div class="col-md-12 grid-margin">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Riwayat Pendidikan</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Institusi</th>
                                <th>Jenjang</th>
                                <th>Jurusan</th>
                                <th>Tahun Mulai</th>
                                <th>Tahun Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($alumni->profile->riwayatPendidikan as $pendidikan)
                                <tr>
                                    <td>{{ $pendidikan->nama_institusi }}</td>
                                    <td>{{ $pendidikan->jenjang }}</td>
                                    <td>{{ $pendidikan->jurusan ?? '-' }}</td>
                                    <td>{{ $pendidikan->tahun_mulai }}</td>
                                    <td>{{ $pendidikan->tahun_selesai ?? 'Sekarang' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">Belum ada data riwayat pendidikan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 4. KARTU GALERI PENGGUNA (TETAP SAMA) --}}
<div class="row mt-4">
    <div class="col-md-12 grid-margin">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Galeri Foto</h4>
                <div class="row">
                    @forelse ($alumni->profile->galeri as $foto)
                        <div class="col-md-3 mb-3">
                            <div class="card h-100 border-0 shadow">
                                <div style="max-height: 300px; overflow: hidden; border-radius: 8px 8px 0 0; position: relative;" class="bg-light">
                                    <img src="{{ asset('storage/'.$foto->file_path) }}" 
                                         class="img-fluid img-clickable" alt="{{ $foto->keterangan }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
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
                        <div class="col-12 text-center">
                            <p>Belum ada foto di galeri.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 5. KARTU UNTUK EDIT ROLE (UPDATED) --}}
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Edit Role Alumni</h4>
                <form class="forms-sample" action="{{ route('admin.alumni.update', $alumni->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Input Role --}}
                    <div class="form-group">
                        <label for="roleSelect">Role Saat Ini</label>
                        {{-- Perubahan ID ke roleSelect dan penambahan onchange --}}
                        <select class="form-control" id="roleSelect" name="role" onchange="toggleJabatan()">
                            <option value="alumni" {{ old('role', $alumni->role) == 'alumni' ? 'selected' : '' }}>Alumni</option>
                            <option value="ketua_angkatan" {{ old('role', $alumni->role) == 'ketua_angkatan' ? 'selected' : '' }}>Ketua Angkatan</option>
                            <option value="ketua_alumni" {{ old('role', $alumni->role) == 'ketua_alumni' ? 'selected' : '' }}>Ketua Alumni</option>
                        </select>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Input Jabatan Angkatan (BARU: Hidden by Default) --}}
                    <div class="form-group" id="jabatanInput" style="display: none;">
                        <label class="text-danger font-weight-bold">Menjabat Sebagai Ketua Angkatan:</label>
                        <select name="jabatan_angkatan_id" class="form-control">
                            <option value="">-- Pilih Angkatan --</option>
                            {{-- Menggunakan $semuaAngkatan dari controller --}}
                            @foreach($semuaAngkatan as $angk)
                                <option value="{{ $angk->id }}" 
                                    {{ (old('jabatan_angkatan_id') ?? $alumni->profile->jabatan_angkatan_id) == $angk->id ? 'selected' : '' }}>
                                    {{ $angk->tahun_masuk }} - {{ $angk->nama_angkatan }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih angkatan yang akan dipimpin oleh user ini.</small>
                        @error('jabatan_angkatan_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Update Role</button>
                    <a href="{{ route('admin.alumni.index') }}" class="btn btn-light">Kembali ke List</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleJabatan() {
        var roleSelect = document.getElementById('roleSelect');
        var jabatanDiv = document.getElementById('jabatanInput');
        
        // Pastikan elemen ditemukan untuk menghindari error JS
        if(roleSelect && jabatanDiv) {
            var role = roleSelect.value;
            if (role === 'ketua_angkatan') {
                jabatanDiv.style.display = 'block';
            } else {
                jabatanDiv.style.display = 'none';
            }
        }
    }

    // Jalankan saat halaman load (agar status awal tersesuaikan jika sedang edit user ketua_angkatan)
    document.addEventListener("DOMContentLoaded", function() {
        toggleJabatan();
    });
</script>
@endpush