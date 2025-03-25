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
                             style="width: 60px; height: 60px; border: 2px solid rgba(255,255,255,0.5); object-fit: cover;">
                    </div>
                    <div class="mt-2 text-white text-center">
                        <h6 class="mb-0 fw-bold">SMA NEGERI 1</h6>
                        <small class="text-white-50">GIRSANG SIPANGAN BOLON</small>
                    </div>
                </div>
                
                <hr class="sidebar-divider opacity-25 mx-3">
                
                <div class="nav-container flex-grow-1 overflow-auto">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bx bxs-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="nav-section mt-3 mb-1">
                            <span class="sidebar-heading px-3 text-uppercase">Manajemen Akademik</span>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('students.index') }}" class="nav-link {{ Request::routeIs('students.*') ? 'active' : '' }}">
                                <i class="bx bxs-user-detail"></i> <span>Data Siswa</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('teachers.index') }}" class="nav-link {{ Request::routeIs('teachers.*') ? 'active' : '' }}">
                                <i class="bx bxs-user-badge"></i> <span>Data Guru</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('classes.index') }}" class="nav-link {{ Request::routeIs('classes.*') ? 'active' : '' }}">
                                <i class="bx bxs-school"></i> <span>Data Kelas</span>
                            </a>
                        </li>
                        
                        <li class="nav-section mt-3 mb-1">
                            <span class="sidebar-heading px-3 text-uppercase">Pembelajaran</span>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('schedules.*') || Request::routeIs('student-schedules.*') || Request::routeIs('teacher-schedules.*') ? 'active' : '' }}" 
                               href="#scheduleSubmenu" data-bs-toggle="collapse" 
                               aria-expanded="{{ Request::routeIs('schedules.*') || Request::routeIs('student-schedules.*') || Request::routeIs('teacher-schedules.*') ? 'true' : 'false' }}">
                                <i class="bx bx-calendar"></i>
                                <span>Jadwal</span>
                                <i class="bx bx-chevron-down ms-auto"></i>
                            </a>
                            <div class="collapse {{ Request::routeIs('schedules.*') || Request::routeIs('student-schedules.*') || Request::routeIs('teacher-schedules.*') ? 'show' : '' }}" id="scheduleSubmenu">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a href="{{ route('schedules.index') }}" class="nav-link ps-3 {{ Request::routeIs('schedules.*') && !Request::routeIs('schedules.weekly') ? 'active' : '' }}">
                                            <i class="bx bx-list-ul"></i> <span>Semua Jadwal</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('schedules.weekly') }}" class="nav-link ps-3 {{ Request::routeIs('schedules.weekly') ? 'active' : '' }}">
                                            <i class="bx bx-calendar-week"></i> <span>Jadwal Mingguan</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('student-schedules.index') }}" class="nav-link ps-3 {{ Request::routeIs('student-schedules.*') ? 'active' : '' }}">
                                            <i class="bx bx-user"></i> <span>Jadwal Per Kelas</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('teacher-schedules.index') }}" class="nav-link ps-3 {{ Request::routeIs('teacher-schedules.*') ? 'active' : '' }}">
                                            <i class="bx bx-user-voice"></i> <span>Jadwal Per Guru</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('subjects.index') }}" class="nav-link {{ Request::routeIs('subjects.*') ? 'active' : '' }}">
                                <i class="bx bx-book-alt"></i> <span>Mata Pelajaran</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('attendance.index') }}" class="nav-link {{ Request::routeIs('attendance.*') ? 'active' : '' }}">
                                <i class="bx bx-check-square"></i> <span>Absensi</span>
                            </a>
                        </li>
                        
                        <li class="nav-section mt-3 mb-1">
                            <span class="sidebar-heading px-3 text-uppercase">Informasi Sekolah</span>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('announcements.index') }}" class="nav-link {{ Request::routeIs('announcements.*') ? 'active' : '' }}">
                                <i class="bx bx-bell"></i> <span>Pengumuman</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('academic-calendar.index') }}" class="nav-link {{ Request::routeIs('academic-calendar.*') ? 'active' : '' }}">
                                <i class="bx bx-calendar-event"></i> <span>Kalender Akademik</span>
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
                            <div class="small fw-bold text-truncate">{{ Auth::user()->name }}</div>
                            <div class="small text-white-50 text-truncate">{{ Auth::user()->email }}</div>
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
        <div class="col main-content">
            <!-- Mobile Menu Toggle -->
            <button id="sidebarToggle" class="btn btn-sm btn-primary d-lg-none mb-3">
                <i class="bx bx-menu"></i>
            </button>
            
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                <div class="page-actions">
                    @yield('page-actions')
                </div>
            </div>
            
            <!-- Dashboard Content -->
            <div>
                @yield('dashboard-content')
            </div>
            
            <!-- Footer -->
            <footer class="mt-5 text-center">
                <p class="text-muted small mb-0">&copy; {{ date('Y') }} SMA Negeri 1 Girsang Sipangan Bolon. All rights reserved.</p>
            </footer>
        </div>
    </div>
</div>
@endsection