@extends($layout)
@section('title', 'Tambah Pekerjaan')

@section('content')
<div class="{{ auth()->user()->role == 'alumni' ? 'container mt-5' : '' }}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold">Tambah Pengalaman Kerja</div>
                <div class="card-body">
                    <form action="{{ route('riwayat-pekerjaan.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nama Perusahaan / Instansi *</label>
                            <input type="text" name="nama_perusahaan" class="form-control" required value="{{ old('nama_perusahaan') }}">
                        </div>
                        <div class="form-group">
                            <label>Jabatan / Posisi *</label>
                            <input type="text" name="jabatan" class="form-control" required value="{{ old('jabatan') }}">
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
                                    <label>Tahun Selesai (Kosongi jika masih aktif)</label>
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