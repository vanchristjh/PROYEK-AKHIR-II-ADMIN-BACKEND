<!-- ... existing code ... -->

<!-- Akademik Section -->
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Akademik</span>
</li>

<!-- Existing menus like attendance, schedule, etc. -->

<!-- Fixed Nilai Akademik menu item with split button approach -->
<li class="menu-item {{ request()->routeIs('grades.*') || request()->routeIs('grade-categories.*') || request()->routeIs('grade-items.*') || request()->routeIs('academic-reports.*') ? 'active open' : '' }}">
    <div class="menu-link d-flex">
        <a href="{{ route('grades.index') }}" class="menu-link" style="flex: 1; padding-right: 0;">
            <i class="menu-icon tf-icons bx bx-book"></i>
            <div data-i18n="Nilai Akademik">Nilai Akademik</div>
        </a>
        <a class="menu-toggle-btn" href="javascript:void(0)" style="width: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="bx bx-chevron-down"></i>
        </a>
    </div>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('grades.*') ? 'active' : '' }}">
            <a href="{{ route('grades.index') }}" class="menu-link">
                <div data-i18n="Daftar Nilai">Daftar Nilai</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('grade-categories.*') ? 'active' : '' }}">
            <a href="{{ route('grade-categories.index') }}" class="menu-link">
                <div data-i18n="Kategori Nilai">Kategori Nilai</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('grade-items.*') ? 'active' : '' }}">
            <a href="{{ route('grade-items.index') }}" class="menu-link">
                <div data-i18n="Item Nilai">Item Nilai</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('academic-reports.*') ? 'active' : '' }}">
            <a href="{{ route('academic-reports.index') }}" class="menu-link">
                <div data-i18n="Laporan Nilai">Laporan Nilai</div>
            </a>
        </li>
    </ul>
</li>

<!-- ... existing code ... -->
