@extends('layouts.admin')
@section('title', 'Edit Pengelola')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Formulir Edit Pengelola</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form class="forms-sample" action="{{ route('admin.pengelola.update', $pengelola->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Metode Update --}}

                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $pengelola->profile->nama_lengkap) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $pengelola->username) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Login</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $pengelola->email) }}" required>
                    </div>
                    <p class="text-muted">Perubahan password harus dilakukan melalui mekanisme "Lupa Password".</p>

                    <button type="submit" class="btn btn-primary mr-2">Update</button>
                    <a href="{{ route('admin.pengelola.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection