{{-- File: resources/views/admin/pengelola/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Manajemen Pengelola')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Data Pengelola</h4>
                </div>
                
                <div class="mb-3">
                    <a href="{{ route('admin.pengelola.create') }}" class="btn btn-primary btn-sm" style="display:flex; width:fit-content">
                        <div class="mdi mdi-plus mr-1"></div>
                        Tambah Pengelola
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table id="tabel" class="table table-hover">
                        <thead class="theadMC">
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Email Login</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($semuaPengelola as $pengelola)
                                <tr>
                                    <td></td> {{-- Untuk DataTables --}}
                                    <td>{{ $pengelola->profile->nama_lengkap ?? 'N/A' }}</td>
                                    <td>{{ $pengelola->username }}</td>
                                    <td>{{ $pengelola->email }}</td>
                                    <td>
                                        <a href="{{ route('admin.pengelola.edit', $pengelola->id) }}" class="btn btn-info btn-icon-text btn-sm">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <form id="form-hapus-{{ $pengelola->id }}" 
                                            action="{{ route('admin.pengelola.destroy', $pengelola->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-icon-text btn-sm btn-hapus-swal"
                                                    data-id="{{ $pengelola->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">Belum ada data pengelola lain.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        inisialisasiTabel('#tabel', konfigurasiPengaturanDataPengelola);
        inisialisasiTombolHapus(
            '#tabel tbody',
            'form-hapus',
            'Akun pengelola ini akan dihapus permanen!'
        );
    });
</script>
@endpush