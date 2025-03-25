@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-auto sidebar">
            <div class="d-flex flex-column h-100">
                <div class="sidebar-logo">
                    <div class="logo-container position-relative">
                        <div class="logo-bg position-absolute top-0 start-0 w-100 h-100 rounded-circle" 
                             style="background: rgba(255,255,255,0.2); filter: blur(8px);"></div>
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo SMA" class="img-fluid rounded-circle position-relative" 
                             style="width: 50px; height: 50px; border: 2px solid rgba(255,255,255,0.5);">
                    </div>
                    <div class="mt-2 text-white">
                        <h6 class="mb-0 fw-bold">SMA NEGERI 1</h6>
                        <small class="text-white-50">GIRSANG SIPANGAN BOLON</small>
                    </div>
                </div>
                
                <hr class="sidebar-divider my-3 opacity-25">
                
                <div class="nav-container flex-grow-1 overflow-auto">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bx bxs-dashboard"></i> Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-section mt-3 mb-1">
                            <span class="sidebar-heading px-3 text-uppercase opacity-50 small">Manajemen Akademik</span>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('students.index') }}" class="nav-link {{ Request::routeIs('students.*') ? 'active' : '' }}">
                                <i class="bx bxs-user-detail"></i> Data Siswa
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('teachers.index') }}" class="nav-link {{ Request::routeIs('teachers.*') ? 'active' : '' }}">
                                <i class="bx bxs-user-badge"></i> Data Guru
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('classes.index') }}" class="nav-link {{ Request::routeIs('classes.*') ? 'active' : '' }}">
                                <i class="bx bxs-school"></i> Data Kelas
                            </a>
                        </li>
                        
                        <li class="nav-section mt-3 mb-1">
                            <span class="sidebar-heading px-3 text-uppercase opacity-50 small">Pembelajaran</span>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="#scheduleSubmenu" data-bs-toggle="collapse" 
                               aria-expanded="{{ Request::routeIs('schedules.*') || Request::routeIs('student-schedules.*') || Request::routeIs('teacher-schedules.*') ? 'true' : 'false' }}">
                                <i class="bx bx-calendar"></i>
                                <span>Jadwal</span>
                                <i class="bx bx-chevron-down ms-auto"></i>
                            </a>
                            <div class="collapse {{ Request::routeIs('schedules.*') || Request::routeIs('student-schedules.*') || Request::routeIs('teacher-schedules.*') ? 'show' : '' }}" id="scheduleSubmenu">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a href="{{ route('schedules.index') }}" class="nav-link ps-3 {{ Request::routeIs('schedules.*') && !Request::routeIs('schedules.weekly') ? 'active' : '' }}">
                                            <i class="bx bx-list-ul"></i> Semua Jadwal
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('schedules.weekly') }}" class="nav-link ps-3 {{ Request::routeIs('schedules.weekly') ? 'active' : '' }}">
                                            <i class="bx bx-calendar-week"></i> Jadwal Mingguan
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('student-schedules.index') }}" class="nav-link ps-3 {{ Request::routeIs('student-schedules.*') ? 'active' : '' }}">
                                            <i class="bx bx-user"></i> Jadwal Per Kelas
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('teacher-schedules.index') }}" class="nav-link ps-3 {{ Request::routeIs('teacher-schedules.*') ? 'active' : '' }}">
                                            <i class="bx bx-user-voice"></i> Jadwal Per Guru
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bx bx-book-alt"></i> Mata Pelajaran
                            </a>
                        </li>
                        
                        <li class="nav-section mt-3 mb-1">
                            <span class="sidebar-heading px-3 text-uppercase opacity-50 small">Informasi Sekolah</span>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('announcements.index') }}" class="nav-link {{ Request::routeIs('announcements.*') ? 'active' : '' }}">
                                <i class="bx bx-bell"></i> Pengumuman
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('academic-calendar.index') }}" class="nav-link {{ Request::routeIs('academic-calendar.*') ? 'active' : '' }}">
                                <i class="bx bx-calendar-event"></i> Kalender Akademik
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="sidebar-footer mt-auto py-3 px-3">
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0066b3&color=fff" class="avatar rounded-circle">
                        </div>
                        <div class="flex-grow-1 text-white">
                            <div class="small fw-bold">{{ Auth::user()->name }}</div>
                            <div class="small text-white-50">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-white p-1" type="button" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bx bx-user me-2"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bx bx-cog me-2"></i> Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bx bx-log-out me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
                <div class="container-fluid px-4">
                    <button class="navbar-toggler border-0 p-0 me-2" type="button" id="sidebar-toggle">
                        <i class="bx bx-menu fs-4"></i>
                    </button>
                    
                    <div class="d-flex flex-grow-1 align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h5>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 small">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="link-secondary">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@yield('page-title', 'Dashboard')</li>
                                </ol>
                            </nav>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            @yield('page-actions')
                            
                            <div class="nav-item ms-3 dropdown">
                                <a href="#" class="nav-link position-relative p-0" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-bell fs-4 notification-bell"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        3
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-notifications shadow-sm border-0 rounded-3 p-0" style="width: 300px;">
                                    <div class="card border-0 m-0">
                                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
                                            <h6 class="card-title mb-0">Notifikasi</h6>
                                            <span class="badge bg-light text-primary rounded-pill">3 Baru</span>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="list-group list-group-flush">
                                                <a href="#" class="list-group-item list-group-item-action px-3 py-2 border-bottom">
                                                    <div class="d-flex">
                                                        <div class="me-3">
                                                            <div class="avatar-sm bg-primary-soft rounded-circle d-flex align-items-center justify-content-center">
                                                                <i class="bx bx-calendar text-primary"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-medium">Jadwal rapat guru telah diperbarui</p>
                                                            <small class="text-muted">Baru saja</small>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="#" class="list-group-item list-group-item-action px-3 py-2 border-bottom">
                                                    <div class="d-flex">
                                                        <div class="me-3">
                                                            <div class="avatar-sm bg-success-soft rounded-circle d-flex align-items-center justify-content-center">
                                                                <i class="bx bx-user-plus text-success"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-medium">5 siswa baru telah ditambahkan</p>
                                                            <small class="text-muted">2 jam yang lalu</small>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="#" class="list-group-item list-group-item-action px-3 py-2 border-bottom">
                                                    <div class="d-flex">
                                                        <div class="me-3">
                                                            <div class="avatar-sm bg-danger-soft rounded-circle d-flex align-items-center justify-content-center">
                                                                <i class="bx bx-bell text-danger"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-medium">Pengumuman penting telah dipublikasikan</p>
                                                            <small class="text-muted">5 jam yang lalu</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-light p-2 text-center">
                                            <a href="#" class="small text-primary text-decoration-none">Lihat semua notifikasi</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="nav-item ms-3 dropdown">
                                <a href="#" class="nav-link d-flex align-items-center p-0" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0066b3&color=fff" class="avatar rounded-circle me-2">
                                    <span class="d-none d-sm-inline-block">
                                        <span class="fw-semibold">{{ Auth::user()->name }}</span>
                                        <i class="bx bx-chevron-down ms-1 small"></i>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <span class="dropdown-header small text-muted">AKUN SAYA</span>
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="bx bx-user me-2"></i> Profil</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bx bx-cog me-2"></i> Pengaturan</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bx bx-log-out me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Page Content -->
            <div class="container-fluid p-4">
                @yield('dashboard-content')
            </div>
            
            <!-- Footer -->
            <footer class="footer bg-white border-top mt-auto py-3 px-4">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-lg-6 text-center text-lg-start mb-2 mb-lg-0">
                            <span class="text-muted">&copy; {{ date('Y') }} SMA Negeri 1 Girsang Sipangan Bolon. All rights reserved.</span>
                        </div>
                        <div class="col-lg-6 text-center text-lg-end">
                            <span class="text-muted">Designed with <i class="bx bx-heart text-danger"></i> by Tim PA2</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar on mobile
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
            });
        }
        
        // Active navigation links
        const currentLocation = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && (currentLocation === href || currentLocation.startsWith(href) && href !== '/')) {
                link.classList.add('active');
                
                // Also add active class to parent items for multi-level menu
                const parentItem = link.closest('.submenu');
                if (parentItem) {
                    const parentLink = parentItem.querySelector('.nav-link');
                    if (parentLink) {
                        parentLink.classList.add('active');
                    }
                }
            }
        });
    });
    
    // Notification bell animation
    document.addEventListener('DOMContentLoaded', function() {
        const bell = document.querySelector('.notification-bell');
        if (bell) {
            setInterval(() => {
                bell.classList.add('animate__animated', 'animate__tada');
                setTimeout(() => {
                    bell.classList.remove('animate__animated', 'animate__tada');
                }, 1000);
            }, 10000);
        }
    });
</script>
@endsection