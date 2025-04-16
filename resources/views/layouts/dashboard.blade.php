@php
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
@endphp

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
                        <li><h6 class="dropdown-header">Data Akademik</h6></li>
                        <li><a class="dropdown-item" href="{{ route('students.create') }}"><i class="bx bx-user-plus"></i> Tambah Siswa</a></li>
                        <li><a class="dropdown-item" href="{{ route('teachers.create') }}"><i class="bx bx-user-voice"></i> Tambah Guru</a></li>
                        <li><a class="dropdown-item" href="{{ route('classes.create') }}"><i class="bx bx-buildings"></i> Tambah Kelas</a></li>
                        <li><a class="dropdown-item" href="{{ route('subjects.create') }}"><i class="bx bx-book-alt"></i> Tambah Mata Pelajaran</a></li>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Kegiatan Akademik</h6></li>
                        <li><a class="dropdown-item" href="{{ route('attendance.create') }}"><i class="bx bx-check-square"></i> Isi Absensi Siswa</a></li>
                        <li><a class="dropdown-item" href="{{ route('teacher-attendance.create') }}"><i class="bx bx-user-check"></i> Isi Absensi Guru</a></li>
                        <li><a class="dropdown-item" href="{{ route('schedules.create') }}"><i class="bx bx-calendar-plus"></i> Buat Jadwal</a></li>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Informasi & Laporan</h6></li>
                        <li><a class="dropdown-item" href="{{ route('announcements.create') }}"><i class="bx bx-bell-plus"></i> Buat Pengumuman</a></li>
                        <li><a class="dropdown-item" href="{{ route('academic-calendar.create') }}"><i class="bx bx-calendar-event"></i> Tambah Agenda Kalender</a></li>
                        <li><a class="dropdown-item" href="{{ route('attendance.report') }}"><i class="bx bx-bar-chart-alt-2"></i> Laporan Kehadiran Siswa</a></li>
                        <li><a class="dropdown-item" href="{{ route('teacher-attendance.report') }}"><i class="bx bx-line-chart"></i> Laporan Kehadiran Guru</a></li>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Sistem</h6></li>
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bx bx-cog"></i> Pengaturan</a></li>
                        <li><a class="dropdown-item" href="{{ route('documentation.api') }}"><i class="bx bx-help-circle"></i> Dokumentasi API</a></li>
                    </ul>
                </div>
                
                <!-- Theme Toggle -->
                <button class="btn-icon theme-toggle" id="themeToggle">
                    <i class="bx bx-moon"></i>
                </button>
                
                <!-- Notifications -->
                <div class="notifications dropdown">
                    <button class="btn-icon" data-bs-toggle="dropdown" aria-expanded="false" id="notificationDropdown">
                        <i class="bx bx-bell"></i>
                        <span class="badge notification-badge" id="notification-badge">0</span>
                    </button>
                    <div class="dropdown-menu notifications-dropdown dropdown-menu-end">
                        <div class="notifications-header">
                            <h6>Notifikasi</h6>
                            <a href="#" class="mark-all-read" id="markAllReadBtn">Tandai sudah dibaca</a>
                        </div>
                        <div class="notifications-body" id="notificationsContainer">
                            <div class="text-center py-5" id="notification-loading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 small text-muted">Memuat notifikasi...</p>
                            </div>
                            <div class="text-center py-5" id="notification-empty" style="display: none;">
                                <i class="bx bx-bell-off text-muted" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0">Tidak ada notifikasi</p>
                            </div>
                        </div>
                        <div class="notifications-footer">
                            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-light w-100">Lihat Semua Notifikasi</a>
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
    --primary-hover: #0056a6;
    --secondary: #ffc107;
    --secondary-light: #fff3cd;
    --success: #28a745;
    --success-light: #d4edda;
    --danger: #dc3545;
    --danger-light: #f8d7da;
    --warning: #fd7e14;
    --warning-light: #fff3cd;
    --info: #17a2b8;
    --info-light: #d1ecf1;
    --dark: #343a40;
    --light: #f8f9fa;
    --gray: #6c757d;
    --gray-light: #e9ecef;
    --body-bg: #f5f7fa;
    --sidebar-bg: #ffffff;
    --white: #ffffff;
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 70px;
    --header-height: 70px;
    --footer-height: 50px;
    --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    --card-shadow-hover: 0 6px 16px rgba(0, 0, 0, 0.12);
    --transition-speed: 0.3s;
    --border-radius: 0.5rem;
    --border-radius-sm: 0.25rem;
    --border-radius-lg: 0.75rem;
    --font-main: 'Nunito', 'Segoe UI', Roboto, -apple-system, BlinkMacSystemFont, sans-serif;
    --text-color: #495057;
    --text-muted: #6c757d;
    --border-color: #e9ecef;
}

.dark-mode {
    --primary: #4d94ff;
    --primary-dark: #0066cc;
    --primary-light: #1a2e44;
    --primary-hover: #5a9dff;
    --body-bg: #121212;
    --sidebar-bg: #1e1e2d;
    --white: #1e1e2d;
    --light: #2a2a3c;
    --dark: #f8f9fa;
    --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    --card-shadow-hover: 0 6px 16px rgba(0, 0, 0, 0.4);
    --text-color: #e4e6ef;
    --text-muted: #9899ac;
    --gray-light: #32324a;
    --border-color: #2b2b40;
}

body {
    font-family: var(--font-main);
    background-color: var(--body-bg);
    color: var(--text-color);
    transition: background-color var(--transition-speed), color var(--transition-speed);
    overflow-x: hidden;
    letter-spacing: 0.2px;
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
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.07);
    display: flex;
    flex-direction: column;
    transition: all var(--transition-speed) ease;
    z-index: 1000;
    overflow-y: auto;
    scrollbar-width: thin;
}

.sidebar::-webkit-scrollbar {
    width: 5px;
}

.sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.15);
    border-radius: 10px;
}

.dark-mode .sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.15);
}

.sidebar-header {
    padding: 1.75rem 1.5rem;
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
    margin-right: 12px;
    background: var(--primary-light);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(0, 102, 179, 0.2);
}

.school-logo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.logo-wrapper:hover .school-logo {
    transform: scale(1.1);
}

.school-info {
    display: flex;
    flex-direction: column;
}

.school-name {
    color: var(--primary);
    font-weight: 700;
    margin-bottom: 2px;
    font-size: 1.1rem;
}

.school-location {
    color: var(--text-muted);
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* Updated settings link style */
.settings-link {
    margin: 0 1rem 1.5rem;
    transition: transform 0.2s ease;
}

.settings-link a {
    background-color: var(--primary-light);
    border-radius: var(--border-radius);
    position: relative;
    overflow: hidden;
}

.settings-link a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: 0.5s;
}

.settings-link a:hover::before {
    left: 100%;
}

.settings-link:hover {
    transform: translateY(-3px);
}

.sidebar-search {
    padding: 0 1.5rem 1.25rem;
}

.sidebar-search .input-group {
    background-color: var(--light);
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: box-shadow 0.3s ease;
}

.sidebar-search .input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(0, 102, 179, 0.15);
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
    color: var(--text-color);
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
    margin-bottom: 0.35rem;
    border-radius: var(--border-radius);
    transition: background-color var(--transition-speed), transform 0.2s ease;
}

.nav-item:hover {
    transform: translateX(3px);
}

.nav-link {
    display: flex;
    align-items: center;
    color: var(--dark);
    padding: 0.8rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: all var(--transition-speed);
    position: relative;
    overflow: hidden;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: var(--primary);
    transition: width 0.3s ease;
}

.nav-item:hover .nav-link::after {
    width: 100%;
}

.nav-link i {
    font-size: 1.25rem;
    margin-right: 0.75rem;
    color: var(--text-muted);
    transition: color var(--transition-speed), transform 0.2s ease;
}

.nav-item:hover .nav-link i {
    transform: translateY(-2px);
}

.nav-item:hover .nav-link {
    background-color: var(--light);
}

.nav-item.active .nav-link {
    background-color: var(--primary-light);
    color: var(--primary);
    font-weight: 600;
}

.nav-item.active .nav-link i, 
.nav-item:hover .nav-link i {
    color: var(--primary);
}

.nav-divider {
    padding: 1.25rem 1.5rem 0.5rem;
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1.2px;
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
    transition: max-height var(--transition-speed) ease;
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
    transform: translateX(3px);
}

.submenu-content li.active a {
    color: var(--primary);
    background-color: var(--primary-light);
    font-weight: 600;
}

.submenu-content li.active a i {
    color: var(--primary);
}

.badge {
    font-size: 0.65rem;
    padding: 0.25rem 0.65rem;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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
    animation: pulse 1.8s infinite;
    z-index: -1;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 0.7;
    }
    70% {
        transform: scale(1.7);
        opacity: 0;
    }
    100% {
        transform: scale(1.7);
        opacity: 0;
    }
}

.sidebar-footer {
    padding: 1.25rem 1.5rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: auto;
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
    color: var(--danger);
    border: none;
    border-radius: var(--border-radius);
    padding: 0.6rem 1.25rem;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-speed);
    width: 100%;
    overflow: hidden;
    position: relative;
}

.btn-logout::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: 0.5s;
}

.btn-logout:hover::before {
    left: 100%;
}

.btn-logout i {
    margin-right: 0.75rem;
    font-size: 1.25rem;
    transition: transform 0.3s ease;
}

.btn-logout:hover {
    background-color: var(--danger);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.25);
}

.btn-logout:hover i {
    transform: translateX(-3px);
}

.btn-logout:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.25);
}

/* Main Content Styles */
.main-content {
    flex: 1;
    margin-left: var(--sidebar-width);
    transition: margin-left var(--transition-speed) ease;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    position: relative;
}

.top-navbar {
    height: var(--header-height);
    background-color: var(--white);
    box-shadow: 0 2px 15px rgba(0,0,0,0.04);
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
    transform: rotate(180deg);
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

.breadcrumb-item a:hover {
    color: var(--primary-hover);
    text-decoration: underline;
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
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: transparent;
    color: var(--dark);
    border: none;
    cursor: pointer;
    font-size: 1.25rem;
    margin-left: 0.7rem;
    position: relative;
    transition: all var(--transition-speed);
    overflow: hidden;
}

.btn-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0, 102, 179, 0.1), transparent);
    transition: 0.5s;
}

.btn-icon:hover::before {
    left: 100%;
}

.btn-icon:hover {
    background-color: var(--light);
    color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.btn-icon:active {
    transform: translateY(0);
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
    box-shadow: 0 3px 5px rgba(220, 53, 69, 0.2);
}

.notifications-dropdown {
    width: 350px;
    padding: 0;
    border: none;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    animation: fadeInDown 0.3s ease;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notifications-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    background-color: var(--light);
    border-bottom: 1px solid var(--border-color);
}

.notifications-header h6 {
    margin-bottom: 0;
    font-weight: 600;
    color: var(--dark);
}

.mark-all-read {
    font-size: 0.8rem;
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
}

.mark-all-read:hover {
    color: var(--primary-hover);
    text-decoration: underline;
}

.notifications-body {
    max-height: 350px;
    overflow-y: auto;
    padding: 0.5rem 0;
}

.notification-item {
    display: flex;
    align-items: center;
    padding: 0.9rem 1.25rem;
    border-bottom: 1px solid var(--border-color);
    transition: background-color var(--transition-speed), transform 0.2s ease;
    cursor: pointer;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background-color: rgba(0, 102, 179, 0.05);
}

.notification-item:hover {
    background-color: var(--light);
    transform: translateY(-2px);
}

.notification-icon {
    width: 42px;
    height: 42px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
    transition: transform 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.notification-item:hover .notification-icon {
    transform: scale(1.1);
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
    font-weight: 500;
    line-height: 1.4;
}

.notification-time {
    font-size: 0.75rem;
    color: var(--text-muted);
    display: block;
}

.notification-action {
    background: transparent;
    border: none;
    color: var(--text-muted);
    margin-left: 0.5rem;
    cursor: pointer;
    opacity: 0;
    transition: opacity var(--transition-speed), color 0.2s ease, transform 0.2s ease;
}

.notification-item:hover .notification-action {
    opacity: 1;
}

.notification-action:hover {
    color: var(--primary);
    transform: rotate(90deg);
}

.notifications-footer {
    padding: 0.75rem 1rem;
    background-color: var(--light);
    border-top: 1px solid var(--border-color);
}

.notifications-footer .btn {
    transition: all 0.2s ease;
    font-weight: 500;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.notifications-footer .btn:hover {
    box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    transform: translateY(-1px);
}

.user-menu-toggle {
    display: flex;
    align-items: center;
    background-color: transparent;
    border: none;
    cursor: pointer;
    padding: 0.5rem 0.75rem;
    border-radius: var(--border-radius);
    transition: all var(--transition-speed);
}

.user-menu-toggle:hover {
    background-color: var(--light);
}

.user-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    margin-right: 0.75rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}

.user-menu-toggle:hover .user-avatar {
    transform: scale(1.1);
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
    transition: transform 0.3s ease;
}

.user-menu-toggle:hover i {
    transform: rotate(180deg);
}

.dropdown-header {
    background-color: var(--light);
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.dropdown-divider {
    margin: 0.25rem 0;
    border-top-color: var(--border-color);
}

.dropdown-item {
    padding: 0.7rem 1.5rem;
    font-size: 0.9rem;
    color: var(--text-color);
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.dropdown-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0, 102, 179, 0.05), transparent);
    transition: 0.5s;
}

.dropdown-item:hover::before {
    left: 100%;
}

.dropdown-item:hover {
    background-color: var(--light);
    color: var(--primary);
    transform: translateX(5px);
}

.dropdown-item i {
    font-size: 1.1rem;
    color: var(--text-muted);
    margin-right: 0.5rem;
    transition: transform 0.2s ease;
}

.dropdown-item:hover i {
    color: var(--primary);
    transform: translateY(-2px);
}

/* Page Content */
.page-content {
    flex: 1;
    padding: 1.75rem 1.75rem calc(var(--footer-height) + 1.75rem);
}

/* Footer */
.footer {
    height: var(--footer-height);
    background-color: var(--white);
    border-top: 1px solid var(--border-color);
    padding: 0 1.75rem;
    display: flex;
    align-items: center;
    position: absolute;
    bottom: 0;
    width: 100%;
    transition: all var(--transition-speed);
    font-size: 0.85rem;
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

/* Alert Styling */
.alert {
    border-radius: var(--border-radius);
    border-left-width: 4px;
    padding: 1rem 1rem 1rem 1.25rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: var(--success-light);
    border-left-color: var(--success);
}

.alert-danger {
    background-color: var(--danger-light);
    border-left-color: var(--danger);
}

.alert .btn-close {
    font-size: 0.8rem;
    padding: 0.5rem;
    background-size: 0.8rem;
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
        padding: 1.25rem 1.25rem calc(var(--footer-height) + 1.25rem);
    }
    
    .alert {
        padding: 0.75rem;
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
    padding: 0.75rem 0.5rem;
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
    padding: 1.25rem 0 0.75rem;
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
    color: var(--text-color);
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
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                sidebar.classList.toggle('expanded');
                
                // Add click outside listener if sidebar is expanded
                if (sidebar.classList.contains('expanded')) {
                    setTimeout(() => {
                        document.addEventListener('click', closeSidebarOnClickOutside);
                    }, 10);
                }
            } else {
                body.classList.toggle('sidebar-collapsed');
            }
        });
    }
    
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
            
            // Close other open submenus
            if (!parent.classList.contains('open')) {
                document.querySelectorAll('.has-submenu.open').forEach(openSubmenu => {
                    if (openSubmenu !== parent) {
                        openSubmenu.classList.remove('open');
                    }
                });
            }
                
            parent.classList.toggle('open');
        });
    });
    
    // Theme toggle
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        const themeIcon = themeToggle.querySelector('i');
        
        // Check if user has already set a preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
            if (themeIcon) {
                themeIcon.classList.remove('bx-moon');
                themeIcon.classList.add('bx-sun');
            }
        }
        
        themeToggle.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
             
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                if (themeIcon) {
                    themeIcon.classList.remove('bx-moon');
                    themeIcon.classList.add('bx-sun');
                }
            } else {
                localStorage.setItem('darkMode', 'disabled');
                if (themeIcon) {
                    themeIcon.classList.remove('bx-sun');
                    themeIcon.classList.add('bx-moon');
                }
            }
        });
    }
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth < 992) {
            body.classList.remove('sidebar-collapsed');
        } else {
            if (sidebar) {
                sidebar.classList.remove('expanded');
                document.removeEventListener('click', closeSidebarOnClickOutside);
            }
        }
    });
    
    function closeSidebarOnClickOutside(event) {
        if (sidebar && sidebarToggle && !sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
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
            e.stopPropagation();
            const notification = this.closest('.notification-item');
            notification.classList.toggle('unread');
            
            // Update notification badge count
            updateNotificationBadge();
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
            updateNotificationBadge();
        });
    }
    
    // Function to update notification badge
    function updateNotificationBadge() {
        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
        const notificationBadge = document.querySelector('.notifications .badge');
        
        if (notificationBadge) {
            if (unreadCount > 0) {
                notificationBadge.textContent = unreadCount;
                notificationBadge.style.display = '';
            } else {
                notificationBadge.style.display = 'none';
            }
        }
    }
    
    // Initialize notification badge
    updateNotificationBadge();
    
    // Add functionality for sidebar search
    const sidebarSearch = document.querySelector('.sidebar-search input');
    if (sidebarSearch) {
        sidebarSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const navItems = document.querySelectorAll('.sidebar-nav .nav-item:not(.nav-divider)');
            
            navItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Prevent dropdown menus from closing when clicking inside
    document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
        dropdown.addEventListener('click', function(event) {
            if (event.target.tagName !== 'A' && event.target.tagName !== 'BUTTON') {
                event.stopPropagation();
            }
        });
    });
    
    // Close alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
<script src="{{ asset('js/sidebar-fix.js') }}"></script>
<!-- Add this in the head section of the layout -->
<link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endsection