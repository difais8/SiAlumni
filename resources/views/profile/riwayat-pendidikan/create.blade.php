@extends($layout)
@section('title', 'Tambah Pendidikan')

@section('content')
<div class="{{ auth()->user()->role == 'alumni' ? 'container mt-5' : '' }}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold">Tambah Riwayat Pendidikan</div>
                <div class="card-body">
                    <form action="{{ route('riwayat-pendidikan.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label>Nama Institusi / Sekolah / Universitas *</label>
                            <input type="text" name="nama_institusi" class="form-control" required value="{{ old('nama_institusi') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenjang *</label>
                                    <select name="jenjang" class="form-control" required>
                                        <option value="" disabled selected>-- Pilih Jenjang --</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA / SMK</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1 / D4</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jurusan / Prodi (Opsional)</label>
                                    <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun Mulai *</label>
                                    <input type="number" name="tahun_mulai" class="form-control" required value="{{ old('tahun_mulai') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun Lulus (Kosongi jika masih studi)</label>
                                    <input type="number" name="tahun_selesai" class="form-control" value="{{ old('tahun_selesai') }}">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('profile.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection