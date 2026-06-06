@extends('layouts.admin')

@section('title', 'Manajemen Alumni')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<h3 class="font-weight-bold text-dark mb-4">Data Akun Alumni</h3>

{{-- FILTER SECTION --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.alumni.index') }}" class="d-flex align-items-center">
            <label class="mr-3 font-weight-bold text-dark">Filter Angkatan:</label>
            <select name="angkatan" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Angkatan</option>
                @foreach($semuaAngkatan as $angk)
                    <option value="{{ $angk->id }}" {{ request('angkatan') == $angk->id ? 'selected' : '' }}>
                        {{ $angk->tahun_masuk }} - {{ $angk->nama_angkatan }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>



<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card shadow-sm">
            <div class="card-body">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Daftar Alumni</h4>
                    <a href="{{ route('admin.alumni.export', ['angkatan' => request('angkatan')]) }}" class="btn btn-success icon-btn">
                        <i class="mdi mdi-file-excel"></i> Export Excel
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="tabel" class="table table-hover">
                        <thead class="theadMC">
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Angkatan</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($semuaAlumni as $user)
                                <tr>
                                    <td></td>
                                    <td>
                                        <img src="{{ $user->profile->foto_profil_thumbnail ? asset('storage/' . $user->profile->foto_profil_thumbnail) : asset('images/aset/usern.png') }}" 
                                        alt="foto" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td>{{ $user->profile->nama_lengkap ?? 'Belum Diisi' }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>
                                        @if ($user->profile->angkatan)
                                            {{ $user->profile->angkatan->jenjang }} - 
                                            {{ $user->profile->angkatan->tahun_masuk }}
                                            ({{ $user->profile->angkatan->nama_angkatan }})
                                            @if ($user->profile->angkatan2)
                                            <br>
                                            {{ $user->profile->angkatan2->jenjang }} - 
                                            {{ $user->profile->angkatan2->tahun_masuk }}
                                            ({{ $user->profile->angkatan2->nama_angkatan }})
                                            @endif
                                            @if ($user->profile->angkatan3)
                                            <br>
                                            {{ $user->profile->angkatan3->jenjang }} - 
                                            {{ $user->profile->angkatan3->tahun_masuk }}
                                            ({{ $user->profile->angkatan3->nama_angkatan }})
                                            @endif
                                        @else
                                            <span>Belum Diisi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->role == 'ketua_angkatan')
                                            <span class="badge badge-primary">
                                                Ketua Angkatan
                                                <br>
                                                {{ $user->profile->jabatanAngkatan->jenjang}} {{ $user->profile->jabatanAngkatan->tahun_masuk}} - {{ $user->profile->jabatanAngkatan->nama_angkatan}}
                                            </span>
                                        @elseif ($user->role == 'ketua_alumni')
                                            <span class="badge" style="background-color: #49aca1; color:white;">Ketua Alumni</span>
                                        @else
                                            <span class="badge badge-info">Alumni</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.alumni.edit', $user->id) }}" class="btn btn-info btn-icon-text btn-sm">
                                            <i class="mdi mdi-information-outline"></i>
                                        </a>

                                        <form id="form-hapus-{{ $user->id }}" 
                                            action="{{ route('admin.alumni.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type_="button" class="btn btn-danger btn-icon-text btn-sm btn-hapus-swal"
                                                    data-id="{{ $user->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Pemicu DataTables & Swal --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        inisialisasiTabel('#tabel', konfigurasiPengaturanDataAlumni);
        inisialisasiTombolHapus(
            '#tabel tbody',       // Selector <tbody>
            'form-hapus',                     // Prefix ID form
            'Akun ini akan dihapus permanen!' // Pesan konfirmasi
        );
    });
</script>
@endpush