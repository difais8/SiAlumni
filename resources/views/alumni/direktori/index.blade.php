@extends('layouts.alumni')
@section('title', 'Direktori Alumni')

@section('content')

<h3 class="font-weight-bold text-dark mb-4">Direktori Alumni</h3>

{{-- FILTER SECTION --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form action="{{ route('alumni.direktori.index') }}" method="GET" class="d-flex align-items-center">
            <div class="mr-3 font-weight-bold text-dark">Filter Angkatan:</div>
            <div class="flex-grow-1">
                <select name="angkatan" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Semua Angkatan --</option>
                    @foreach($semuaAngkatan as $angk)
                        <option value="{{ $angk->id }}" {{ request('angkatan') == $angk->id ? 'selected' : '' }}>
                            Angkatan {{ $angk->tahun_masuk }} ({{ $angk->nama_angkatan }})
                        </option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->role == 'pengelola' && !request('angkatan'))
                <div class="ml-3 text-danger small">
                    *Pengelola wajib memilih angkatan
                </div>
            @endif
        </form>
    </div>
</div>

{{-- TABLE SECTION --}}
<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="card-title mb-4">Daftar Alumni</h4>
        <div class="table-responsive">
            <table id="tabel-direktori" class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th class="text-center">Nama Lengkap</th>
                        <th>Angkatan</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumni as $a)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ $a->profile->foto_profil_thumbnail ? asset('storage/' . $a->profile->foto_profil_thumbnail) : asset('images/aset/usern.png') }}" 
                                alt="foto" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        </td>
                        <td>{{ $a->profile->nama_lengkap }}</td>
                        <td>
                            @if($a->profile->angkatan)
                                {{ $a->profile->angkatan->jenjang }} {{ $a->profile->angkatan->tahun_masuk }} - {{ $a->profile->angkatan->nama_angkatan }}
                                @if($a->profile->angkatan2)
                                    <br>
                                    {{ $a->profile->angkatan2->jenjang }} {{ $a->profile->angkatan2->tahun_masuk }} - {{ $a->profile->angkatan2->nama_angkatan }}
                                @endif
                                @if($a->profile->angkatan3)
                                    <br>
                                    {{ $a->profile->angkatan3->jenjang }} {{ $a->profile->angkatan3->tahun_masuk }} - {{ $a->profile->angkatan3->nama_angkatan }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($a->role == 'ketua_angkatan')
                                Ketua Angkatan
                                <br>
                                {{ $a->profile->jabatanAngkatan->jenjang}} {{ $a->profile->jabatanAngkatan->tahun_masuk}} - {{ $a->profile->jabatanAngkatan->nama_angkatan}}
                            @elseif($a->role == 'ketua_alumni')
                                Ketua Alumni
                            @else
                                Alumni
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('alumni.direktori.show', $a->id) }}" class="btn btn-sm btn-primary rounded-pill">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        inisialisasiTabel('#tabel-direktori', konfigurasiDirektoriAlumni);
    });
</script>
@endpush
