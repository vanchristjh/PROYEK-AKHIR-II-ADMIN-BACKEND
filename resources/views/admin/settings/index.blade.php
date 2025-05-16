@php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Helper function to check if route exists
function routeExists($name) {
    return Route::has($name);
}
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem - Admin Panel</title>
    
    <!-- Custom fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css for smooth transitions -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
            --primary: #6366F1;
            --primary-dark: #5152C4;
            --secondary: #8A8DFF;
            --light: #F0F0FF;
            --white: #FFFFFF;
            --dark: #2D326B;
            --gray-bg: #f8f9fc;
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--gray-bg);
            color: #333;
            overflow-x: hidden;
        }
        
        /* Improved sidebar styling */
        .sidebar {
            background: var(--primary);
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            color: var(--white);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: var(--transition);
        }
        
        .sidebar .logo {
            padding: 1.5rem 1rem;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }
        
        .sidebar .logo:hover {
            transform: translateY(-2px);
        }
        
        .sidebar .logo img {
            width: 38px;
            height: 38px;
            transition: var(--transition);
        }
        
        .sidebar .logo-text {
            margin-left: 10px;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .sidebar .logo-caption {
            font-size: 0.8rem;
            opacity: 0.8;
            display: block;
            margin-top: -5px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
        }
        
        .sidebar-menu h6 {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
            opacity: 0.8;
            margin-top: 1rem;
            color: rgba(255,255,255,0.7);
        }
        
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--white);
            opacity: 0.8;
            transition: var(--transition);
            text-decoration: none;
            border-left: 0px solid transparent;
        }
        
        .sidebar-menu li a:hover,
        .sidebar-menu li.active a {
            opacity: 1;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--white);
            padding-left: calc(1.5rem - 4px);
            transform: translateX(4px);
        }
        
        .sidebar-menu li a i {
            margin-right: 10px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: var(--transition);
        }
        
        .sidebar-menu li a:hover i {
            transform: scale(1.2);
        }
        
        /* Main content styling improvements */
        .main-content {
            padding: 2rem 1rem;
            transition: var(--transition);
        }
        
        .navbar {
            background-color: var(--white);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--dark);
        }
        
        /* Breadcrumb styling */
        .breadcrumb {
            background-color: transparent;
            padding: 0.5rem 0;
            margin-bottom: 1rem;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            font-size: 1.2rem;
            line-height: 1;
            color: var(--primary);
        }
        
        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 600;
        }
        
        .breadcrumb-item a {
            color: #6e707e;
            transition: var(--transition);
        }
        
        .breadcrumb-item a:hover {
            color: var(--primary);
            text-decoration: none;
        }
        
        /* Card improvements */
        .welcome-card {
            background: var(--primary);
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: var(--white);
            border-radius: 1rem;
            padding: 1.8rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
            transition: var(--transition);
        }
        
        .welcome-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3);
        }
        
        .welcome-card .crown-icon {
            font-size: 2.2rem;
            margin-bottom: 0.8rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .welcome-card h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card-header {
            background-color: var(--white);
            border-bottom: 1px solid #e3e6f0;
            font-weight: 700;
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
            padding: 1.2rem 1.5rem;
        }
        
        .tab-content {
            padding: 1.5rem 0;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            border-radius: 0.5rem;
            transition: var(--transition);
            box-shadow: 0 4px 6px rgba(99, 102, 241, 0.1);
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(99, 102, 241, 0.2);
        }
        
        .btn-secondary {
            border-radius: 0.5rem;
            transition: var(--transition);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            font-weight: 600;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        
        .nav-tabs {
            border-bottom: 2px solid #e3e6f0;
        }
        
        .nav-tabs .nav-link {
            color: #6e707e;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
            position: relative;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary);
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            background-color: transparent;
            font-weight: 700;
        }
        
        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--primary);
            border-radius: 3px 3px 0 0;
        }
        
        /* Custom file input styling */
        .custom-file-label {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .custom-file-input {
            cursor: pointer;
        }
        
        .custom-file-label::after {
            background-color: var(--primary);
            color: var(--white);
            border-radius: 0 0.5rem 0.5rem 0;
            transition: var(--transition);
        }
        
        .custom-file-input:hover ~ .custom-file-label::after {
            background-color: var(--primary-dark);
        }
        
        .input-group {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .input-group-text {
            background-color: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }
        
        .form-control {
            border-radius: 0.5rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }
        
        /* User profile section */
        .user-profile {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
            cursor: pointer;
        }
        
        .user-profile:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .user-profile .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: bold;
            font-size: 1.2rem;
            transition: var(--transition);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .user-profile:hover .avatar {
            transform: scale(1.1);
        }
        
        .user-profile .user-info {
            margin-left: 12px;
        }
        
        .user-profile .user-name {
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        .user-profile .user-role {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        
        .logout-btn {
            margin-left: auto;
            background: none;
            border: none;
            color: var(--white);
            opacity: 0.8;
            transition: var(--transition);
            padding: 0.5rem;
            border-radius: 50%;
        }
        
        .logout-btn:hover {
            opacity: 1;
            color: var(--white);
            background-color: rgba(255, 255, 255, 0.1);
            transform: rotate(360deg);
        }
        
        /* Alert styling */
        .alert {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .alert-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -100%;
                z-index: 1030;
                transition: left 0.3s ease;
                width: 250px;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                width: 100%;
                margin-left: 0;
            }
            
            .toggle-sidebar {
                display: block !important;
            }
        }
        
        /* Toggle button for mobile */
        .toggle-sidebar {
            display: none;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1040;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        footer {
            padding: 1.5rem 0;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0 sidebar" id="sidebar" style="position: fixed; height: 100%;">
            <a href="{{ routeExists('admin.dashboard') ? route('admin.dashboard') : '#' }}" class="logo text-decoration-none">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" onerror="this.src='https://via.placeholder.com/38'">
                <div>
                    <span class="logo-text">SMAN 1 Girsip</span>
                    <span class="logo-caption">E-Learning System</span>
                </div>
            </a>
            
            <ul class="sidebar-menu">
                <h6>DASHBOARD</h6>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.dashboard') ? route('admin.dashboard') : '#' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                
                <h6>PENGGUNA</h6>
                <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.users.index') ? route('admin.users.index') : '#' }}">
                        <i class="fas fa-users"></i> Semua Pengguna
                    </a>
                </li>
                <li class="{{ request()->is('admin/teachers*') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.teachers.index') ? route('admin.teachers.index') : '#' }}">
                        <i class="fas fa-chalkboard-teacher"></i> Guru
                    </a>
                </li>
                <li class="{{ request()->is('admin/students*') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.students.index') ? route('admin.students.index') : '#' }}">
                        <i class="fas fa-user-graduate"></i> Siswa
                    </a>
                </li>
                
                <h6>AKADEMIK</h6>
                <li class="{{ request()->is('admin/subjects*') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.subjects.index') ? route('admin.subjects.index') : '#' }}">
                        <i class="fas fa-book"></i> Mata Pelajaran
                    </a>
                </li>
                <li class="{{ request()->is('admin/classes*') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.classes.index') ? route('admin.classes.index') : '#' }}">
                        <i class="fas fa-chalkboard"></i> Kelas
                    </a>
                </li>
                <li class="{{ request()->is('admin/schedules*') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.schedules.index') ? route('admin.schedules.index') : '#' }}">
                        <i class="fas fa-calendar-alt"></i> Jadwal
                    </a>
                </li>
                
                <h6>KOMUNIKASI</h6>
                <li class="{{ request()->is('admin/announcements*') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.announcements.index') ? route('admin.announcements.index') : '#' }}">
                        <i class="fas fa-bullhorn"></i> Pengumuman
                    </a>
                </li>
                <li class="{{ request()->is('admin/messages*') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.messages.index') ? route('admin.messages.index') : '#' }}">
                        <i class="fas fa-envelope"></i> Pesan
                    </a>
                </li>
                
                <h6>SISTEM & AKUN</h6>
                <li class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.settings.index') ? route('admin.settings.index') : '#' }}">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>
                </li>
                <li class="{{ request()->is('admin/profile*') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.profile.index') ? route('admin.profile.index') : '#' }}">
                        <i class="fas fa-user"></i> Profil Saya
                    </a>
                </li>
                <li class="{{ request()->is('admin/logs*') ? 'active' : '' }}">
                    <a href="{{ routeExists('admin.logs.index') ? route('admin.logs.index') : '#' }}">
                        <i class="fas fa-history"></i> Log Aktivitas
                    </a>
                </li>
            </ul>
            
            <!-- User Profile -->
            <div class="mt-auto" style="position: absolute; bottom: 0; width: 100%;">
                <div class="user-profile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                        <div class="user-role">{{ Auth::user()->role ?? 'Administrator' }}</div>
                    </div>
                    <a href="#" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                    <form id="logout-form" action="{{ routeExists('logout') ? route('logout') : '#' }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 ml-auto main-content">
            <nav class="navbar navbar-expand-lg navbar-light bg-white rounded mb-4">
                <span class="navbar-brand">
                    <i class="fas fa-cog mr-2 text-primary"></i> Pengaturan Sistem
                </span>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <h6 class="dropdown-header">Notifikasi</h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary text-white">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">{{ date('d M Y') }}</div>
                                        <span>Pengguna baru telah mendaftar!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Lihat semua notifikasi</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent px-0">
                    <li class="breadcrumb-item"><a href="{{ routeExists('admin.dashboard') ? route('admin.dashboard') : '#' }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
                </ol>
            </nav>
            
            <div class="content animate__animated animate__fadeIn">
                <!-- Welcome Card -->
                <div class="welcome-card">
                    <i class="fas fa-crown crown-icon"></i>
                    <h2>Pengaturan Sistem</h2>
                    <p class="mb-0">Konfigurasi sistem dan pengaturan untuk aplikasi e-learning. Perubahan yang Anda buat akan diterapkan ke seluruh platform.</p>
                </div>
                
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                        <i class="fas fa-exclamation-circle mr-1"></i> Terjadi kesalahan:
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                @if(!Schema::hasTable('settings'))
                    <div class="alert alert-warning alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Tabel pengaturan belum tersedia di database. Silakan simpan pengaturan untuk membuat tabel tersebut.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                <!-- Settings Card -->
                <div class="card shadow animate__animated animate__fadeInUp">
                    <div class="card-header py-3">
                        <ul class="nav nav-tabs card-header-tabs" id="setting-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">
                                    <i class="fas fa-cog mr-1"></i> Umum
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="appearance-tab" data-toggle="tab" href="#appearance" role="tab">
                                    <i class="fas fa-palette mr-1"></i> Tampilan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab">
                                    <i class="fas fa-address-card mr-1"></i> Kontak
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab">
                                    <i class="fas fa-share-alt mr-1"></i> Media Sosial
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="{{ routeExists('admin.settings.update') ? route('admin.settings.update') : '#' }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="tab-content" id="setting-tabContent">
                                <!-- General Settings -->
                                <div class="tab-pane fade show active" id="general" role="tabpanel">
                                    <div class="form-group row">
                                        <label for="app_name" class="col-sm-3 col-form-label">Nama Sekolah</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="app_name" name="app_name" value="{{ $settings['app_name'] ?? 'SMAN 1' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="school_code" class="col-sm-3 col-form-label">Kode Sekolah</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="school_code" name="school_code" value="{{ $settings['school_code'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="school_year" class="col-sm-3 col-form-label">Tahun Ajaran</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="school_year" name="school_year" value="{{ $settings['school_year'] ?? date('Y').'/'.((int)date('Y')+1) }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="school_semester" class="col-sm-3 col-form-label">Semester</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="school_semester" name="school_semester">
                                                <option value="Ganjil" {{ ($settings['school_semester'] ?? '') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                                <option value="Genap" {{ ($settings['school_semester'] ?? '') == 'Genap' ? 'selected' : '' }}>Genap</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Appearance Settings -->
                                <div class="tab-pane fade" id="appearance" role="tabpanel">
                                    <div class="form-group row">
                                        <label for="school_logo" class="col-sm-3 col-form-label">Logo Sekolah</label>
                                        <div class="col-sm-9">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="school_logo" name="school_logo">
                                                <label class="custom-file-label" for="school_logo">Pilih file</label>
                                            </div>
                                            @if(!empty($settings['school_logo']))
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/'.$settings['school_logo']) }}" alt="Logo Sekolah" height="50" class="rounded shadow-sm">
                                                </div>
                                            @endif
                                            <small class="form-text text-muted">Ukuran yang direkomendasikan: 200x200 piksel</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="school_banner" class="col-sm-3 col-form-label">Banner Sekolah</label>
                                        <div class="col-sm-9">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="school_banner" name="school_banner">
                                                <label class="custom-file-label" for="school_banner">Pilih file</label>
                                            </div>
                                            @if(!empty($settings['school_banner']))
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/'.$settings['school_banner']) }}" alt="Banner Sekolah" height="100" class="img-fluid rounded shadow-sm">
                                                </div>
                                            @endif
                                            <small class="form-text text-muted">Ukuran yang direkomendasikan: 1200x300 piksel</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="theme_color" class="col-sm-3 col-form-label">Warna Tema</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-fill-drip"></i></span>
                                                </div>
                                                <input type="color" class="form-control" id="theme_color" name="theme_color" value="{{ $settings['theme_color'] ?? '#6366F1' }}">
                                            </div>
                                            <small class="form-text text-muted">Pilih warna utama untuk tema aplikasi</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="favicon" class="col-sm-3 col-form-label">Favicon</label>
                                        <div class="col-sm-9">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="favicon" name="favicon">
                                                <label class="custom-file-label" for="favicon">Pilih file</label>
                                            </div>
                                            @if(!empty($settings['favicon']))
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/'.$settings['favicon']) }}" alt="Favicon" height="32" class="rounded shadow-sm">
                                                </div>
                                            @endif
                                            <small class="form-text text-muted">Format .ico, .png atau .jpg (32x32 piksel)</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Contact Settings -->
                                <div class="tab-pane fade" id="contact" role="tabpanel">
                                    <div class="form-group row">
                                        <label for="school_address" class="col-sm-3 col-form-label">Alamat Sekolah</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="school_address" name="school_address" rows="3">{{ $settings['school_address'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="school_email" class="col-sm-3 col-form-label">Email Sekolah</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control" id="school_email" name="school_email" value="{{ $settings['school_email'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="school_phone" class="col-sm-3 col-form-label">Nomor Telepon</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="school_phone" name="school_phone" value="{{ $settings['school_phone'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="school_website" class="col-sm-3 col-form-label">Website</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                </div>
                                                <input type="url" class="form-control" id="school_website" name="school_website" value="{{ $settings['school_website'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="contact_person" class="col-sm-3 col-form-label">Kontak Person</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ $settings['contact_person'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Social Media Settings -->
                                <div class="tab-pane fade" id="social" role="tabpanel">
                                    <div class="form-group row">
                                        <label for="facebook" class="col-sm-3 col-form-label">Facebook</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                                </div>
                                                <input type="url" class="form-control" id="facebook" name="facebook" value="{{ $settings['facebook'] ?? '' }}" placeholder="https://facebook.com/yourschool">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="instagram" class="col-sm-3 col-form-label">Instagram</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                </div>
                                                <input type="url" class="form-control" id="instagram" name="instagram" value="{{ $settings['instagram'] ?? '' }}" placeholder="https://instagram.com/yourschool">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="twitter" class="col-sm-3 col-form-label">Twitter</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                </div>
                                                <input type="url" class="form-control" id="twitter" name="twitter" value="{{ $settings['twitter'] ?? '' }}" placeholder="https://twitter.com/yourschool">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="youtube" class="col-sm-3 col-form-label">YouTube</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                                </div>
                                                <input type="url" class="form-control" id="youtube" name="youtube" value="{{ $settings['youtube'] ?? '' }}" placeholder="https://youtube.com/c/yourschool">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tiktok" class="col-sm-3 col-form-label">TikTok</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-tiktok"></i></span>
                                                </div>
                                                <input type="url" class="form-control" id="tiktok" name="tiktok" value="{{ $settings['tiktok'] ?? '' }}" placeholder="https://tiktok.com/@yourschool">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                                </button>
                                <a href="{{ routeExists('admin.dashboard') ? route('admin.dashboard') : '#' }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="text-center">
                <p class="text-muted">Copyright &copy; SMAN 1 Girsip {{ date('Y') }} | E-Learning System v1.0</p>
            </footer>
        </div>
    </div>
</div>

<!-- Mobile sidebar toggle button -->
<button class="toggle-sidebar" id="toggleSidebar">
    <i class="fas fa-bars"></i>
</button>

<!-- Bootstrap core JavaScript-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(function() {
        // Initialize file input labels
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
            
            // Preview image if available
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                let imgElement = $(this).closest('.form-group').find('img');
                
                reader.onload = function(e) {
                    if (imgElement.length) {
                        imgElement.attr('src', e.target.result);
                    } else {
                        $(this).closest('.form-group').append('<div class="mt-2"><img src="' + e.target.result + '" height="50" class="img-fluid rounded shadow-sm"></div>');
                    }
                }.bind(this);
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Mobile sidebar toggle
        $('#toggleSidebar').on('click', function() {
            $('#sidebar').toggleClass('show');
        });
        
        // Close sidebar when clicking outside on mobile
        $(document).on('click', function(e) {
            if ($(window).width() < 768) {
                if (!$(e.target).closest('#sidebar').length && !$(e.target).closest('#toggleSidebar').length) {
                    $('#sidebar').removeClass('show');
                }
            }
        });
        
        // Apply theme color dynamically
        $('#theme_color').on('change', function() {
            document.documentElement.style.setProperty('--primary', $(this).val());
        });
        
        // Auto-close alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>
</body>
</html>
