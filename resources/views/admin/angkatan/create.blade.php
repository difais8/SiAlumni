@extends('layouts.admin')

@section('title', 'Tambah Angkatan Baru')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Formulir Angkatan Baru</h4>
                <p class="card-description">
                    Masukkan detail angkatan di bawah ini.
                </p>


                {{-- Formulir Create --}}
                <form class="forms-sample" action="{{ route('admin.angkatan.store') }}" method="POST">
                    @csrf  {{-- Wajib untuk keamanan Laravel --}}

                    <div class="form-group">
                        <label for="jenjang">Jenjang Pendidikan</label>
                        <select class="form-control" id="jenjang" name="jenjang" required>
                            <option value="" disabled selected>-- Pilih Jenjang --</option>
                            <option value="SD" {{ old('jenjang') == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ old('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="Lainnya" {{ old('jenjang') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tahun_masuk">Tahun Masuk</label>
                        <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" 
                               placeholder="Contoh: 2010" value="{{ old('tahun_masuk') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_angkatan">Nama Angkatan</label>
                        <input type="text" class="form-control" id="nama_angkatan" name="nama_angkatan" 
                               placeholder="Contoh: Garuda" value="{{ old('nama_angkatan') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                    <a href="{{ route('admin.angkatan.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection