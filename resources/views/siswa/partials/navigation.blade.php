<li>
    <a href="{{ route('siswa.dashboard') }}" class="sidebar-item {{ $active === 'dashboard' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-tachometer-alt text-lg w-6 {{ $active === 'dashboard' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('siswa.schedule.index') }}" class="sidebar-item {{ $active === 'schedule' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-calendar-alt text-lg w-6 {{ $active === 'schedule' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Jadwal Pelajaran</span>
    </a>
</li>
<li>
    <a href="{{ route('siswa.assignments.index') }}" class="sidebar-item {{ $active === 'assignments' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-tasks text-lg w-6 {{ $active === 'assignments' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Tugas</span>
    </a>
</li>
<li>
    <a href="{{ route('siswa.materials.index') }}" class="sidebar-item {{ $active === 'materials' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-book text-lg w-6 {{ $active === 'materials' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Materi Pelajaran</span>
    </a>
</li>
<li>
    <a href="{{ route('siswa.grades.index') }}" class="sidebar-item {{ $active === 'grades' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-star text-lg w-6 {{ $active === 'grades' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Nilai</span>
    </a>
</li>
<li>
    <a href="{{ route('siswa.announcements.index') }}" class="sidebar-item {{ $active === 'announcements' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-bullhorn text-lg w-6 {{ $active === 'announcements' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Pengumuman</span>
    </a>
</li>
