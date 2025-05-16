<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="SMAN 1 Girsip">
        <h2>SMAN 1 Girsip</h2>
        <p>E-Learning System</p>
    </div>

    <div class="sidebar-menu">
        <div class="menu-section">
            <h3><i class="fas fa-tachometer-alt sidebar-icon"></i> DASHBOARD</h3>
            <ul>
                <li>
                    @if(Route::has('admin.dashboard'))
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home sidebar-icon"></i> Dashboard</a>
                    @else
                        <a href="#" class="disabled"><i class="fas fa-home sidebar-icon"></i> Dashboard</a>
                    @endif
                </li>
            </ul>
        </div>

        <div class="menu-section">
            <h3><i class="fas fa-users sidebar-icon"></i> PENGGUNA</h3>
            <ul>
                <li>
                    @if(Route::has('admin.users'))
                        <a href="{{ route('admin.users') }}"><i class="fas fa-user sidebar-icon"></i> Pengguna</a>
                    @else
                        <a href="#" class="disabled"><i class="fas fa-user sidebar-icon"></i> Pengguna</a>
                    @endif
                </li>
            </ul>
        </div>

        <div class="menu-section">
            <h3><i class="fas fa-book sidebar-icon"></i> AKADEMIK</h3>
            <ul>
                <li>
                    @if(Route::has('admin.subjects'))
                        <a href="{{ route('admin.subjects') }}"><i class="fas fa-book-open sidebar-icon"></i> Mata Pelajaran</a>
                    @else
                        <a href="#" class="disabled"><i class="fas fa-book-open sidebar-icon"></i> Mata Pelajaran</a>
                    @endif
                </li>
                <li><a href="{{ route('admin.classes') }}"><i class="fas fa-chalkboard sidebar-icon"></i> Kelas</a></li>
                <li><a href="{{ route('admin.schedule') }}"><i class="fas fa-calendar-alt sidebar-icon"></i> Jadwal</a></li>
            </ul>
        </div>

        <div class="menu-section">
            <h3><i class="fas fa-comment sidebar-icon"></i> KOMUNIKASI</h3>
            <ul>
                <li><a href="{{ route('admin.announcements') }}"><i class="fas fa-bullhorn sidebar-icon"></i> Pengumuman</a></li>
            </ul>
        </div>

        <div class="menu-section">
            <h3><i class="fas fa-cog sidebar-icon"></i> SISTEM & AKUN</h3>
            <ul>
                <li><a href="{{ route('admin.settings') }}"><i class="fas fa-sliders-h sidebar-icon"></i> Pengaturan</a></li>
                <li><a href="{{ route('admin.profile') }}"><i class="fas fa-user-circle sidebar-icon"></i> Profil Saya</a></li>
            </ul>
        </div>
    </div>

    <div class="user-info">
        <div class="avatar">
            <span>A</span>
        </div>
        <div class="user-details">
            <h4>Admin User</h4>
            <p>Administrator</p>
        </div>
    </div>

    <div class="logout-button">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt sidebar-icon"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
