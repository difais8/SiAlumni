@extends($layout)
@section('title', 'Edit Pekerjaan')

@section('content')
<div class="{{ auth()->user()->role == 'alumni' ? 'container mt-5' : '' }}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold">Edit Pengalaman Kerja</div>
                <div class="card-body">
                    <form action="{{ route('riwayat-pekerjaan.update', $pekerjaan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Nama Perusahaan / Instansi *</label>
                            <input type="text" name="nama_perusahaan" class="form-control" required value="{{ old('nama_perusahaan', $pekerjaan->nama_perusahaan) }}">
                        </div>
                        <div class="form-group">
                            <label>Jabatan / Posisi *</label>
                            <input type="text" name="jabatan" class="form-control" required value="{{ old('jabatan', $pekerjaan->jabatan) }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun Mulai *</label>
                                    <input type="number" name="tahun_mulai" class="form-control" required value="{{ old('tahun_mulai', $pekerjaan->tahun_mulai) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun Selesai</label>
                                    <input type="number" name="tahun_selesai" class="form-control" value="{{ old('tahun_selesai', $pekerjaan->tahun_selesai) }}">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('profile.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection