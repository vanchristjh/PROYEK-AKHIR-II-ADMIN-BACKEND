<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SMA Admin') }} - @yield('page-title', 'Dashboard')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f8fa;
        }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #0066b3 0%, #004a80 100%);
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
            border-left: 3px solid white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.2rem;
            transition: transform 0.2s ease;
        }
        
        .sidebar .nav-link:hover i {
            transform: translateY(-2px);
        }
        
        .sidebar-heading {
            display: block;
            font-size: 0.75rem;
            letter-spacing: 1px;
            font-weight: 600;
        }
        
        .nav-section {
            padding: 0 1rem;
        }
        
        .avatar {
            width: 36px;
            height: 36px;
        }
        
        .main-content {
            flex: 1;
            overflow-y: auto;
            max-height: 100vh;
        }
        
        .card {
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .page-title {
            font-weight: 600;
            color: #333;
        }
        
        .breadcrumb-item.active {
            color: #0066b3;
        }
        
        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }
        
        .animate__tada {
            animation: tada 1s ease-in-out;
        }
        
        .sidebar-logo {
            padding: 20px 15px;
            display: flex;
            align-items: center;
        }
        
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        @yield('styles')
    </style>
</head>
<body>
    @yield('content')
    
    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
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
            
            // Initialize date pickers
            if (typeof flatpickr !== 'undefined') {
                flatpickr(".datepicker", {
                    locale: "id",
                    dateFormat: "Y-m-d",
                });
                
                flatpickr(".datetimepicker", {
                    locale: "id",
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                });
            }
        });
    </script>
    
    <!-- Custom scripts -->
    @yield('scripts')
</body>
</html>