<li>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ $active === 'dashboard' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-tachometer-alt text-lg w-6 {{ $active === 'dashboard' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('admin.users.index') }}" class="sidebar-item {{ $active === 'users' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-users text-lg w-6 {{ $active === 'users' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Pengguna</span>
    </a>
</li>
<li>
    <a href="{{ route('admin.subjects.index') }}" class="sidebar-item {{ $active === 'subjects' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-book text-lg w-6 {{ $active === 'subjects' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Mata Pelajaran</span>
    </a>
</li>
<li>
    <a href="{{ route('admin.classrooms.index') }}" class="sidebar-item {{ $active === 'classrooms' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-school text-lg w-6 {{ $active === 'classrooms' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Kelas</span>
    </a>
</li>
<li>
    <a href="{{ route('admin.announcements.index') }}" class="sidebar-item {{ $active === 'announcements' ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200' }} flex items-center rounded-lg px-4 py-3">
        <i class="fas fa-bullhorn text-lg w-6 {{ $active === 'announcements' ? '' : 'text-indigo-300' }}"></i>
        <span class="ml-3">Pengumuman</span>
    </a>
</li>
