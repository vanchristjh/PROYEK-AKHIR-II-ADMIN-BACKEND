@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="d-flex flex-column p-3 h-100">
                <div class="d-flex align-items-center justify-content-center mb-4 sidebar-logo">
                    <img src="https://ui-avatars.com/api/?name=SMA&background=0066b3&color=fff&bold=true&size=50" alt="Logo" class="img-fluid rounded-circle" style="width: 50px; height: 50px;">
                    <div class="ms-3">
                        <h5 class="mb-0 fw-bold">Admin SMA</h5>
                        <small class="text-white-50">Panel Administrator</small>
                    </div>
                </div>
                <hr class="text-white-50">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="bx bxs-dashboard"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- Akun Pengguna Menu -->
                    <li class="nav-item mt-2">
                        <span class="text-white-50 small text-uppercase px-3">Manajemen Akun</span>
                    </li>
                    
                    <!-- Siswa Menu -->
                    <li class="nav-item">
                        <a href="{{ route('students.index') }}" class="nav-link {{ request()->is('students') || (request()->is('students/*') && !request()->is('students/create')) ? 'active' : '' }}">
                            <i class="bx bxs-user-detail"></i> Data Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('students.create') }}" class="nav-link {{ request()->is('students/create') ? 'active' : '' }}">
                            <i class="bx bx-user-plus"></i> Tambah Akun Siswa
                        </a>
                    </li>
                    
                    <!-- Guru Menu -->
                    <li class="nav-item">
                        <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->is('teachers') || (request()->is('teachers/*') && !request()->is('teachers/create')) ? 'active' : '' }}">
                            <i class="bx bxs-user-badge"></i> Data Guru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('teachers.create') }}" class="nav-link {{ request()->is('teachers/create') ? 'active' : '' }}">
                            <i class="bx bx-user-plus"></i> Tambah Akun Guru
                        </a>
                    </li>
                    
                    <!-- Kelas Menu -->
                    <li class="nav-item mt-2">
                        <span class="text-white-50 small text-uppercase px-3">Manajemen Kelas</span>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('classes.index') }}" class="nav-link {{ request()->is('classes') || (request()->is('classes/*') && !request()->is('classes/create')) ? 'active' : '' }}">
                            <i class="bx bxs-school"></i> Data Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('classes.create') }}" class="nav-link {{ request()->is('classes/create') ? 'active' : '' }}">
                            <i class="bx bx-plus-circle"></i> Tambah Kelas
                        </a>
                    </li>
                    
                    <li class="nav-item mt-2">
                        <span class="text-white-50 small text-uppercase px-3">Sistem</span>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('documentation.api') }}" class="nav-link {{ request()->is('documentation/api') ? 'active' : '' }}">
                            <i class="bx bx-code-alt"></i> API Mobile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bx bxs-cog"></i> Pengaturan
                        </a>
                    </li>
                </ul>
                <hr class="text-white-50">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=0066b3&color=fff" alt="Admin" width="36" height="36" class="rounded-circle me-2">
                        <div>
                            <strong>Administrator</strong>
                            <small class="d-block text-white-50">admin@sma.sch.id</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="#"><i class="bx bx-user me-2"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bx bx-cog me-2"></i>Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bx bx-log-out me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content fade-in">
            <nav class="navbar navbar-expand-lg bg-white rounded-3 shadow-sm mb-4 mt-3">
                <div class="container-fluid">
                    <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bx bx-menu fs-4"></i>
                    </button>
                    <h5 class="navbar-brand mb-0 text-primary fw-semibold">@yield('page-title', 'Dashboard')</h5>
                    <div class="d-flex align-items-center">
                        <div class="dropdown me-3">
                            <a class="nav-link position-relative" href="#" role="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown" style="min-width: 300px;">
                                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                                <li><a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="rounded-circle bg-light p-2 me-3"><i class="bx bxs-calendar text-primary"></i></div>
                                    <div>
                                        <p class="mb-0 fw-bold">Rapat Guru</p>
                                        <small class="text-muted">Hari ini, 14:00 WIB</small>
                                    </div>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item small text-center" href="#">Lihat Semua Notifikasi</a></li>
                            </ul>
                        </div>
                        <div class="d-none d-md-block">
                            @yield('page-actions')
                        </div>
                    </div>
                </div>
            </nav>
            
            @yield('dashboard-content')
            
            <footer class="mt-auto py-3 text-center text-muted">
                <small>&copy; {{ date('Y') }} SMA - All Rights Reserved</small>
            </footer>
        </main>
    </div>
</div>
@endsection