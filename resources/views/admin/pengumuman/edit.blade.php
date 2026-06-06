@extends('layouts.admin')
@section('title', 'Edit Pengumuman')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Pengumuman</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form class="forms-sample" action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="judul">Judul Pengumuman *</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required>
                    </div>

                    {{-- Tampilkan Poster Lama --}}
                    <div class="form-group">
                        <label>Poster Saat Ini</label><br>
                        @if($pengumuman->poster_path)
                            <img src="{{ asset('storage/' . $pengumuman->poster_path) }}" alt="Poster Lama" style="height: 150px; border-radius: 8px; margin-bottom: 10px;">
                        @else
                            <span class="text-muted">Belum ada poster</span>
                        @endif
                        
                        <div class="mt-2">
                            <label>Ganti Poster (Opsional)</label>
                            <input type="file" name="poster" class="form-control file-upload-info" accept="image/*">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah poster.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tgl_mulai" value="{{ old('tgl_mulai', $pengumuman->tgl_mulai) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" class="form-control" name="tgl_selesai" value="{{ old('tgl_selesai', $pengumuman->tgl_selesai) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Waktu Mulai</label>
                                <input type="time" class="form-control" name="waktu_mulai" value="{{ old('waktu_mulai', $pengumuman->waktu_mulai ? \Carbon\Carbon::parse($pengumuman->waktu_mulai)->format('H:i') : '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Waktu Selesai</label>
                                <input type="time" class="form-control" name="waktu_selesai" value="{{ old('waktu_selesai', $pengumuman->waktu_selesai ? \Carbon\Carbon::parse($pengumuman->waktu_selesai)->format('H:i') : '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi Event</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ old('lokasi', $pengumuman->lokasi) }}">
                    </div>

                    <div class="form-group">
                        <label for="sosial_media">Info Sosial Media / Link</label>
                        <input type="text" class="form-control" id="sosial_media" name="sosial_media" value="{{ old('sosial_media', $pengumuman->sosial_media) }}">
                    </div>

                    <div class="form-group">
                        <label for="isi_konten">Isi Konten *</label>
                        <textarea class="form-control" id="summernote" name="isi_konten" rows="4">{{ old('isi_konten', $pengumuman->isi_konten) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Update</button>
                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Copy Script Summernote dari Create Blade di sini --}}
@push('scripts')
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Tulis detail pengumuman di sini...',
            tabsize: 2,
            height: 300, // Tinggi editor
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush