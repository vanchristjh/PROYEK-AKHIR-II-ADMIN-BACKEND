<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SMAN 1 Learning Management System</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
      <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Additional CSS -->
    @stack('styles')
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    @php
        use Illuminate\Support\Facades\Auth;
    @endphp

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" 
             class="bg-indigo-800 text-white w-64 px-2 py-4 fixed inset-y-0 left-0 transform transition-transform duration-300 lg:translate-x-0 lg:relative z-40 overflow-y-auto scrollbar-thin scrollbar-thumb-indigo-600 scrollbar-track-indigo-800">
            
            <!-- Logo & Menu Header -->
            <div class="flex items-center justify-between px-3">
                <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-8 w-auto">
                    <span class="text-xl font-bold">SMAN 1 GIRSIP</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden">
                    <i class="fas fa-times text-indigo-300 hover:text-white transition-colors"></i>
                </button>
            </div>
              <!-- Navigation Links -->
            <div class="mt-8">
                <nav class="mt-2 px-2 space-y-1">
                    @include('guru.partials.sidebar')
                </nav>
                  <div class="px-2 mt-4 pt-4 border-t border-indigo-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full group flex items-center px-4 py-2.5 text-sm font-medium text-indigo-100 hover:bg-indigo-600 hover:text-white rounded-lg">
                            <svg class="mr-3 h-5 w-5 text-indigo-300 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:pl-64">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 lg:hidden">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-700 ml-3">@yield('header', 'Dashboard Guru')</h2>
                    </div>
                    
                    <div class="flex items-center">
                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/'.Auth::user()->avatar) }}" class="w-8 h-8 rounded-full object-cover" alt="Profile">
                                    @else
                                        <span class="font-medium">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <span class="text-gray-700 hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">                                <a href="{{ route('guru.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2 text-gray-500"></i>
                                    Profil
                                </a>
                                <a href="{{ route('guru.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2 text-gray-500"></i>
                                    Pengaturan
                                </a>
                                <hr class="my-1 border-gray-200">
                                <form method="POST" action="{{ route('logout') }}" class="block w-full text-left">
                                    @csrf
                                    <button type="submit" class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2 text-gray-500"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6 lg:pl-0">
                @yield('content')
            </main>
              <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} SMAN 1 GIRSIP - Sistem Informasi Arsip Digital</p>
                    <div class="mt-2 md:mt-0 flex items-center space-x-4 text-sm text-gray-500">
                        <a href="{{ route('guru.help') }}" class="hover:text-indigo-600 transition-colors">Bantuan</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('guru.settings.privacy') }}" class="hover:text-indigo-600 transition-colors">Privasi</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('guru.profile.show') }}" class="hover:text-indigo-600 transition-colors">Profil</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Scripts -->
    @yield('scripts')
</body>
</html>
