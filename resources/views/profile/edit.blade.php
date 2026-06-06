{{-- File: resources/views/profile/edit.blade.php --}}
@extends($layout)

@section('title', 'Edit Profil')

@section('content')
<div>
    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('profile.index') }}" class="btn btn-light text-dark">
            <i class="mdi mdi-arrow-left"></i> Kembali ke Profil Saya
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            Profil berhasil diperbarui.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Alert Error --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        
        {{-- BAGIAN 1: FORM INFORMASI PROFIL & DATA DIRI --}}
        <div class="col-md-12 mb-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold text-dark py-3">
                    <h5 class="mb-0">Informasi Profil</h5>
                    <small class="text-muted">Perbarui informasi profil, foto, dan alamat email akun Anda.</small>
                </div>
                <div class="card-body">
                    
                    {{-- Form Utama --}}
                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')

                        {{-- 1. FOTO PROFIL --}}
                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Foto Profil</label>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <img src="{{ $user->profile->foto_profil_path ? asset('storage/' . $user->profile->foto_profil_path) : asset('images/aset/usern.png') }}" 
                                         alt="Current Profile Photo" 
                                         class="rounded-circle" 
                                         style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #eee;">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="foto_profil" class="form-control" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG. Maks: 2MB.</small>
                                </div>
                            </div>
                            @error('foto_profil')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- 2. NAMA LENGKAP --}}
                        <div class="form-group">
                            <label for="nama_lengkap" class="font-weight-bold">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                   value="{{ old('nama_lengkap', $user->profile->nama_lengkap) }}" required autofocus>
                            @error('nama_lengkap')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- 3. USERNAME & EMAIL (Grid) --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username" class="font-weight-bold">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="{{ old('username', $user->username) }}" required>
                                    @error('username')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold">Email Login</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        @if($user->role != 'pengelola')
                            <hr class="my-4">
                            <h6 class="text-primary font-weight-bold mb-3"><i class="mdi mdi-account-details"></i> Detail Alumni</h6>

                            {{-- 4. ANGKATAN 1 (UTAMA) --}}
                        <div class="form-group">
                            <label for="angkatan_id" class="font-weight-bold">Angkatan 1 (Utama)</label>
                            <select id="angkatan_id" name="angkatan_id" class="form-control">
                                <option value="">-- Pilih Angkatan --</option>
                                @foreach($dataAngkatan as $angkatan)
                                    <option value="{{ $angkatan->id }}" 
                                        {{ old('angkatan_id', $user->profile->angkatan_id) == $angkatan->id ? 'selected' : '' }}>
                                        {{ $angkatan->jenjang }} - {{ $angkatan->tahun_masuk }} ({{ $angkatan->nama_angkatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ANGKATAN 2 (OPSIONAL) --}}
                        <div class="form-group">
                            <label for="angkatan2_id" class="font-weight-bold">Angkatan 2 (Opsional)</label>
                            <select id="angkatan2_id" name="angkatan2_id" class="form-control">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($dataAngkatan as $angkatan)
                                    <option value="{{ $angkatan->id }}" 
                                        {{ old('angkatan2_id', $user->profile->angkatan2_id) == $angkatan->id ? 'selected' : '' }}>
                                        {{ $angkatan->jenjang }} - {{ $angkatan->tahun_masuk }} ({{ $angkatan->nama_angkatan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('angkatan2_id')
                                <div class="text-danger small mt-1">
                                    <i class="mdi mdi-alert-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- ANGKATAN 3 (OPSIONAL) --}}
                        <div class="form-group">
                            <label for="angkatan3_id" class="font-weight-bold">Angkatan 3 (Opsional)</label>
                            <select id="angkatan3_id" name="angkatan3_id" class="form-control">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($dataAngkatan as $angkatan)
                                    <option value="{{ $angkatan->id }}" 
                                        {{ old('angkatan3_id', $user->profile->angkatan3_id) == $angkatan->id ? 'selected' : '' }}>
                                        {{ $angkatan->jenjang }} - {{ $angkatan->tahun_masuk }} ({{ $angkatan->nama_angkatan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('angkatan3_id')
                                <div class="text-danger small mt-1">
                                    <i class="mdi mdi-alert-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                            {{-- 5. KONTAK  --}}
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nomor_telepon" class="font-weight-bold">No. HP / WhatsApp</label>
                                        <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" 
                                            value="{{ old('nomor_telepon', $user->profile->nomor_telepon) }}"
                                            placeholder="0812...">
                                        @error('nomor_telepon')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_publik" class="font-weight-bold">Email Publik (Opsional)</label>
                                        <input type="email" class="form-control" id="email_publik" name="email_publik" 
                                            value="{{ old('email_publik', $user->profile->email_publik) }}"
                                            placeholder="Email yang boleh dilihat alumni lain">
                                        @error('email_publik')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary mr-2">Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN 2: FORM UPDATE PASSWORD --}}
        {{-- ============================================================ --}}
        <div class="col-md-12 mb-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold text-dark py-3">
                    <h5 class="mb-0">Update Password</h5>
                    <small class="text-muted">Pastikan akun Anda aman dengan menggunakan password yang kuat.</small>
                </div>
                <div class="card-body">
                    
                    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="current_password" class="font-weight-bold">Password Saat Ini</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="font-weight-bold">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="font-weight-bold">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mt-3 d-flex align-items-center">
                            <button type="submit" class="btn btn-warning mr-3">Ganti Password</button>
                            
                            @if (session('status') === 'password-updated')
                                <p class="text-success mb-0 font-weight-bold"><i class="mdi mdi-check"></i> Tersimpan.</p>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN 3: HAPUS AKUN --}}
        {{-- ============================================================ --}}
        <div class="col-md-12 mb-5">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white font-weight-bold py-3">
                    <h5 class="mb-0 text-white">Hapus Akun</h5>
                    <small class="text-white-50">Setelah akun dihapus, semua data akan hilang permanen.</small>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Tindakan ini tidak dapat dibatalkan. Silakan masukkan password Anda untuk mengonfirmasi penghapusan akun.
                    </p>

                    {{-- Tombol Trigger Modal --}}
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmUserDeletionModal">
                        Hapus Akun Saya
                    </button>

                    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
                                @csrf
                                @method('delete')

                                <div class="modal-header">
                                    <h5 class="modal-title text-danger" id="modalLabel">Apakah Anda yakin?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-muted">
                                        Semua data profil, riwayat, dan foto Anda akan dihapus secara permanen. Masukkan password untuk melanjutkan.
                                    </p>
                                    <div class="form-group">
                                        <label for="password_delete" class="sr-only">Password</label>
                                        <input type="password" class="form-control" id="password_delete" name="password" placeholder="Password Anda">
                                        @error('password', 'userDeletion')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Ya, Hapus Akun</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($errors->userDeletion->isNotEmpty())
                        <script>
                            // Jika ada error pada delete (misal password salah), buka modal lagi otomatis
                            document.addEventListener('DOMContentLoaded', function() {
                                $('#confirmUserDeletionModal').modal('show');
                            });
                        </script>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection