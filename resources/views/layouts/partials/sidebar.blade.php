<!-- Teacher Attendance Menu Item -->
<li class="sidebar-item">
    <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#teacher-attendance-menu" aria-expanded="false">
        <i class="bx bx-id-card"></i>
        <span>Absensi Guru</span>
    </a>
    <ul id="teacher-attendance-menu" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar-menu">
        <li class="sidebar-item">
            <a href="{{ route('teacher-attendance.index') }}" class="sidebar-link">
                <i class="bx bx-list-ul"></i>
                <span>Daftar Absensi</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('teacher-attendance.create') }}" class="sidebar-link">
                <i class="bx bx-plus"></i>
                <span>Absensi Baru</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('teacher-attendance.report') }}" class="sidebar-link">
                <i class="bx bx-line-chart"></i>
                <span>Laporan Absensi</span>
            </a>
        </li>
    </ul>
</li>