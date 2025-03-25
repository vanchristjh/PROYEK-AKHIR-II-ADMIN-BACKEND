<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SMA Admin') }} - @yield('page-title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #0066b3;
            --primary-dark: #004a80;
            --primary-light: #e6f0f9;
            --success-color: #10b981;
            --info-color: #3b82f6;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --gray-color: #94a3b8;
            --sidebar-width: 280px;
            --header-height: 60px;
            --border-radius: 0.5rem;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            overflow-x: hidden;
        }
        
        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            transition: all var(--transition-speed) ease;
            z-index: 1000;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
        
        .sidebar-logo {
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        
        .logo-container {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.1);
            margin: 0.75rem 0;
        }
        
        .sidebar .nav-container {
            padding: 0 0.75rem;
        }
        
        .sidebar .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar .nav-link i {
            font-size: 1.25rem;
            margin-right: 0.75rem;
            width: 24px;
            text-align: center;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(3px);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            font-weight: 500;
        }
        
        .sidebar .nav-section {
            padding: 0 1rem;
        }
        
        .sidebar-heading {
            font-size: 0.7rem;
            letter-spacing: 0.05rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 600;
        }
        
        .sidebar-footer {
            background-color: rgba(0, 0, 0, 0.1);
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .avatar {
            width: 38px;
            height: 38px;
            object-fit: cover;
        }
        
        /* Content Area Styling */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: 100vh;
            transition: all var(--transition-speed) ease;
        }
        
        .page-header {
            background-color: #fff;
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .page-title {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 0;
            color: var(--dark-color);
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background-color: #fff;
            padding: 1rem 1.25rem;
        }
        
        .card-title {
            color: var(--dark-color);
            font-weight: 600;
        }
        
        /* Button Styling */
        .btn {
            font-weight: 500;
            border-radius: 0.5rem;
            padding: 0.375rem 1rem;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            transform: translateY(-1px);
        }
        
        /* Table Styling */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: middle;
            border-color: #e9ecef;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .table-hover tbody tr:hover {
            background-color: var(--primary-light);
        }
        
        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
            border-bottom-width: 1px;
        }
        
        /* Form Controls */
        .form-control, .form-select {
            border-radius: 0.5rem;
            border-color: #dee2e6;
            padding: 0.5rem 0.75rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 102, 179, 0.25);
        }
        
        /* Form Label */
        .form-label {
            font-weight: 500;
            color: #4b5563;
        }
        
        /* Badge Styling */
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
            border-radius: 0.25rem;
        }
        
        /* Alert Styling */
        .alert {
            border-radius: 0.5rem;
            border: none;
        }
        
        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }
        
        /* Custom Utilities */
        .hover-shadow {
            transition: all 0.3s ease;
        }
        
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            :root {
                --sidebar-width: 70px;
            }
            
            .sidebar .nav-link span, 
            .sidebar .sidebar-heading, 
            .sidebar-footer .flex-grow-1 {
                display: none;
            }
            
            .sidebar .nav-link i {
                margin-right: 0;
                font-size: 1.5rem;
            }
            
            .sidebar-logo {
                padding: 1rem 0.5rem;
            }
            
            .logo-container {
                margin-bottom: 0;
            }
            
            .sidebar-logo .mt-2 {
                display: none;
            }
            
            .main-content {
                margin-left: var(--sidebar-width);
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-header .page-actions {
                margin-top: 1rem;
                width: 100%;
            }
        }
        
        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            body.dark-mode {
                background-color: #121212;
                color: #e5e5e5;
            }
            
            body.dark-mode .card,
            body.dark-mode .page-header {
                background-color: #1e1e1e;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            }
            
            body.dark-mode .card-header {
                background-color: #1e1e1e;
                border-bottom-color: #333;
            }
            
            body.dark-mode .table {
                color: #e5e5e5;
            }
            
            body.dark-mode .table th {
                background-color: #333;
            }
            
            body.dark-mode .table-hover tbody tr:hover {
                background-color: #2a2a2a;
            }
            
            body.dark-mode .form-control,
            body.dark-mode .form-select {
                background-color: #2a2a2a;
                border-color: #444;
                color: #e5e5e5;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            if (tooltipTriggerList.length > 0) {
                [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            }
            
            // Initialize Select2
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    theme: 'bootstrap-5'
                });
            }
            
            // Initialize Flatpickr
            if (typeof flatpickr !== 'undefined') {
                flatpickr(".datepicker", {
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    altInput: true,
                    altFormat: "d M Y"
                });
            }
            
            // Mobile Menu Toggle
            const menuToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (menuToggle && sidebar) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (sidebar && sidebar.classList.contains('show') && !sidebar.contains(event.target) && event.target !== menuToggle) {
                    sidebar.classList.remove('show');
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>