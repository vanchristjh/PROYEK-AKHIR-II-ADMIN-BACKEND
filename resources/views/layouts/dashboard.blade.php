@extends('layouts.app')

@section('content')
<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="school-brand">
                <div class="logo-container">
                    <div class="logo-wrapper">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo SMA" class="school-logo">
                    </div>
                    <div class="school-info">
                        <h6 class="school-name">SMA NEGERI 1</h6>
                        <span class="school-location">GIRSANG SIPANGAN BOLON</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Settings Link (replacing User Profile Preview) -->
        <div class="settings-link mb-3">
            <a href="{{ route('settings.index') }}" class="d-flex align-items-center p-3 rounded-3 text-decoration-none">
                <div class="flex-shrink-0">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="bx bx-cog text-white fs-5"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0 text-white">Pengaturan Sistem</h6>
                    <span class="text-muted small">Konfigurasi aplikasi</span>
                </div>
            </a>
        </div>
        
        <div class="sidebar-search">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bx bx-search"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari menu...">
            </div>
        </div>
        
        <div class="nav-container">
            <ul class="sidebar-nav">
                <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="bx bxs-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-divider">
                    <span>MANAJEMEN AKADEMIK</span>
                </li>
                
                <li class="nav-item {{ Request::routeIs('students.*') ? 'active' : '' }}">
                    <a href="{{ route('students.index') }}" class="nav-link">
                        <i class="bx bxs-user-detail"></i>
                        <span>Data Siswa</span>
                        <span class="badge bg-primary-light text-primary rounded-pill ms-auto">215</span>
                    </a>
                </li>
                
                <li class="nav-item {{ Request::routeIs('teachers.*') ? 'active' : '' }}">
                    <a href="{{ route('teachers.index') }}" class="nav-link">
                        <i class="bx bxs-user-badge"></i>
                        <span>Data Guru</span>
                        <span class="badge bg-primary-light text-primary rounded-pill ms-auto">24</span>
                    </a>
                </li>
                
                <li class="nav-item {{ Request::routeIs('classes.*') ? 'active' : '' }}">
                    <a href="{{ route('classes.index') }}" class="nav-link">
                        <i class="bx bxs-school"></i>
                        <span>Data Kelas</span>
                        <span class="badge bg-primary-light text-primary rounded-pill ms-auto">9</span>
                    </a>
                </li>
                
                <li class="nav-divider">
                    <span>PEMBELAJARAN</span>
                </li>
                
                <li class="nav-item has-submenu {{ Request::routeIs('schedules.*') || Request::routeIs('student-schedules.*') || Request::routeIs('teacher-schedules.*') ? 'active open' : '' }}">
                    <a href="#" class="nav-link submenu-toggle">
                        <i class="bx bx-calendar"></i>
                        <span>Jadwal</span>
                        <i class="bx bx-chevron-down submenu-indicator"></i>
                    </a>
                    <ul class="submenu-content">
                        <li class="{{ Request::routeIs('schedules.index') ? 'active' : '' }}">
                            <a href="{{ route('schedules.index') }}">
                                <i class="bx bx-list-ul"></i>
                                <span>Semua Jadwal</span>
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('schedules.weekly') ? 'active' : '' }}">
                            <a href="{{ route('schedules.weekly') }}">
                                <i class="bx bx-calendar-week"></i>
                                <span>Jadwal Mingguan</span>
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('student-schedules.*') ? 'active' : '' }}">
                            <a href="{{ route('student-schedules.index') }}">
                                <i class="bx bx-user"></i>
                                <span>Jadwal Per Kelas</span>
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('teacher-schedules.*') ? 'active' : '' }}">
                            <a href="{{ route('teacher-schedules.index') }}">
                                <i class="bx bx-user-voice"></i>
                                <span>Jadwal Per Guru</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item {{ Request::routeIs('subjects.*') ? 'active' : '' }}">
                    <a href="{{ route('subjects.index') }}" class="nav-link">
                        <i class="bx bx-book-alt"></i>
                        <span>Mata Pelajaran</span>
                    </a>
                </li>
                
                <li class="nav-item {{ Request::routeIs('attendance.*') ? 'active' : '' }}">
                    <a href="{{ route('attendance.index') }}" class="nav-link">
                        <i class="bx bx-check-square"></i>
                        <span>Absensi</span>
                        <span class="badge bg-success-light text-success rounded-pill ms-auto">Hari Ini</span>
                    </a>
                </li>
                
                <li class="nav-item {{ Request::is('grades*') ? 'active' : '' }}">
                    <a href="{{ route('subjects.index') }}" class="nav-link">
                        <i class="bx bx-bar-chart-alt-2"></i>
                        <span>Nilai Akademik</span>
                    </a>
                </li>
                
                <li class="nav-divider">
                    <span>INFORMASI SEKOLAH</span>
                </li>
                
                <li class="nav-item {{ Request::routeIs('announcements.*') ? 'active' : '' }}">
                    <a href="{{ route('announcements.index') }}" class="nav-link">
                        <i class="bx bx-bell"></i>
                        <span>Pengumuman</span>
                        <span class="badge bg-danger-light text-danger rounded-pill pulse ms-auto">5</span>
                    </a>
                </li>
                
                <li class="nav-item {{ Request::routeIs('academic-calendar.*') ? 'active' : '' }}">
                    <a href="{{ route('academic-calendar.index') }}" class="nav-link">
                        <i class="bx bx-calendar-event"></i>
                        <span>Kalender Akademik</span>
                    </a>
                </li>
                
                <li class="nav-divider">
                    <span>PENGATURAN</span>
                </li>
                
                <li class="nav-item {{ Request::is('settings*') ? 'active' : '' }}">
                    <a href="{{ url('/settings') }}" class="nav-link">
                        <i class="bx bx-cog"></i>
                        <span>Pengaturan Sistem</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <div class="app-version">v2.1.0</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bx bx-log-out"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="navbar-left">
                <button id="sidebarToggle" class="btn-toggle-sidebar">
                    <i class="bx bx-menu"></i>
                </button>
                <div class="page-title d-none d-md-block">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                            @yield('breadcrumbs')
                            <li class="breadcrumb-item active" aria-current="page">@yield('page-title', 'Dashboard')</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="navbar-right">
                <!-- Quick Actions -->
                <div class="quick-actions dropdown">
                    <button class="btn-icon" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-plus-circle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Aksi Cepat</h6></li>
                        <li><a class="dropdown-item" href="{{ route('students.create') }}"><i class="bx bx-user-plus"></i> Tambah Siswa</a></li>
                        <li><a class="dropdown-item" href="{{ route('teachers.create') }}"><i class="bx bx-user-voice"></i> Tambah Guru</a></li>
                        <li><a class="dropdown-item" href="{{ route('announcements.create') }}"><i class="bx bx-bell-plus"></i> Buat Pengumuman</a></li>
                        <li><a class="dropdown-item" href="{{ route('schedules.create') }}"><i class="bx bx-calendar-plus"></i> Buat Jadwal</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('classes.create') }}"><i class="bx bx-plus-circle"></i> Kelas Baru</a></li>
                    </ul>
                </div>
                
                <!-- Theme Toggle -->
                <button class="btn-icon theme-toggle" id="themeToggle">
                    <i class="bx bx-moon"></i>
                </button>
                
                <!-- Notifications -->
                <div class="notifications dropdown">
                    <button class="btn-icon" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-bell"></i>
                        <span class="badge">5</span>
                    </button>
                    <div class="dropdown-menu notifications-dropdown dropdown-menu-end">
                        <div class="notifications-header">
                            <h6>Notifikasi</h6>
                            <a href="#" class="mark-all-read">Tandai sudah dibaca</a>
                        </div>
                        <div class="notifications-body">
                            <div class="notification-item unread">
                                <div class="notification-icon bg-primary-light text-primary">
                                    <i class="bx bx-user-plus"></i>
                                </div>
                                <div class="notification-content">
                                    <p class="notification-text">5 siswa baru telah terdaftar</p>
                                    <span class="notification-time">30 menit yang lalu</span>
                                </div>
                                <button class="notification-action"><i class="bx bx-dots-vertical-rounded"></i></button>
                            </div>
                            
                            <div class="notification-item unread">
                                <div class="notification-icon bg-success-light text-success">
                                    <i class="bx bx-calendar-check"></i>
                                </div>
                                <div class="notification-content">
                                    <p class="notification-text">Jadwal semester ganjil telah diterbitkan</p>
                                    <span class="notification-time">2 jam yang lalu</span>
                                </div>
                                <button class="notification-action"><i class="bx bx-dots-vertical-rounded"></i></button>
                            </div>
                            
                            <div class="notification-item">
                                <div class="notification-icon bg-warning-light text-warning">
                                    <i class="bx bx-error-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <p class="notification-text">Pengumpulan nilai semester terakhir besok</p>
                                    <span class="notification-time">12 jam yang lalu</span>
                                </div>
                                <button class="notification-action"><i class="bx bx-dots-vertical-rounded"></i></button>
                            </div>
                            
                            <div class="notification-item">
                                <div class="notification-icon bg-info-light text-info">
                                    <i class="bx bx-bell"></i>
                                </div>
                                <div class="notification-content">
                                    <p class="notification-text">Rapat guru akan diadakan hari Jumat</p>
                                    <span class="notification-time">1 hari yang lalu</span>
                                </div>
                                <button class="notification-action"><i class="bx bx-dots-vertical-rounded"></i></button>
                            </div>
                            
                            <div class="notification-item">
                                <div class="notification-icon bg-danger-light text-danger">
                                    <i class="bx bx-calendar-exclamation"></i>
                                </div>
                                <div class="notification-content">
                                    <p class="notification-text">Ujian semester dimulai dalam 2 minggu</p>
                                    <span class="notification-time">2 hari yang lalu</span>
                                </div>
                                <button class="notification-action"><i class="bx bx-dots-vertical-rounded"></i></button>
                            </div>
                        </div>
                        <div class="notifications-footer">
                            <a href="{{ url('/notifications') }}" class="btn btn-sm btn-light w-100">Lihat Semua Notifikasi</a>
                        </div>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="user-menu dropdown">
                    <button class="user-menu-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0066b3&color=fff" alt="{{ Auth::user()->name }}" class="user-avatar">
                        <span class="d-none d-md-inline-block">{{ Auth::user()->name }}</span>
                        <i class="bx bx-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0066b3&color=fff" alt="{{ Auth::user()->name }}" class="avatar-sm me-3">
                                <div>
                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="bx bx-user me-2"></i> Profil Saya</a></li>
                        <li><a class="dropdown-item" href="{{ url('/settings') }}"><i class="bx bx-cog me-2"></i> Pengaturan</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bx bx-help-circle me-2"></i> Bantuan</a></li>
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
        </nav>
        
        <!-- Page Content -->
        <div class="page-content">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle fs-4 me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error-circle fs-4 me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('dashboard-content')
        </div>
        
        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }} SMA Negeri 1 Girsang Sipangan Bolon</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0">Sistem Informasi Akademik v2.1.0</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Include Toast Notifications -->
@include('layouts.partials.toast-notifications')

<!-- Dashboard Styles -->
<style>
:root {
    --primary: #0066b3;
    --primary-dark: #004c87;
    --primary-light: #e6f2ff;
    --secondary: #ffc107;
    --success: #28a745;
    --danger: #dc3545;
    --warning: #fd7e14;
    --info: #17a2b8;
    --dark: #343a40;
    --light: #f8f9fa;
    --body-bg: #f5f7fa;
    --sidebar-bg: #ffffff;
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 70px;
    --header-height: 70px;
    --footer-height: 50px;
    --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    --card-shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    --transition-speed: 0.3s;
    --border-radius: 0.5rem;
    --font-main: 'Nunito', sans-serif;
}

.dark-mode {
    --primary: #4d94ff;
    --primary-dark: #0066cc;
    --primary-light: #1a2e44;
    --body-bg: #121212;
    --sidebar-bg: #1e1e2d;
    --light: #2a2a3c;
    --dark: #f8f9fa;
    --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.2);
    --text-color: #e4e6ef;
    --text-muted: #9899ac;
    --border-color: #2b2b40;
}

body {
    font-family: var(--font-main);
    background-color: var(--body-bg);
    transition: background-color var(--transition-speed);
    overflow-x: hidden;
}

.dashboard-wrapper {
    display: flex;
    min-height: 100vh;
    position: relative;
}

/* Sidebar Styles */
.sidebar {
    width: var(--sidebar-width);
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background-color: var(--sidebar-bg);
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    transition: all var(--transition-speed);
    z-index: 1000;
    overflow-y: auto;
    scrollbar-width: thin;
}

.sidebar::-webkit-scrollbar {
    width: 5px;
}

.sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
}

.sidebar-header {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.school-brand {
    text-align: center;
}

.logo-container {
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 10px;
    background: var(--primary-light);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0, 102, 179, 0.2);
}

.school-logo {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.school-info {
    display: flex;
    flex-direction: column;
}

.school-name {
    color: var(--primary);
    font-weight: 700;
    margin-bottom: 0;
    font-size: 1rem;
}

.school-location {
    color: var(--text-muted);
    font-size: 0.7rem;
    font-weight: 600;
}

.user-profile-preview {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background-color: var(--primary-light);
    margin: 0 1rem 1rem;
    border-radius: var(--border-radius);
}

.user-avatar {
    position: relative;
    margin-right: 0.75rem;
}

.user-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.5);
}

.status-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid var(--primary-light);
}

.status-indicator.online {
    background-color: var(--success);
}

.user-info {
    flex: 1;
}

.user-name {
    color: var(--primary-dark);
    font-weight: 600;
    margin-bottom: 0;
    font-size: 0.9rem;
}

.user-role {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.sidebar-search {
    padding: 0 1.5rem 1rem;
}

.sidebar-search .input-group {
    background-color: var(--light);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.sidebar-search .input-group-text {
    background-color: transparent;
    border: none;
    color: var(--text-muted);
}

.sidebar-search .form-control {
    border: none;
    background-color: transparent;
    padding-left: 0;
    font-size: 0.85rem;
}

.sidebar-search .form-control:focus {
    box-shadow: none;
}

.nav-container {
    flex: 1;
    padding: 0 1rem;
}

.sidebar-nav {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 0.25rem;
    border-radius: var(--border-radius);
    transition: background-color var(--transition-speed);
}

.nav-link {
    display: flex;
    align-items: center;
    color: var(--dark);
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: all var(--transition-speed);
}

.nav-link i {
    font-size: 1.25rem;
    margin-right: 0.75rem;
    color: var (--text-muted);
    transition: color var(--transition-speed);
}

.nav-item:hover .nav-link {
    background-color: var(--light);
}

.nav-item.active .nav-link {
    background-color: var(--primary-light);
    color: var(--primary);
}

.nav-item.active .nav-link i, 
.nav-item:hover .nav-link i {
    color: var(--primary);
}

.nav-divider {
    padding: 1rem 1.5rem 0.5rem;
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.has-submenu .submenu-toggle {
    cursor: pointer;
}

.submenu-indicator {
    margin-left: auto;
    transition: transform var(--transition-speed);
}

.has-submenu.open .submenu-indicator {
    transform: rotate(-180deg);
}

.submenu-content {
    list-style: none;
    padding: 0.25rem 0 0.25rem 2.5rem;
    margin: 0;
    max-height: 0;
    overflow: hidden;
    transition: max-height var(--transition-speed);
}

.has-submenu.open .submenu-content {
    max-height: 500px;
}

.submenu-content li {
    margin-bottom: 0.25rem;
}

.submenu-content a {
    display: flex;
    align-items: center;
    color: var(--dark);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    text-decoration: none;
    transition: all var(--transition-speed);
}

.submenu-content a i {
    font-size: 1rem;
    margin-right: 0.75rem;
    color: var(--text-muted);
}

.submenu-content li:hover a {
    background-color: var(--light);
}

.submenu-content li.active a {
    color: var(--primary);
    background-color: var(--primary-light);
}

.submenu-content li.active a i {
    color: var(--primary);
}

.badge {
    font-size: 0.65rem;
    padding: 0.25rem 0.65rem;
    font-weight: 600;
}

.badge.pulse {
    position: relative;
}

.badge.pulse::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background-color: inherit;
    opacity: 0.7;
    animation: pulse 1.5s infinite;
    z-index: -1;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 0.7;
    }
    70% {
        transform: scale(1.5);
        opacity: 0;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

.sidebar-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--light);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.app-version {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 0.75rem;
}

.btn-logout {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--danger-light);
    color: var (--danger);
    border: none;
    border-radius: var(--border-radius);
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-speed);
    width: 100%;
}

.btn-logout i {
    margin-right: 0.5rem;
    font-size: 1.25rem;
}

.btn-logout:hover {
    background-color: var(--danger);
    color: white;
}

/* Main Content Styles */
.main-content {
    flex: 1;
    margin-left: var(--sidebar-width);
    transition: margin-left var(--transition-speed);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    position: relative;
}

.top-navbar {
    height: var(--header-height);
    background-color: var(--white);
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.5rem;
    position: sticky;
    top: 0;
    z-index: 990;
    transition: all var(--transition-speed);
}

.navbar-left {
    display: flex;
    align-items: center;
}

.btn-toggle-sidebar {
    background: transparent;
    border: none;
    color: var(--dark);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    margin-right: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all var(--transition-speed);
}

.btn-toggle-sidebar:hover {
    background-color: var(--light);
    color: var(--primary);
}

.page-title h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--dark);
}

.breadcrumb {
    margin-bottom: 0;
}

.breadcrumb-item a {
    color: var(--primary);
    text-decoration: none;
}

.breadcrumb-item.active {
    color: var(--text-muted);
}

.navbar-center {
    flex: 1;
    max-width: 600px;
    margin: 0 1rem;
}

.global-search .input-group {
    background-color: var(--light);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.global-search .input-group-text {
    background-color: transparent;
    border: none;
    color: var(--text-muted);
}

.global-search .form-control {
    border: none;
    background-color: transparent;
    font-size: 0.9rem;
}

.global-search .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.navbar-right {
    display: flex;
    align-items: center;
}

.btn-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: transparent;
    color: var(--dark);
    border: none;
    cursor: pointer;
    font-size: 1.25rem;
    margin-left: 0.5rem;
    position: relative;
    transition: all var(--transition-speed);
}

.btn-icon:hover {
    background-color: var(--light);
    color: var(--primary);
}

.notifications .badge {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 0.65rem;
    font-weight: 600;
    background-color: var(--danger);
    color: white;
    border-radius: 50%;
    padding: 0.25rem 0.45rem;
}

.notifications-dropdown {
    width: 350px;
    padding: 0;
    border: none;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.notifications-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background-color: var(--light);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.notifications-header h6 {
    margin-bottom: 0;
    font-weight: 600;
}

.mark-all-read {
    font-size: 0.8rem;
    color: var(--primary);
    text-decoration: none;
}

.notifications-body {
    max-height: 350px;
    overflow-y: auto;
    padding: 0.5rem 0;
}

.notification-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: background-color var(--transition-speed);
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background-color: rgba(0, 102, 179, 0.05);
}

.notification-item:hover {
    background-color: var(--light);
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.notification-icon i {
    font-size: 1.25rem;
}

.notification-content {
    flex: 1;
}

.notification-text {
    margin-bottom: 0.25rem;
    font-size: 0.85rem;
    color: var(--dark);
}

.notification-time {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.notification-action {
    background: transparent;
    border: none;
    color: var(--text-muted);
    margin-left: 0.5rem;
    cursor: pointer;
    opacity: 0;
    transition: opacity var(--transition-speed);
}

.notification-item:hover .notification-action {
    opacity: 1;
}

.notifications-footer {
    padding: 0.75rem;
    background-color: var(--light);
    border-top: 1px solid rgba(0,0,0,0.05);
}

.user-menu-toggle {
    display: flex;
    align-items: center;
    background-color: transparent;
    border: none;
    cursor: pointer;
    padding: 0.5rem 0.75rem;
    border-radius: var (--border-radius);
    transition: all var(--transition-speed);
}

.user-menu-toggle:hover {
    background-color: var(--light);
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 0.75rem;
}

.avatar-sm {
    width: 35px;
    height: 35px;
    border-radius: 50%;
}

.user-menu-toggle i {
    color: var(--text-muted);
    font-size: 1.25rem;
    margin-left: 0.5rem;
}

.dropdown-header {
    background-color: var(--light);
    padding: 1rem;
}

.dropdown-divider {
    margin: 0.25rem 0;
}

.dropdown-item {
    padding: 0.6rem 1.5rem;
    font-size: 0.9rem;
}

.dropdown-item i {
    font-size: 1.1rem;
    color: var(--text-muted);
}

.dropdown-item:hover i {
    color: inherit;
}

/* Page Content */
.page-content {
    flex: 1;
    padding: 1.5rem 1.5rem calc(var(--footer-height) + 1.5rem);
}

/* Footer */
.footer {
    height: var(--footer-height);
    background-color: var(--white);
    border-top: 1px solid rgba(0,0,0,0.05);
    padding: 0 1.5rem;
    display: flex;
    align-items: center;
    position: absolute;
    bottom: 0;
    width: 100%;
    transition: all var(--transition-speed);
}

/* Theme & Colors */
.bg-primary-light {
    background-color: rgba(0, 102, 179, 0.1);
}

.text-primary {
    color: var(--primary) !important;
}

.bg-success-light {
    background-color: rgba(40, 167, 69, 0.1);
}

.text-success {
    color: var(--success) !important;
}

.bg-warning-light {
    background-color: rgba(253, 126, 20, 0.1);
}

.text-warning {
    color: var(--warning) !important;
}

.bg-danger-light {
    background-color: rgba(220, 53, 69, 0.1);
}

.text-danger {
    color: var(--danger) !important;
}

.bg-info-light {
    background-color: rgba(23, 162, 184, 0.1);
}

.text-info {
    color: var(--info) !important;
}

/* Responsive Styling */
@media (max-width: 991.98px) {
    :root {
        --sidebar-width: 0;
    }
    
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.expanded {
        transform: translateX(0);
        width: 280px;
    }
    
    .main-content {
        margin-left: 0;
    }
}

@media (max-width: 767.98px) {
    .page-title {
        display: block;
    }
    
    .top-navbar {
        height: auto;
        padding: 1rem;
        flex-wrap: wrap;
    }
    
    .navbar-right {
        margin-left: auto;
    }
}

@media (max-width: 575.98px) {
    .notifications-dropdown {
        width: 300px;
    }
    
    .page-content {
        padding: 1rem 1rem calc(var(--footer-height) + 1rem);
    }
}

/* Collapsed Sidebar */
.sidebar-collapsed .sidebar {
    width: var(--sidebar-collapsed-width);
}

.sidebar-collapsed .main-content {
    margin-left: var(--sidebar-collapsed-width);
}

.sidebar-collapsed .school-info,
.sidebar-collapsed .nav-link span,
.sidebar-collapsed .submenu-indicator,
.sidebar-collapsed .nav-divider span,
.sidebar-collapsed .btn-logout span,
.sidebar-collapsed .user-info,
.sidebar-collapsed .app-version,
.sidebar-collapsed .badge {
    display: none;
}

.sidebar-collapsed .logo-wrapper {
    margin-right: 0;
}

.sidebar-collapsed .sidebar-nav .nav-link {
    justify-content: center;
    padding: 0.75rem;
}

.sidebar-collapsed .sidebar-nav .nav-link i {
    margin-right: 0;
    font-size: 1.5rem;
}

.sidebar-collapsed .has-submenu .submenu-content {
    position: absolute;
    left: 100%;
    top: 0;
    width: 200px;
    background-color: var(--white);
    box-shadow: var(--card-shadow);
    border-radius: var(--border-radius);
    padding: 0.5rem;
    z-index: 100;
    visibility: hidden;
    opacity: 0;
    transition: all var(--transition-speed);
}

.sidebar-collapsed .has-submenu:hover .submenu-content {
    visibility: visible;
    opacity: 1;
}

.sidebar-collapsed .nav-divider {
    text-align: center;
    padding: 1rem 0 0.5rem;
}

.sidebar-collapsed .nav-divider::after {
    content: '•••';
    display: block;
    color: var(--text-muted);
    font-size: 0.7rem;
}

.sidebar-collapsed .user-profile-preview {
    justify-content: center;
    padding: 0.75rem;
}

.sidebar-collapsed .user-avatar {
    margin-right: 0;
}

.sidebar-collapsed .sidebar-search {
    display: none;
}

/* Dark Mode */
.dark-mode .top-navbar,
.dark-mode .footer,
.dark-mode .card {
    background-color: var(--sidebar-bg);
    color: var(--text-color);
}

.dark-mode .card {
    border: 1px solid var(--border-color);
}

.dark-mode .text-dark {
    color: var(--text-color) !important;
}

.dark-mode .border-light {
    border-color: var(--border-color) !important;
}

.dark-mode .dropdown-menu {
    background-color: var(--sidebar-bg);
    border-color: var(--border-color);
}

.dark-mode .dropdown-item {
    color: var(--text-color);
}

.dark-mode .dropdown-item:hover {
    background-color: var(--light);
}

.dark-mode .dropdown-divider {
    border-color: var(--border-color);
}

.dark-mode .nav-link,
.dark-mode .page-title h1,
.dark-mode .notification-text {
    color: var (--text-color);
}

.dark-mode .btn-icon,
.dark-mode .btn-toggle-sidebar {
    color: var(--text-color);
}

.dark-mode .btn-toggle-sidebar:hover,
.dark-mode .btn-icon:hover {
    background-color: var(--light);
    color: var(--primary);
}

.dark-mode .notification-item.unread {
    background-color: rgba(77, 148, 255, 0.1);
}

.dark-mode .notification-item:hover {
    background-color: var(--light);
}

.dark-mode .sidebar-footer,
.dark-mode .notifications-header,
.dark-mode .notifications-footer,
.dark-mode .dropdown-header {
    border-color: var(--border-color);
    background-color: var(--light);
}

.dark-mode .notification-item {
    border-color: var(--border-color);
}
</style>

<!-- Additional JavaScript for Dashboard -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const body = document.body;
    
    sidebarToggle.addEventListener('click', function() {
        if (window.innerWidth < 992) {
            sidebar.classList.toggle('expanded');
        } else {
            body.classList.toggle('sidebar-collapsed');
        }
    });
    
    // Submenu toggle
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.closest('.has-submenu');
            
            // If sidebar is collapsed and screen is large, don't toggle
            if (body.classList.contains('sidebar-collapsed') && window.innerWidth >= 992) {
                return;
            }
            
            parent.classList.toggle('open');
        });
    });
    
    // Theme toggle
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = themeToggle.querySelector('i');
    
    // Check if user has already set a preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
        themeIcon.classList.remove('bx-moon');
        themeIcon.classList.add('bx-sun');
    }
    
    themeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
            themeIcon.classList.remove('bx-moon');
            themeIcon.classList.add('bx-sun');
        } else {
            localStorage.setItem('darkMode', 'disabled');
            themeIcon.classList.remove('bx-sun');
            themeIcon.classList.add('bx-moon');
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth < 992) {
            body.classList.remove('sidebar-collapsed');
            if (sidebar.classList.contains('expanded')) {
                document.addEventListener('click', closeSidebarOnClickOutside);
            }
        } else {
            sidebar.classList.remove('expanded');
            document.removeEventListener('click', closeSidebarOnClickOutside);
        }
    });
    
    function closeSidebarOnClickOutside(event) {
        if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
            sidebar.classList.remove('expanded');
            document.removeEventListener('click', closeSidebarOnClickOutside);
        }
    }
    
    // Tooltip initialization
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    }
    
    // Notification item actions
    const notificationActions = document.querySelectorAll('.notification-action');
    notificationActions.forEach(action => {
        action.addEventListener('click', function(e) {
            e.preventDefault();
            const notification = this.closest('.notification-item');
            notification.classList.toggle('unread');
        });
    });
    
    // Mark all read action
    const markAllRead = document.querySelector('.mark-all-read');
    if (markAllRead) {
        markAllRead.addEventListener('click', function(e) {
            e.preventDefault();
            const unreadNotifications = document.querySelectorAll('.notification-item.unread');
            unreadNotifications.forEach(notification => {
                notification.classList.remove('unread');
            });
            
            // Update notification badge
            const notificationBadge = document.querySelector('.notifications .badge');
            if (notificationBadge) {
                notificationBadge.style.display = 'none';
            }
        });
    }
});
</script>

<script>
    // Print all session data for debugging
    console.log('All sessions:', @json(session()->all()));
</script>
@endsection