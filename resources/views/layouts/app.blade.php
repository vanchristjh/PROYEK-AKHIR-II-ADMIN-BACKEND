<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'SMAN 1 Girsip') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in-out',
                        'slide-in': 'slideIn 0.4s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 6s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(0)' }
                        },
                        float: {
                            '0%': { transform: 'translateY(0px)' },
                            '100%': { transform: 'translateY(-10px)' }
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom styles -->
    <style>
        /* Base styles */
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background: #f8fafc;
        }
        
        /* Scrollbars */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 20px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        
        /* Navigation effects */
        .nav-item {
            position: relative;
            transition: all 0.3s ease;
            border-radius: 8px;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
        }
        
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 500;
        }
        
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 70%;
            width: 3px;
            background: linear-gradient(to bottom, #38bdf8, #0284c7);
            border-radius: 2px;
        }
        
        /* Dropdowns */
        .dropdown-content {
            visibility: hidden;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.2s ease-out;
        }
        
        .dropdown-content.show {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Card effects */
        .card {
            @apply bg-white rounded-xl shadow-sm transition-all duration-300;
        }
        
        .card:hover {
            @apply shadow-md transform -translate-y-1;
        }
        
        /* Gradient backgrounds */
        .gradient-primary {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
        }
        
        .gradient-secondary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }
        
        /* Particle effects */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            pointer-events: none;
        }
        
        /* Dark mode adjustments */
        @media (prefers-color-scheme: dark) {
            .dark-mode-text {
                color: #f1f5f9;
            }
            
            .dark-mode-bg {
                background-color: #1e293b;
            }
            
            .dark-card {
                background-color: #0f172a;
                color: #f1f5f9;
            }
        }
        
        /* Animations */
        .hover-scale {
            transition: transform 0.2s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        .floating {
            animation: float 6s ease-in-out infinite alternate;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-10px); }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-secondary-50 antialiased">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Enhanced Sidebar with animated gradient background -->
        <aside class="w-full md:w-72 bg-gradient-to-b from-indigo-800 via-blue-800 to-indigo-900 text-white md:flex flex-col hidden relative overflow-hidden">
            <!-- Animated sidebar particle effect -->
            <div id="sidebar-particles" class="absolute inset-0 pointer-events-none opacity-30"></div>
            
            <!-- Enhanced Logo area with glass effect -->
            <div class="px-6 py-8 flex items-center justify-start border-b border-white/10 relative z-10">
                <div class="bg-white/15 p-2 rounded-lg shadow-inner backdrop-blur-sm hover:bg-white/25 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                    <img src="{{ asset('assets/images/logo.jpg') }}" alt="SMAN 1 Girsip" class="h-12 w-auto rounded shadow">
                </div>
                <div class="ml-4 border-l-4 border-indigo-400 pl-3">
                    <h1 class="text-xl font-bold tracking-tight text-shadow">SMAN 1 Girsip</h1>
                    <p class="text-xs font-light text-indigo-200">Sistem Informasi Akademik</p>
                </div>
            </div>
            
            <!-- Navigation with improved spacing and animations -->
            <nav class="flex-1 overflow-y-auto p-6 space-y-4 relative z-10">
                @yield('navigation')
            </nav>
            
            <!-- User profile with enhanced styling -->
            <div class="mt-auto border-t border-white/10 bg-black/10 p-4 backdrop-blur-sm">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-blue-600 flex items-center justify-center text-white shadow-md">
                        <span class="text-sm font-medium">{{ substr(auth()->user()->name ?? 'Guest', 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-sm text-white truncate max-w-[150px]">{{ auth()->user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-white/60">
                            {{ auth()->user()->role ? auth()->user()->role->name : 'User' }}
                        </p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-white/10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center text-sm text-white/70 hover:text-white transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main content area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top navigation -->
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="px-4 py-3 flex items-center justify-between">
                    <!-- Mobile menu button and breadcrumb -->
                    <div class="flex items-center space-x-4">
                        <button id="mobile-menu-button" class="md:hidden text-secondary-600 hover:text-secondary-900 transition-colors p-1.5 rounded-md hover:bg-gray-100">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        
                        <div class="flex items-center">
                            <h1 class="font-semibold text-lg text-secondary-800">
                                @yield('header', 'Dashboard')
                            </h1>
                            
                            <!-- Breadcrumb navigation -->
                            @if(isset($breadcrumbs))
                            <div class="hidden sm:flex items-center ml-4 text-sm">
                                <span class="text-gray-400 mx-2">/</span>
                                <nav class="flex" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                        @foreach($breadcrumbs as $breadcrumb)
                                            <li class="inline-flex items-center">
                                                @if(!$loop->first)
                                                    <span class="text-gray-400 mx-2">/</span>
                                                @endif
                                                
                                                @if($breadcrumb['url'] && !$loop->last)
                                                    <a href="{{ $breadcrumb['url'] }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">
                                                        {{ $breadcrumb['label'] }}
                                                    </a>
                                                @else
                                                    <span class="text-sm font-medium text-gray-700">{{ $breadcrumb['label'] }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ol>
                                </nav>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Right side actions -->
                    <div class="flex items-center space-x-3">
                        <!-- Announcements dropdown -->
                        <div class="dropdown relative">
                            <button class="p-1.5 rounded-full hover:bg-secondary-100 text-secondary-600 transition-colors relative">
                                <i class="fas fa-bullhorn"></i>
                                @if(isset($unreadAnnouncementsCount) && $unreadAnnouncementsCount > 0)
                                    <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full">
                                        {{ $unreadAnnouncementsCount > 9 ? '9+' : $unreadAnnouncementsCount }}
                                    </span>
                                @endif
                            </button>
                            <div class="dropdown-content absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg py-2 z-50">
                                <div class="px-4 py-2 border-b border-secondary-100 flex justify-between items-center">
                                    <h3 class="font-medium text-sm text-secondary-900">Pengumuman</h3>
                                    @if(isset($unreadAnnouncementsCount) && $unreadAnnouncementsCount > 0)
                                        <span class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded-full">{{ $unreadAnnouncementsCount }} baru</span>
                                    @endif
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @forelse($recentAnnouncements ?? [] as $announcement)
                                        <a href="{{ auth()->user()->role->slug === 'admin' ? route('admin.announcements.show', $announcement) : (auth()->user()->role->slug === 'guru' ? route('guru.announcements.show', $announcement) : route('siswa.announcements.show', $announcement)) }}" 
                                           class="block px-4 py-3 hover:bg-secondary-50 border-b border-secondary-100 {{ $announcement->is_important ? 'bg-red-50' : '' }}">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full {{ $announcement->is_important ? 'bg-red-100 text-red-500' : 'bg-indigo-100 text-indigo-500' }} flex items-center justify-center mr-3">
                                                    <i class="fas fa-{{ $announcement->is_important ? 'exclamation-circle' : 'bullhorn' }} text-sm"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm text-secondary-900 font-medium truncate">
                                                        @if($announcement->is_important)
                                                            <span class="text-red-600">[Penting]</span>
                                                        @endif
                                                        {{ $announcement->title }}
                                                    </p>
                                                    <p class="text-xs text-secondary-500 mt-0.5">{{ $announcement->publish_date->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-4 py-6 text-center">
                                            <div class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 mb-4">
                                                <i class="fas fa-bell-slash text-gray-400"></i>
                                            </div>
                                            <p class="text-sm text-gray-500">Tidak ada pengumuman baru</p>
                                        </div>
                                    @endforelse
                                </div>
                                <a href="{{ auth()->user()->role->slug === 'admin' ? route('admin.announcements.index') : (auth()->user()->role->slug === 'guru' ? route('guru.announcements.index') : route('siswa.announcements.index')) }}" 
                                   class="block text-center text-xs font-medium text-primary-600 hover:text-primary-800 py-2 border-t border-secondary-100">
                                    Lihat semua pengumuman
                                </a>
                            </div>
                        </div>
                        
                        <!-- Notifications dropdown -->
                        <div class="dropdown relative">
                            <button class="p-1.5 rounded-full hover:bg-secondary-100 text-secondary-600 transition-colors">
                                <i class="fas fa-bell"></i>
                            </button>
                            <div class="dropdown-content absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg py-2 z-50">
                                <div class="px-4 py-2 border-b border-secondary-100 flex justify-between items-center">
                                    <h3 class="font-medium text-sm text-secondary-900">Notifikasi</h3>
                                    <span class="text-xs px-2 py-1 bg-primary-100 text-primary-600 rounded-full">3 baru</span>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <!-- Sample notifications -->
                                    <a href="#" class="block px-4 py-3 hover:bg-secondary-50 border-b border-secondary-100">
                                        <p class="text-sm text-secondary-900 font-medium">Tugas baru ditambahkan</p>
                                        <p class="text-xs text-secondary-500 mt-0.5">5 menit yang lalu</p>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-secondary-50">
                                        <p class="text-sm text-secondary-900 font-medium">Nilai ujian telah diupdate</p>
                                        <p class="text-xs text-secondary-500 mt-0.5">1 jam yang lalu</p>
                                    </a>
                                </div>
                                <a href="#" class="block text-center text-xs font-medium text-primary-600 hover:text-primary-800 py-2">
                                    Lihat semua notifikasi
                                </a>
                            </div>
                        </div>
                        
                        <!-- User dropdown -->
                        <div class="dropdown relative">
                            <button class="flex items-center space-x-1 p-1 rounded hover:bg-secondary-100 transition-colors">
                                <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center text-white">
                                    <span class="text-sm font-medium">{{ substr(auth()->user()->name ?? 'G', 0, 1) }}</span>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-secondary-400"></i>
                            </button>
                            <div class="dropdown-content absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-50">
                                    <i class="fas fa-user text-secondary-500 mr-3 w-4"></i> Profil
                                </a>
                                
                                @if(auth()->user()->role->slug === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-50">
                                    <i class="fas fa-tachometer-alt text-secondary-500 mr-3 w-4"></i> Dashboard
                                </a>
                                @elseif(auth()->user()->role->slug === 'guru')
                                <a href="{{ route('guru.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-50">
                                    <i class="fas fa-tachometer-alt text-secondary-500 mr-3 w-4"></i> Dashboard
                                </a>
                                @elseif(auth()->user()->role->slug === 'siswa')
                                <a href="{{ route('siswa.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-50">
                                    <i class="fas fa-tachometer-alt text-secondary-500 mr-3 w-4"></i> Dashboard
                                </a>
                                @endif
                                
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-50">
                                    <i class="fas fa-cog text-secondary-500 mr-3 w-4"></i> Pengaturan
                                </a>
                                <div class="border-t border-secondary-200 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left">
                                        <i class="fas fa-sign-out-alt text-red-500 mr-3 w-4"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-y-auto p-6 animate-fade-in">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="border-t border-secondary-200 py-4 px-6 bg-white">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <p class="text-sm text-secondary-500">
                        &copy; {{ date('Y') }} SMAN 1 Girsip. All rights reserved.
                    </p>
                    <div class="mt-2 sm:mt-0">
                        <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">About</a>
                        <span class="text-secondary-300 mx-2">|</span>
                        <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">Support</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Mobile navigation sidebar (hidden by default) -->
    <div id="mobile-backdrop" class="fixed inset-0 bg-secondary-900/60 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300"></div>
    <div id="mobile-sidebar" class="fixed inset-y-0 left-0 gradient-primary w-72 max-w-[80%] z-50 transform -translate-x-full transition-transform duration-300 ease-in-out">
        <!-- Mobile sidebar content -->
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-4 border-b border-white/10">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="SMAN 1 Girsip" class="h-8">
                    <div class="ml-3">
                        <h1 class="font-bold text-white">SMAN 1 Girsip</h1>
                        <p class="text-xs text-white/60">Sistem Informasi Akademik</p>
                    </div>
                </div>
                <button id="close-mobile-menu" class="text-white hover:bg-white/10 p-2 rounded">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="flex-1 overflow-y-auto p-4 space-y-1.5">
                @yield('navigation')
            </nav>
            
            <div class="mt-auto border-t border-white/10 bg-black/10 p-4">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white shadow-inner">
                        <span class="text-sm font-medium">{{ substr(auth()->user()->name ?? 'Guest', 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-sm text-white truncate max-w-[150px]">{{ auth()->user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-white/60">
                            {{ auth()->user()->role ? auth()->user()->role->name : 'User' }}
                        </p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-white/10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center text-sm text-white/70 hover:text-white transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create sidebar particles
            function createParticles() {
                const container = document.getElementById('sidebar-particles');
                if (!container) return;
                
                for (let i = 0; i < 20; i++) {
                    const particle = document.createElement('div');
                    particle.classList.add('particle');
                    
                    // Random size
                    const size = Math.random() * 4 + 1;
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    
                    // Random position
                    particle.style.left = `${Math.random() * 100}%`;
                    particle.style.top = `${Math.random() * 100}%`;
                    
                    // Animation properties
                    particle.style.opacity = Math.random() * 0.5 + 0.1;
                    
                    // Add to container
                    container.appendChild(particle);
                    
                    // Animate particle
                    animateParticle(particle);
                }
            }
            
            function animateParticle(particle) {
                const duration = Math.random() * 30000 + 10000; // 10-40 seconds
                const xMove = Math.random() * 30 - 15;
                const yMove = Math.random() * 30 - 15;
                
                particle.animate([
                    { transform: 'translate(0, 0)' },
                    { transform: `translate(${xMove}px, ${yMove}px)` },
                    { transform: 'translate(0, 0)' }
                ], {
                    duration,
                    iterations: Infinity,
                    direction: 'alternate',
                    easing: 'ease-in-out'
                });
            }
            
            // Dropdown functionality
            document.querySelectorAll('.dropdown').forEach(dropdown => {
                const button = dropdown.querySelector('button');
                const content = dropdown.querySelector('.dropdown-content');
                
                if (button && content) {
                    button.addEventListener('click', (e) => {
                        e.stopPropagation();
                        content.classList.toggle('show');
                    });
                }
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', () => {
                document.querySelectorAll('.dropdown-content.show').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            });
            
            // Mobile menu functionality
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeMobileMenu = document.getElementById('close-mobile-menu');
            const mobileBackdrop = document.getElementById('mobile-backdrop');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            
            if (mobileMenuButton && mobileSidebar) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileBackdrop.classList.remove('hidden');
                    setTimeout(() => {
                        mobileBackdrop.classList.add('opacity-100');
                        mobileSidebar.classList.add('translate-x-0');
                        mobileSidebar.classList.remove('-translate-x-full');
                        document.body.style.overflow = 'hidden';
                    }, 50);
                });
            }
            
            function closeMobileNav() {
                mobileSidebar.classList.remove('translate-x-0');
                mobileSidebar.classList.add('-translate-x-full');
                mobileBackdrop.classList.remove('opacity-100');
                
                setTimeout(() => {
                    mobileBackdrop.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }
            
            if (closeMobileMenu) {
                closeMobileMenu.addEventListener('click', closeMobileNav);
            }
            
            if (mobileBackdrop) {
                mobileBackdrop.addEventListener('click', closeMobileNav);
            }
            
            // Initialize components
            createParticles();
        });
    </script>
    @stack('scripts')
</body>
</html>
