@extends('layouts.admin')

@section('title', 'Manajemen Angkatan')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Data Angkatan</h4>
                </div>

                <div class="mb-3">
                    <a href="{{ route('admin.angkatan.create') }}" class="btn btn-primary btn-sm"  style="display:flex; width:fit-content">
                        <div class="mdi mdi-plus mr-1"></div> Tambah Angkatan
                    </a>
                </div>
                <div class="table-responsive">
                    <table id="tabel" class="table table-hover">
                        <thead class="theadMC">
                            <tr>
                                <th>No</th>
                                <th>Jenjang</th>
                                <th>Tahun Masuk</th>
                                <th>Nama Angkatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($semuaAngkatan as $angkatan)
                                <tr>
                                    <td></td>
                                    <td>{{ $angkatan->jenjang }}</td>
                                    <td>{{ $angkatan->tahun_masuk }}</td>
                                    <td>{{ $angkatan->nama_angkatan }}</td>
                                    <td>
                                        <a href="{{ route('admin.angkatan.edit', $angkatan->id) }}" class="btn btn-info btn-icon-text btn-sm"> {{-- Nanti diisi: route('admin.angkatan.edit', $angkatan->id) --}}
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form id="form-hapus-{{ $angkatan->id }}" 
                                            action="{{ route('admin.angkatan.destroy', $angkatan->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-icon-text btn-sm btn-hapus-swal" 
                                                    data-id="{{ $angkatan->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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
        inisialisasiTabel('#tabel', konfigurasiPengaturanDataAngkatan);
        inisialisasiTombolHapus(
            '#tabel tbody',      
            'form-hapus',                      
            'Data angkatan ini akan dihapus permanen!'
        );
    });
</script>
@endpush