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
            <a href="{{ url('/siswa/materials') }}" onclick="navigateTo('/siswa/materials')" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->is('siswa/materials*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->is('siswa/materials*') ? 'bg-purple-800' : 'bg-indigo-700/50 group-hover:bg-purple-700/50' }} transition-all duration-200">
                    <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center {{ request()->is('siswa/materials*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Materi Pelajaran</span>
                @if(request()->is('siswa/materials*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('siswa.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('siswa.assignments.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.assignments.*') ? 'bg-yellow-700' : 'bg-indigo-700/50 group-hover:bg-yellow-700/50' }} transition-all duration-200">
                    <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.assignments.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Tugas</span>
                @if(request()->routeIs('siswa.assignments.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-yellow-400 rounded-tr-md rounded-br-md"></span>
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
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.announcements.*') ? 'bg-red-700' : 'bg-indigo-700/50 group-hover:bg-red-700/50' }} transition-all duration-200">
                    <i class="fas fa-bullhorn text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.announcements.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Pengumuman</span>
                @if(request()->routeIs('siswa.announcements.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-red-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
        
        <li>
            <a href="{{ route('siswa.submissions.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 {{ request()->routeIs('siswa.submissions.*') ? 'sidebar-active' : '' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.submissions.*') ? 'bg-green-700' : 'bg-indigo-700/50 group-hover:bg-green-700/50' }} transition-all duration-200">
                    <i class="fas fa-file-upload text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.submissions.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Pengumpulan Tugas</span>
                @if(request()->routeIs('siswa.submissions.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-green-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
    </ul>
</div>

<!-- Akun -->
<div class="sidebar-section mb-2">
    <div class="sidebar-section-header px-4 py-2 text-xs font-semibold text-indigo-200 uppercase tracking-wider flex items-center">
        <span class="inline-block w-2 h-2 rounded-full bg-purple-400 mr-2"></span>
        Akun
    </div>
    <ul class="sidebar-items space-y-1 px-3">
        <li>
            <a href="{{ route('siswa.profile.show') }}" class="sidebar-item {{ request()->routeIs('siswa.profile.*') ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-2.5 group">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.profile.*') ? 'bg-purple-800' : 'bg-indigo-700/50 group-hover:bg-purple-700/50' }} transition-all duration-200">
                    <i class="fas fa-user-circle text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.profile.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Profil Saya</span>
                @if(request()->routeIs('siswa.profile.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('siswa.settings.index') }}" class="sidebar-item {{ request()->routeIs('siswa.settings.*') ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-2.5 group">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('siswa.settings.*') ? 'bg-purple-800' : 'bg-indigo-700/50 group-hover:bg-purple-700/50' }} transition-all duration-200">
                    <i class="fas fa-cog text-lg w-5 h-5 flex items-center justify-center {{ request()->routeIs('siswa.settings.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}"></i>
                </div>
                <span class="ml-3">Pengaturan Akun</span>
                @if(request()->routeIs('siswa.settings.*'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-400 rounded-tr-md rounded-br-md"></span>
                @endif
            </a>
        </li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click event handlers to all sidebar links
    document.querySelectorAll('.sidebar-item').forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Get the URL directly
            const href = this.getAttribute('href');
            if (href && href !== 'javascript:void(0)' && href !== '#') {
                e.preventDefault(); // Stop default event
                console.log('Navigating to:', href); // Debug info
                window.location.href = href; // Force navigation
            }
        });
    });
});

function navigateTo(path) {
    console.log('Direct navigation to:', path);
    window.location.href = path;
    return false;
}
</script>