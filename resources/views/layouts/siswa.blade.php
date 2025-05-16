<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Siswa Dashboard') - SMAN 1</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --light-bg: #f8f9fa;
            --dark-bg: #2c3e50;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f5f8fa;
        }
        
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        #sidebar {
            width: 280px;
            background-color: var(--dark-bg);
            color: white;
            transition: all 0.3s;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            z-index: 999;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            background-color: var(--secondary-color);
            text-align: center;
        }
        
        #sidebar .sidebar-header img {
            max-width: 80px;
            margin-bottom: 10px;
        }
        
        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #47748b;
        }
        
        #sidebar ul li {
            padding: 0;
            position: relative;
        }
        
        #sidebar ul li a {
            padding: 15px 20px;
            font-size: 16px;
            display: block;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        #sidebar ul li a:hover {
            background: rgba(255,255,255,0.1);
            border-left: 3px solid var(--primary-color);
        }
        
        #sidebar ul li.active > a {
            background: rgba(255,255,255,0.1);
            border-left: 3px solid var(--primary-color);
            font-weight: bold;
        }
        
        #sidebar ul li i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        #content {
            width: calc(100% - 280px);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .navbar {
            padding: 15px 0;
            background: white !important;
            border: none;
            border-radius: 0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        #sidebarCollapse {
            background: var(--primary-color);
            border: none;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
        }
        
        .user-profile img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .main-content {
            padding: 20px;
            background-color: #fff;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        
        .footer {
            text-align: center;
            padding: 15px;
            background: white;
            position: relative;
            bottom: 0;
            width: 100%;
            border-top: 1px solid #eee;
        }
        
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -280px;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                width: 100%;
            }
            #sidebarCollapse span {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('img/school-logo.png') }}" alt="School Logo" class="img-fluid" onerror="this.src='https://via.placeholder.com/80'">
                <h3>SMAN 1 GIRSIP</h3>
                <p class="text-light mb-0">Portal Siswa</p>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->is('siswa/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('siswa.dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="{{ request()->is('submissions*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.submissions.index') }}">
                        <i class="fas fa-book"></i> Tugas
                    </a>
                </li>
                <li class="{{ request()->is('siswa/materials*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.materials.index') }}" onclick="window.location.href='{{ route('siswa.materials.index') }}'">
                        <i class="fas fa-file-alt"></i> Materi
                    </a>
                    <ul class="{{ request()->is('siswa/materials*') ? 'collapse show' : 'collapse' }} list-unstyled" id="materiSubmenu">
                        <li>
                            <a href="{{ route('siswa.materials.index') }}" onclick="window.location.href='{{ route('siswa.materials.index') }}'"><i class="fas fa-circle fa-xs"></i> Semua Materi</a>
                        </li>
                        <li>
                            <a href="{{ route('siswa.materials.index') }}?type=downloadable" onclick="window.location.href='{{ route('siswa.materials.index') }}?type=downloadable'"><i class="fas fa-circle fa-xs"></i> Download Materi</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-calendar-alt"></i> Jadwal
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-chart-bar"></i> Nilai
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-user"></i> Profil Saya
                    </a>
                </li>
            </ul>
            
            <div class="px-4 py-3 mt-auto text-center text-light">
                <small>&copy; {{ date('Y') }} SMAN 1 GIRSIP</small>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="badge bg-danger rounded-pill">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                                <li><a class="dropdown-item" href="#">Tugas baru ditambahkan</a></li>
                                <li><a class="dropdown-item" href="#">Nilai tugas telah diupdate</a></li>
                                <li><a class="dropdown-item" href="#">Pengumuman baru</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#">Lihat semua</a></li>
                            </ul>
                        </div>
                        
                        <div class="dropdown ms-3">
                            <a class="user-profile dropdown-toggle" href="#" id="userDropdown" 
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://via.placeholder.com/35" alt="User Avatar">
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Content Section -->
            <div class="main-content">
                @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="container">
                    <span class="text-muted">SMAN 1 GIRSIP &copy; {{ date('Y') }} | Sistem Informasi Akademik</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
            });
            
            // Enable Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
