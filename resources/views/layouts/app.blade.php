<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SMAN 1 Girsip') }}</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Add Material Icons as backup -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Custom Icon Fix CSS -->
    <style>
        .icon-fix {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            min-height: 24px;
        }
        /* Ensure icons are visible with proper contrast */
        .sidebar-icon {
            color: rgba(255, 255, 255, 0.85);
            margin-right: 8px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Navbar -->
            @include('layouts.navbar')
            
            <!-- Main Content -->
            <div class="content-container">
                @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="py-2 bg-white">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center small">
                        <div>© {{ date('Y') }} SMAN 1 GIRSIP - Sistem Informasi Arsip Digital</div>
                        <div>
                            <a href="{{ route('bantuan') }}">Bantuan</a> |
                            <a href="{{ route('privasi') }}">Privasi</a> |
                            <a href="{{ route('profil') }}">Profil</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom scripts -->
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('scripts')
</body>
</html>
