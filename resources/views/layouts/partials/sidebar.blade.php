@php
    $user = auth()->user();
    $role = $user->role ? $user->role->name : null;
@endphp

<!-- Admin Navigation -->
@if($role === 'admin')
    <!-- Dashboard Section -->
    <div class="mb-6">
        <div class="text-xs uppercase font-medium text-white/50 px-3 mb-2">Dashboard</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home w-5 text-center mr-3"></i>
            <span>Beranda</span>
        </a>
    </div>

    <!-- School Management Section -->
    <div class="mb-6">
        <div class="text-xs uppercase font-medium text-white/50 px-3 mb-2">Manajemen Sekolah</div>
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users w-5 text-center mr-3"></i>
            <span>Pengguna</span>
        </a>
        <a href="{{ route('admin.subjects.index') }}" class="nav-item {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
            <i class="fas fa-book w-5 text-center mr-3"></i>
            <span>Mata Pelajaran</span>
        </a>
        <a href="{{ route('admin.classrooms.index') }}" class="nav-item {{ request()->routeIs('admin.classrooms.*') ? 'active' : '' }}">
            <i class="fas fa-school w-5 text-center mr-3"></i>
            <span>Kelas</span>
        </a>
    </div>
<!-- Teacher Navigation -->
@elseif($role === 'guru')
    <!-- Dashboard Section -->
    <div class="mb-6">
        <div class="text-xs uppercase font-medium text-white/50 px-3 mb-2">Dashboard</div>
        <a href="{{ route('guru.dashboard') }}" class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home w-5 text-center mr-3"></i>
            <span>Beranda</span>
        </a>
    </div>

    <!-- Teaching Materials -->
    <div class="mb-6">
        <div class="text-xs uppercase font-medium text-white/50 px-3 mb-2">Pembelajaran</div>
        <a href="{{ route('guru.materials.index') }}" class="nav-item {{ request()->routeIs('guru.materials.*') ? 'active' : '' }}">
            <i class="fas fa-book-open w-5 text-center mr-3"></i>
            <span>Materi Pelajaran</span>
        </a>
        <a href="{{ route('guru.assignments.index') }}" class="nav-item {{ request()->routeIs('guru.assignments.*') ? 'active' : '' }}">
            <i class="fas fa-tasks w-5 text-center mr-3"></i>
            <span>Tugas</span>
        </a>
    </div>

    <!-- Student Monitoring -->
    <div class="mb-6">
        <div class="text-xs uppercase font-medium text-white/50 px-3 mb-2">Monitoring Siswa</div>
        <a href="{{ route('guru.grades.index') }}" class="nav-item {{ request()->routeIs('guru.grades.*') ? 'active' : '' }}">
            <i class="fas fa-star w-5 text-center mr-3"></i>
            <span>Nilai</span>
        </a>
        <a href="{{ route('guru.attendance.index') }}" class="nav-item {{ request()->routeIs('guru.attendance.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-check w-5 text-center mr-3"></i>
            <span>Absensi</span>
        </a>
    </div>
<!-- Student Navigation -->
@elseif($role === 'siswa')
    <!-- Dashboard Section -->
    <div class="mb-6">
        <div class="text-xs uppercase font-medium text-white/50 px-3 mb-2">Dashboard</div>
        <a href="{{ route('siswa.dashboard') }}" class="nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home w-5 text-center mr-3"></i>
            <span>Beranda</span>
        </a>
    </div>

    <!-- Learning Section -->
    <div class="mb-6">
        <div class="text-xs uppercase font-medium text-white/50 px-3 mb-2">Pembelajaran</div>
        <a href="{{ route('siswa.schedule.index') }}" class="nav-item {{ request()->routeIs('siswa.schedule.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt w-5 text-center mr-3"></i>
            <span>Jadwal</span>
        </a>
        <a href="{{ route('siswa.materials.index') }}" class="nav-item {{ request()->routeIs('siswa.materials.*') ? 'active' : '' }}">
            <i class="fas fa-book-reader w-5 text-center mr-3"></i>
            <span>Materi</span>
        </a>
        <a href="{{ route('siswa.assignments.index') }}" class="nav-item {{ request()->routeIs('siswa.assignments.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list w-5 text-center mr-3"></i>
            <span>Tugas</span>
        </a>
    </div>

    <!-- Results Section -->
    <div class="mb-6">
        <div class="text-xs uppercase font-medium text-white/50 px-3 mb-2">Hasil</div>
        <a href="{{ route('siswa.grades.index') }}" class="nav-item {{ request()->routeIs('siswa.grades.*') ? 'active' : '' }}">
            <i class="fas fa-chart-line w-5 text-center mr-3"></i>
            <span>Nilai</span>
        </a>
    </div>
@endif

<div class="mt-3 pt-3 border-t border-white/10">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex items-center text-sm text-white/70 hover:text-white transition-colors w-full text-left">
            <i class="fas fa-sign-out-alt mr-2"></i>
            <span>Logout</span>
        </button>
    </form>
</div>
