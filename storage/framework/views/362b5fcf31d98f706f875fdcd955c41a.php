<?php
use Illuminate\Support\Facades\Request;
?>

<!-- Dashboard -->
<div class="sidebar-section mb-2">
    <div class="sidebar-section-header px-4 py-2 text-xs font-semibold text-indigo-200 uppercase tracking-wider flex items-center">
        <span class="inline-block w-2 h-2 rounded-full bg-indigo-400 mr-2"></span>
        Dashboard
    </div>
    <ul class="sidebar-items space-y-1 px-3">
        <li>
            <a href="<?php echo e(route('guru.dashboard')); ?>" class="sidebar-item <?php echo e(Request::routeIs('guru.dashboard') ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white'); ?> flex items-center rounded-lg px-4 py-3 transition-all duration-200">
                <i class="fas fa-tachometer-alt text-lg w-6 <?php echo e(!Request::routeIs('guru.dashboard') ? 'text-indigo-300' : ''); ?>"></i>
                <span class="ml-3">Dashboard</span>
            </a>
        </li>
    </ul>
</div>

<!-- Pembelajaran -->
<div class="sidebar-section mb-2">
    <div class="sidebar-section-header px-4 py-2 text-xs font-semibold text-indigo-200 uppercase tracking-wider flex items-center">
        <span class="inline-block w-2 h-2 rounded-full bg-green-400 mr-2"></span>
        Pembelajaran
    </div>
    <ul class="sidebar-items space-y-1 px-3">
        <li>
            <a href="<?php echo e(route('guru.materials.index')); ?>" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 <?php echo e(request()->routeIs('guru.materials.*') ? 'sidebar-active' : ''); ?>">
                <div class="p-1.5 rounded-lg <?php echo e(request()->routeIs('guru.materials.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-green-700/50'); ?> transition-all duration-200">
                    <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center <?php echo e(request()->routeIs('guru.materials.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white'); ?>"></i>
                </div>
                <span class="ml-3">Materi Pelajaran</span>
                <?php if(request()->routeIs('guru.materials.*')): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-green-400 rounded-tr-md rounded-br-md"></span>
                <?php endif; ?>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('guru.assignments.index')); ?>" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 <?php echo e(request()->routeIs('guru.assignments.*') ? 'sidebar-active' : ''); ?>">
                <div class="p-1.5 rounded-lg <?php echo e(request()->routeIs('guru.assignments.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-green-700/70'); ?> transition-all duration-200">
                    <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center <?php echo e(request()->routeIs('guru.assignments.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white'); ?>"></i>
                </div>
                <span class="ml-3">Tugas</span>
                <?php if(request()->routeIs('guru.assignments.*')): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-green-400 rounded-tr-md rounded-br-md"></span>
                <?php endif; ?>
            </a>
        </li>
            <a href="<?php echo e(route('guru.grades.index')); ?>" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 <?php echo e(request()->routeIs('guru.grades.*') ? 'sidebar-active' : ''); ?>">
                <div class="p-1.5 rounded-lg <?php echo e(request()->routeIs('guru.grades.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-orange-700/50'); ?> transition-all duration-200">
                    <i class="fas fa-star text-lg w-5 h-5 flex items-center justify-center <?php echo e(request()->routeIs('guru.grades.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white'); ?>"></i>
                </div>
                <span class="ml-3">Penilaian</span>
                <?php if(request()->routeIs('guru.grades.*')): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-orange-400 rounded-tr-md rounded-br-md"></span>
                <?php endif; ?>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('guru.attendance.index')); ?>" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 <?php echo e(request()->routeIs('guru.attendance.*') ? 'sidebar-active' : ''); ?>">
                <div class="p-1.5 rounded-lg <?php echo e(request()->routeIs('guru.attendance.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-purple-700/50'); ?> transition-all duration-200">
                    <i class="fas fa-clipboard-check text-lg w-5 h-5 flex items-center justify-center <?php echo e(request()->routeIs('guru.attendance.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white'); ?>"></i>
                </div>
                <span class="ml-3">Kehadiran</span>
                <?php if(request()->routeIs('guru.attendance.*')): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-400 rounded-tr-md rounded-br-md"></span>
                <?php endif; ?>
            </a>
        </li>
    </ul>
</div>

<!-- Jadwal -->
<div class="sidebar-section mb-2">
    <div class="sidebar-section-header px-4 py-2 text-xs font-semibold text-indigo-200 uppercase tracking-wider flex items-center">
        <span class="inline-block w-2 h-2 rounded-full bg-blue-400 mr-2"></span>
        Jadwal
    </div>
    <ul class="sidebar-items space-y-1 px-3">
        <li>
            <a href="<?php echo e(route('guru.schedule.index')); ?>" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200 <?php echo e(request()->routeIs('guru.schedule.*') ? 'sidebar-active' : ''); ?>">
                <div class="p-1.5 rounded-lg <?php echo e(request()->routeIs('guru.schedule.*') ? 'bg-green-800' : 'bg-indigo-700/50 group-hover:bg-blue-700/50'); ?> transition-all duration-200">
                    <i class="fas fa-calendar-alt text-lg w-5 h-5 flex items-center justify-center <?php echo e(request()->routeIs('guru.schedule.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white'); ?>"></i>
                </div>
                <span class="ml-3">Jadwal Mengajar</span>
                <?php if(request()->routeIs('guru.schedule.*')): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-blue-400 rounded-tr-md rounded-br-md"></span>
                <?php endif; ?>
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
            <a href="<?php echo e(route('guru.announcements.index')); ?>" class="sidebar-item <?php echo e(Request::routeIs('guru.announcements.*') ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white'); ?> flex items-center rounded-lg px-4 py-3 transition-all duration-200">
                <i class="fas fa-bullhorn text-lg w-6 <?php echo e(!Request::routeIs('guru.announcements.*') ? 'text-indigo-300' : ''); ?>"></i>
                <span class="ml-3">Pengumuman</span>
                <?php if(isset($unreadImportantAnnouncements) && $unreadImportantAnnouncements > 0): ?>
                    <span class="ml-3 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"><?php echo e($unreadImportantAnnouncements); ?></span>
                <?php endif; ?>
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
            <a href="<?php echo e(route('guru.profile.show')); ?>" class="sidebar-item <?php echo e(request()->routeIs('guru.profile.*') ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200'); ?> flex items-center rounded-lg px-4 py-2.5 group">
                <div class="p-1.5 rounded-lg <?php echo e(request()->routeIs('guru.profile.*') ? 'bg-purple-800' : 'bg-indigo-700/50 group-hover:bg-purple-700/50'); ?> transition-all duration-200">
                    <i class="fas fa-user-circle text-lg w-5 h-5 flex items-center justify-center <?php echo e(request()->routeIs('guru.profile.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white'); ?>"></i>
                </div>
                <span class="ml-3">Profil Saya</span>
                <?php if(request()->routeIs('guru.profile.*')): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-400 rounded-tr-md rounded-br-md"></span>
                <?php endif; ?>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('guru.settings.index')); ?>" class="sidebar-item <?php echo e(request()->routeIs('guru.settings.*') ? 'sidebar-active text-white' : 'text-indigo-100 hover:text-white transition-all duration-200'); ?> flex items-center rounded-lg px-4 py-2.5 group">
                <div class="p-1.5 rounded-lg <?php echo e(request()->routeIs('guru.settings.*') ? 'bg-purple-800' : 'bg-indigo-700/50 group-hover:bg-purple-700/50'); ?> transition-all duration-200">
                    <i class="fas fa-cog text-lg w-5 h-5 flex items-center justify-center <?php echo e(request()->routeIs('guru.settings.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white'); ?>"></i>
                </div>
                <span class="ml-3">Pengaturan Akun</span>
                <?php if(request()->routeIs('guru.settings.*')): ?>
                    <span class="absolute inset-y-0 left-0 w-1 bg-purple-400 rounded-tr-md rounded-br-md"></span>
                <?php endif; ?>
            </a>
        </li>
    </ul>
</div><?php /**PATH D:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip\resources\views/guru/partials/sidebar.blade.php ENDPATH**/ ?>