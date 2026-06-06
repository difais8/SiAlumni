<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Admin') - SiAlumni</title>
    
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link rel="shortcut icon" href="{{ asset('images/iconLogoSMP_m.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css">
    
    @stack('styles')
</head>
<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
                    <h3 class="text-primary">SIALUMNI</h3>
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/iconLogoSMP_m.png') }}" alt="logo"/>
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img class="img-fluid rounded-circle shadow" style="object-fit: cover;" 
                                src="{{ auth()->user()->profile->foto_profil_thumbnail ? asset('storage/' . auth()->user()->profile->foto_profil_thumbnail) : asset('images/aset/usern.png') }}" alt="profile"/>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                            <a href="{{ route('profile.index') }}" class="dropdown-item text-muted text-center">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="dropdown-item p-0">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted text-center">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>

        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content bg-transparent border-0 shadow-none">
                    <div class="modal-body p-0 text-center position-relative">
                        <button type="button" class="btn btn-light btn-sm rounded-circle position-absolute" 
                                style="top: -15px; right: -15px; z-index: 1050; width: 30px; height: 30px; padding: 0;" 
                                data-dismiss="modal">
                            <i class="mdi mdi-close text-dark"></i>
                        </button>
                        
                        <img id="previewImage" src="" alt="Preview" class="img-fluid rounded shadow-lg" style="max-height: 85vh;">
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid page-body-wrapper">

            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    
                    {{-- 1. Dashboard --}}
                    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="mdi mdi-apple-keyboard-command menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    
                    @if(auth()->user()->role == 'pengelola')
                    {{-- 2. Manajemen Pengelola --}}
                    <li class="nav-item {{ request()->routeIs('admin.pengelola.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.pengelola.index') }}">
                            <i class="mdi mdi-account-key menu-icon"></i>
                            <span class="menu-title">Manajemen Pengelola</span>
                        </a>
                    </li>
                    @endif
                    
                    {{-- 3. Manajemen Alumni --}}
                    <li class="nav-item {{ request()->routeIs('admin.alumni.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.alumni.index') }}"> 
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                            <span class="menu-title">Manajemen Alumni</span>
                        </a>
                    </li>
                    
                    {{-- 4. Manajemen Angkatan --}}
                    <li class="nav-item {{ request()->routeIs('admin.angkatan.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.angkatan.index') }}"> 
                            <i class="mdi mdi-calendar-clock menu-icon"></i>
                            <span class="menu-title">Manajemen Angkatan</span>
                        </a>
                    </li>

                    {{-- 5. Pengumuman --}}
                    <li class="nav-item {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.pengumuman.index') }}">
                            <i class="mdi mdi-bullhorn menu-icon"></i>
                            <span class="menu-title">Pengumuman</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('chat.index') }}">
                            <i class="mdi mdi-forum-outline menu-icon"></i>
                            <span class="menu-title">Laman Diskusi</span>
                        </a>
                    </li>
                    
                </ul>
            </nav>

            <div class="main-panel">
                <div class="content-wrapper">
                    
                    @yield('content')

                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © {{ date('Y') }}</span>
                    </div>
                </footer> 
            </div>
            </div>   
        </div>
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('js/custom-databale.js') }}"></script>
    <script src="{{ asset('js/konfigurasi-custom-datatable.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>
    
    @stack('scripts')
</body>

</html>