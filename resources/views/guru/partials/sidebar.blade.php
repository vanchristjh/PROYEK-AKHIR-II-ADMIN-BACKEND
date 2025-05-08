<!-- Dashboard -->
<div class="sidebar-section mb-2">
    <div class="sidebar-section-header px-4 py-2 text-xs font-semibold text-indigo-200 uppercase tracking-wider flex items-center">
        <span class="inline-block w-2 h-2 rounded-full bg-indigo-400 mr-2"></span>
        Dashboard
    </div>
    <ul class="sidebar-items space-y-1 px-3">
        <li>
            <a href="{{ route('guru.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('guru.dashboard') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('guru.dashboard') ? 'bg-indigo-800' : 'bg-indigo-700/50 group-hover:bg-indigo-700' }} transition-all duration-200">
                    <i class="fas fa-tachometer-alt text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('guru.dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Dashboard</span>
                @if(request()->routeIs('guru.dashboard'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-indigo-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
    </ul>
</div>

<!-- Pembelajaran -->
<div class="sidebar-section mb-2">
    <div class="sidebar-section-header px-4 py-2 text-xs font-semibold text-indigo-200 uppercase tracking-wider flex items-center">
        <span class="inline-block w-2 h-2 rounded-full bg-green-400 mr-2"></span>
        Pembelajaran
    </div>
    <ul class="sidebar-items space-y-1 px-3">
        <li>
            <a href="{{ route('guru.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('guru.materials.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('guru.materials.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-green-700/50' }} transition-all duration-200">
                    <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('guru.materials.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Materi Pelajaran</span>
                @if(request()->routeIs('guru.materials.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-green-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('guru.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('guru.assignments.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('guru.assignments.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-green-700/50' }} transition-all duration-200">
                    <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('guru.assignments.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Tugas</span>
                @if(request()->routeIs('guru.assignments.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-green-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('guru.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('guru.grades.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('guru.grades.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-orange-700/50' }} transition-all duration-200">
                    <i class="fas fa-star text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('guru.grades.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Penilaian</span>
                @if(request()->routeIs('guru.grades.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-orange-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('guru.attendance.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('guru.attendance.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('guru.attendance.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-purple-700/50' }} transition-all duration-200">
                    <i class="fas fa-clipboard-check text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('guru.attendance.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Kehadiran</span>
                @if(request()->routeIs('guru.attendance.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('guru.schedule.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('guru.schedule.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('guru.schedule.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-blue-700/50' }} transition-all duration-200">
                    <i class="fas fa-calendar-alt text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('guru.schedule.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Jadwal Mengajar</span>
                @if(request()->routeIs('guru.schedule.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
    </ul>
</div>

<!-- Informasi -->
<div class="sidebar-section mb-2">
    <div class="sidebar-section-header px-4 py-2 text-xs font-semibold text-indigo-200 uppercase tracking-wider flex items-center">
        <span class="inline-block w-2 h-2 rounded-full bg-red-400 mr-2"></span>
        Informasi
    </div>
    <ul class="sidebar-items space-y-1 px-3">
        <li>
            <a href="{{ route('guru.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('guru.announcements.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('guru.announcements.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-red-700/50' }} transition-all duration-200">
                    <i class="fas fa-bullhorn text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('guru.announcements.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Pengumuman</span>
                @if(request()->routeIs('guru.announcements.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-red-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
    </ul>
</div>