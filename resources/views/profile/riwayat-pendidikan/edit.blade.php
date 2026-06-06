@extends($layout)
@section('title', 'Edit Pendidikan')

@section('content')
<div class="{{ auth()->user()->role == 'alumni' ? 'container mt-5' : '' }}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold">Edit Riwayat Pendidikan</div>
                <div class="card-body">
                    <form action="{{ route('riwayat-pendidikan.update', $pendidikan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Nama Institusi / Sekolah / Universitas *</label>
                            <input type="text" name="nama_institusi" class="form-control" required value="{{ old('nama_institusi', $pendidikan->nama_institusi) }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenjang *</label>
                                    <select name="jenjang" class="form-control" required>
                                        <option value="" disabled>-- Pilih Jenjang --</option>
                                        @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3', 'Lainnya'] as $opt)
                                            <option value="{{ $opt }}" {{ $pendidikan->jenjang == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jurusan / Prodi (Opsional)</label>
                                    <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $pendidikan->jurusan) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun Mulai *</label>
                                    <input type="number" name="tahun_mulai" class="form-control" required value="{{ old('tahun_mulai', $pendidikan->tahun_mulai) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun Lulus</label>
                                    <input type="number" name="tahun_selesai" class="form-control" value="{{ old('tahun_selesai', $pendidikan->tahun_selesai) }}">
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