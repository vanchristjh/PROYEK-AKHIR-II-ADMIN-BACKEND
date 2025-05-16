<li>
    <a href="{{ route('siswa.dashboard') }}" class="sidebar-item {{ $active == 'dashboard' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white' }} flex items-center rounded-lg px-4 py-3 transition-all duration-200">
        <i class="fas fa-tachometer-alt text-lg w-6 {{ $active != 'dashboard' ? 'text-indigo-300' : '' }}"></i>
        <span class="ml-3">Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('siswa.materials.index') }}" class="sidebar-item {{ $active == 'materials' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white' }} flex items-center rounded-lg px-4 py-3 transition-all duration-200">
        <i class="fas fa-book text-lg w-6 {{ $active != 'materials' ? 'text-indigo-300' : '' }}"></i>
        <span class="ml-3">Materi Pelajaran</span>
    </a>
</li>
<li>
    <a href="{{ route('siswa.assignments.index') }}" class="sidebar-item {{ $active == 'assignments' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white' }} flex items-center rounded-lg px-4 py-3 transition-all duration-200">
        <i class="fas fa-tasks text-lg w-6 {{ $active != 'assignments' ? 'text-indigo-300' : '' }}"></i>
        <span class="ml-3">Tugas</span>
    </a>
</li>
<li>
    <a href="{{ route('siswa.grades.index') }}" class="sidebar-item {{ $active == 'grades' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white' }} flex items-center rounded-lg px-4 py-3 transition-all duration-200">
        <i class="fas fa-star text-lg w-6 {{ $active != 'grades' ? 'text-indigo-300' : '' }}"></i>
        <span class="ml-3">Nilai</span>
    </a>
</li>
@if(Route::has('siswa.attendance.index'))
<li>
    <a href="{{ route('siswa.attendance.index') }}" class="sidebar-item {{ $active == 'attendance' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white' }} flex items-center rounded-lg px-4 py-3 transition-all duration-200">
        <i class="fas fa-clipboard-check text-lg w-6 {{ $active != 'attendance' ? 'text-indigo-300' : '' }}"></i>
        <span class="ml-3">Kehadiran</span>
    </a>
</li>
@endif
<li>
    <a href="{{ route('siswa.announcements.index') }}" class="sidebar-item {{ $active == 'announcements' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white' }} flex items-center rounded-lg px-4 py-3 transition-all duration-200">
        <i class="fas fa-bullhorn text-lg w-6 {{ $active != 'announcements' ? 'text-indigo-300' : '' }}"></i>
        <span class="ml-3">Pengumuman</span>
        @if(isset($unreadImportantAnnouncements) && $unreadImportantAnnouncements > 0)
            <span class="ml-3 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $unreadImportantAnnouncements }}</span>
        @endif
    </a>
</li>
