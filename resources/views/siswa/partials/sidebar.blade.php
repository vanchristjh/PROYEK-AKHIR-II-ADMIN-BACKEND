<!-- Dashboard -->
<div class="sidebar-section mb-2">
    <div class="sidebar-section-header px-4 py-2 text-xs font-semibold text-indigo-200 uppercase tracking-wider flex items-center">
        <span class="inline-block w-2 h-2 rounded-full bg-indigo-400 mr-2"></span>
        Dashboard
    </div>
    <ul class="sidebar-items space-y-1 px-3">
        <li>
            <a href="{{ route('siswa.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('siswa.dashboard') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.dashboard') ? 'bg-indigo-800' : 'bg-indigo-700/50 group-hover:bg-indigo-700' }} transition-all duration-200">
                    <i class="fas fa-tachometer-alt text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Dashboard</span>
                @if(request()->routeIs('siswa.dashboard'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-indigo-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
    </ul>
</div>

<!-- Pembelajaran -->
<div class="sidebar-section mb-2">
    <div class="sidebar-section-header px-4 py-2 text-xs font-semibold text-indigo-200 uppercase tracking-wider flex items-center">
        <span class="inline-block w-2 h-2 rounded-full bg-blue-400 mr-2"></span>
        Pembelajaran
    </div>
    <ul class="sidebar-items space-y-1 px-3">
        <li>
            <a href="{{ route('siswa.schedule.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('siswa.schedule.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.schedule.*') ? 'bg-blue-800' : 'bg-indigo-700/50 group-hover:bg-blue-700/50' }} transition-all duration-200">
                    <i class="fas fa-calendar-alt text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.schedule.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Jadwal Pelajaran</span>
                @if(request()->routeIs('siswa.schedule.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('siswa.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('siswa.materials.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.materials.*') ? 'bg-purple-800' : 'bg-indigo-700/50 group-hover:bg-purple-700/50' }} transition-all duration-200">
                    <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.materials.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Materi Pelajaran</span>
                @if(request()->routeIs('siswa.materials.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('siswa.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('siswa.assignments.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.assignments.*') ? 'bg-amber-800' : 'bg-indigo-700/50 group-hover:bg-amber-700/50' }} transition-all duration-200">
                    <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.assignments.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Tugas</span>
                @if(request()->routeIs('siswa.assignments.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-amber-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
    </ul>
</div>

<!-- Pencapaian -->
<div class="sidebar-section mb-2">
    <div class="sidebar-section-header px-4 py-2 text-xs font-semibold text-indigo-200 uppercase tracking-wider flex items-center">
        <span class="inline-block w-2 h-2 rounded-full bg-green-400 mr-2"></span>
        Pencapaian
    </div>
    <ul class="sidebar-items space-y-1 px-3">
        <li>
            <a href="{{ route('siswa.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('siswa.grades.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.grades.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-green-700/50' }} transition-all duration-200">
                    <i class="fas fa-star text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.grades.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Nilai</span>
                @if(request()->routeIs('siswa.grades.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-green-400 rounded-tr-md rounded-br-md"></span>
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
            <a href="{{ route('siswa.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('siswa.announcements.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.announcements.*') ? 'bg-red-800' : 'bg-indigo-700/50 group-hover:bg-red-700/50' }} transition-all duration-200">
                    <i class="fas fa-bullhorn text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.announcements.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Pengumuman</span>
                @if(request()->routeIs('siswa.announcements.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-red-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
    </ul>
</div>