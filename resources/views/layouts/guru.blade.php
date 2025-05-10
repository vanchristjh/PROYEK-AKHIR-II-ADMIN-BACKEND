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
    
    <!-- Additional CSS -->
    @stack('styles')
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" 
             class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-indigo-700 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-indigo-800">
                <div class="flex items-center">
                    <span class="text-xl font-bold text-white">SMAN 1 Girsip</span>
                </div>
            </div>
            
            <!-- User Info -->
            <div class="flex flex-col items-center mt-6 mb-6">
                <div class="relative">
                    @if(Auth::user()->avatar)
                        <img class="h-16 w-16 rounded-full object-cover border-2 border-white" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                    @else
                        <div class="h-16 w-16 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xl font-bold border-2 border-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <h2 class="mt-3 text-white font-medium">{{ Auth::user()->name }}</h2>
                <span class="mt-1 px-2 py-0.5 bg-indigo-800 rounded-full text-xs text-white">{{ Auth::user()->role->name ?? 'Guru' }}</span>
            </div>
            
            <!-- Navigation Links -->
            <nav class="mt-2 px-2 space-y-1">
                <a href="{{ route('guru.dashboard') }}" 
                   class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('guru.dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('guru.dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('guru.materials.index') }}" 
                   class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('guru.materials.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('guru.materials.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                    </svg>
                    Materi
                </a>

                <a href="{{ route('guru.assignments.index') }}" 
                   class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('guru.assignments.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('guru.assignments.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                    </svg>
                    Tugas
                </a>

                <a href="{{ route('guru.grades.index') }}" 
                   class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('guru.grades.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('guru.grades.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                    </svg>
                    Nilai
                </a>

                <a href="{{ route('guru.announcements.index') }}" 
                   class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('guru.announcements.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('guru.announcements.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z" clip-rule="evenodd" />
                    </svg>
                    Pengumuman
                </a>

                <a href="{{ route('guru.profile.show') }}" 
                   class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('guru.profile.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('guru.profile.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                    </svg>
                    Profil
                </a>
                
                <div class="border-t border-indigo-600 my-2"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full group flex items-center px-4 py-2.5 text-sm font-medium text-indigo-100 hover:bg-indigo-600 hover:text-white rounded-lg">
                        <svg class="mr-3 h-5 w-5 text-indigo-300 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none focus:ring lg:hidden">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        
                        <div class="ml-4">
                            <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div x-data="{ notificationsOpen: false }" class="relative mr-3">
                            <button @click="notificationsOpen = !notificationsOpen" class="relative p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center h-4 w-4 rounded-full bg-red-500 text-xs font-bold leading-none text-white">
                                    3
                                </span>
                            </button>

                            <div x-show="notificationsOpen" @click.away="notificationsOpen = false" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" x-cloak>
                                <div class="py-2">
                                    <div class="px-4 py-2 border-b border-gray-200">
                                        <h3 class="text-sm font-semibold text-gray-800">Notifikasi</h3>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        <a href="#" class="flex px-4 py-3 border-b hover:bg-gray-50">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">Ada tugas baru telah ditambahkan</div>
                                                <div class="text-sm text-gray-500">5 menit yang lalu</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="px-4 py-2 border-t border-gray-200">
                                        <a href="#" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">
                                            Lihat semua notifikasi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" class="relative block overflow-hidden rounded-full w-8 h-8 focus:outline-none">
                                @if(Auth::user()->avatar)
                                    <img class="h-full w-full object-cover" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-indigo-600 text-white font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </button>

                            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10" x-cloak>
                                <a href="{{ route('guru.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
