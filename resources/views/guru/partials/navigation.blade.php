<li>
    <a href="{{ route('guru.dashboard') }}" class="sidebar-item {{ $active === 'dashboard' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-tachometer-alt text-lg w-6 {{ $active === 'dashboard' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('guru.materials.index') }}" class="sidebar-item {{ $active === 'materials' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-book text-lg w-6 {{ $active === 'materials' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Materi Pelajaran</span>
    </a>
</li>
<li>
    <a href="{{ route('guru.assignments.index') }}" class="sidebar-item {{ $active === 'assignments' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-tasks text-lg w-6 {{ $active === 'assignments' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Tugas</span>
    </a>
</li>
<li>
    <a href="{{ route('guru.grades.index') }}" class="sidebar-item {{ $active === 'grades' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-star text-lg w-6 {{ $active === 'grades' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Penilaian</span>
    </a>
</li>
<li>
    <a href="{{ route('guru.attendance.index') }}" class="sidebar-item {{ $active === 'attendance' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-clipboard-check text-lg w-6 {{ $active === 'attendance' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Kehadiran</span>
    </a>
</li>
<li>
    <a href="{{ route('guru.announcements.index') }}" class="sidebar-item {{ $active === 'announcements' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-bullhorn text-lg w-6 {{ $active === 'announcements' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Pengumuman</span>
    </a>
</li>
