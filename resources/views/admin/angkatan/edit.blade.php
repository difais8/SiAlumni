@extends('layouts.admin')

@section('title', 'Edit Angkatan')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Formulir Edit Angkatan</h4>
                <p class="card-description">
                    Ubah data angkatan di bawah ini.
                </p>
                <form class="forms-sample" action="{{ route('admin.angkatan.update', $angkatan->id) }}" method="POST">
                    @csrf  {{-- Token Keamanan --}}
                    @method('PUT') {{-- Metode HTTP untuk Update --}}

                    <div class="form-group">
                        <label for="jenjang">Jenjang Pendidikan</label>
                        <select class="form-control" id="jenjang" name="jenjang" required>
                            <option value="" disabled>-- Pilih Jenjang --</option>
                            {{-- Gunakan old() dulu, jika tidak ada, baru ambil dari database --}}
                            <option value="SD" {{ old('jenjang', $angkatan->jenjang) == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('jenjang', $angkatan->jenjang) == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ old('jenjang', $angkatan->jenjang) == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="Lainnya" {{ old('jenjang', $angkatan->jenjang) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tahun_masuk">Tahun Masuk</label>
                        <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" 
                               placeholder="Contoh: 2010" value="{{ old('tahun_masuk', $angkatan->tahun_masuk) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_angkatan">Nama Angkatan</label>
                        <input type="text" class="form-control" id="nama_angkatan" name="nama_angkatan" 
                               placeholder="Contoh: Garuda" value="{{ old('nama_angkatan', $angkatan->nama_angkatan) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Update</button>
                    <a href="{{ route('admin.angkatan.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection