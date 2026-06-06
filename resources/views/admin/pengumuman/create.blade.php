@extends('layouts.admin')
@section('title', 'Buat Pengumuman Baru')

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
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Buat Pengumuman / Event Baru</h4>
                <p class="card-description">Isi formulir di bawah ini dengan lengkap.</p>


                <form class="forms-sample" action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- 1. JUDUL --}}
                    <div class="form-group">
                        <label for="judul">Judul Pengumuman *</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: Reuni Akbar Angkatan 2010">
                    </div>

                    {{-- 2. POSTER --}}
                    <div class="form-group">
                        <label>Upload Poster (Opsional)</label>
                        <input type="file" name="poster" class="form-control file-upload-info" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, JPEG. Maks: 5MB.</small>
                    </div>

                    {{-- 3. TANGGAL & WAKTU (Grid Layout) --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Mulai *</label>
                                <input type="date" class="form-control" name="tgl_mulai" value="{{ old('tgl_mulai') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Selesai *</label>
                                <input type="date" class="form-control" name="tgl_selesai" value="{{ old('tgl_selesai') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Waktu Mulai</label>
                                <input type="time" class="form-control" name="waktu_mulai" value="{{ old('waktu_mulai') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Waktu Selesai</label>
                                <input type="time" class="form-control" name="waktu_selesai" value="{{ old('waktu_selesai') }}">
                            </div>
                        </div>
                    </div>

                    {{-- 4. LOKASI & SOSMED --}}
                    <div class="form-group">
                        <label for="lokasi">Lokasi Event</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Aula SMA 1, atau Link Zoom">
                    </div>

                    <div class="form-group">
                        <label for="sosial_media">Info Sosial Media / Link Pendaftaran</label>
                        <input type="text" class="form-control" id="sosial_media" name="sosial_media" value="{{ old('sosial_media') }}" placeholder="Contoh: Instagram @alumni_2010 atau bit.ly/daftar">
                    </div>

                    {{-- 5. SUMMERNOTE EDITOR --}}
                    <div class="form-group">
                        <label for="isi_konten">Isi Konten / Deskripsi Lengkap *</label>
                        <textarea class="form-control" id="summernote" name="isi_konten" rows="4">{{ old('isi_konten') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Simpan & Publikasikan</button>
                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Inisialisasi Summernote --}}
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