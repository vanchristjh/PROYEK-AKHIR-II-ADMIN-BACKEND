

<?php
use Illuminate\Support\Facades\Auth;
?>

<?php $__env->startSection('title', 'Jadwal Mengajar'); ?>

<?php $__env->startSection('header', 'Jadwal Mengajar'); ?>

<?php $__env->startSection('navigation'); ?>
    <?php echo $__env->make('guru.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header with improved visuals -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-8 mb-8 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-calendar-alt text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-blue-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-3">Jadwal Mengajar</h2>
            <p class="text-blue-100 text-lg">Manajemen jadwal mengajar untuk semua kelas</p>
            <div class="flex flex-wrap gap-4 mt-6">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-blue-500/30 text-blue-50 text-sm backdrop-blur-sm">
                    <i class="fas fa-user-clock mr-2"></i> Guru: <?php echo e(Auth::user()->name); ?>

                </span>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-blue-500/30 text-blue-50 text-sm backdrop-blur-sm">
                    <i class="fas fa-calendar mr-2"></i> <?php echo e(now()->format('d F Y')); ?>

                </span>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md animate-fade-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- New Schedule Alert -->
    <?php if(session('new_schedules_count')): ?>
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg shadow-md animate-fade-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-bell text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">
                        <?php echo e(session('new_schedules_count')); ?> jadwal baru telah ditambahkan oleh admin.
                        <a href="#weekly-schedule" class="text-blue-600 hover:text-blue-800 underline ml-1">Lihat sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(isset($message)): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-700"><?php echo e($message); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Current Day Highlight with improved design -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 border border-blue-100/50 hover:shadow-xl transition-all duration-300">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-blue-100/50 border-b border-blue-100">
            <div class="flex items-center">
                <div class="p-3 bg-blue-600 rounded-lg mr-4 shadow-md text-white">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Jadwal Hari Ini</h3>
            </div>
        </div>
        <div class="p-6">
            <?php
                $today = now()->dayOfWeek;
                $indonesianDays = [
                    0 => 'Minggu',
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu'
                ];
                $todayInIndonesian = $indonesianDays[$today];
                $currentTime = now()->format('H:i');
            ?>
            
            <div class="text-center mb-6 animate-pulse-light">
                <div class="inline-block rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 p-1 mb-3">
                    <div class="bg-white rounded-lg px-4 py-2">
                        <h4 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"><?php echo e($todayInIndonesian); ?>, <?php echo e(now()->format('d F Y')); ?></h4>
                    </div>
                </div>
                <p class="text-gray-500 flex items-center justify-center gap-2">
                    <i class="far fa-clock"></i> <?php echo e(now()->format('H:i')); ?> WIB
                </p>
            </div>

            <?php if(isset($schedulesByDay[$todayInIndonesian]) && count($schedulesByDay[$todayInIndonesian]) > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $schedulesByDay[$todayInIndonesian]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isOngoing = $currentTime >= $schedule->start_time && $currentTime <= $schedule->end_time;
                            $isUpcoming = $currentTime < $schedule->start_time;
                            $isPast = $currentTime > $schedule->end_time;
                            
                            $statusClass = $isOngoing ? 'bg-green-100 border-green-200' 
                                : ($isUpcoming ? 'bg-blue-50 border-blue-200' 
                                : 'bg-gray-50 border-gray-200');
                            
                            $statusIconClass = $isOngoing ? 'bg-green-500 text-white' 
                                : ($isUpcoming ? 'bg-blue-500 text-white' 
                                : 'bg-gray-400 text-white');
                        ?>
                        
                        <div class="flex items-center p-5 rounded-xl border-2 <?php echo e($statusClass); ?> transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 group">
                            <div class="flex-shrink-0 p-3 rounded-full <?php echo e($statusIconClass); ?> shadow-sm group-hover:shadow-md transition-all duration-300">
                                <i class="fas <?php echo e($isOngoing ? 'fa-play-circle' : ($isUpcoming ? 'fa-clock' : 'fa-check-circle')); ?> text-lg"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-lg font-semibold text-gray-900"><?php echo e($schedule->subject->name); ?></h4>
                                <div class="flex items-center text-sm text-gray-600 mt-1">
                                    <span><i class="fas fa-chalkboard mr-1"></i> <?php echo e($schedule->classroom->name); ?></span>
                                    <span class="mx-2">•</span>
                                    <span><i class="far fa-clock mr-1"></i> <?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?></span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex px-3 py-1.5 text-sm font-medium rounded-full <?php echo e($isOngoing ? 'bg-green-500 text-white' : 
                                    ($isUpcoming ? 'bg-blue-500 text-white' : 'bg-gray-400 text-white')); ?>">
                                    <?php echo e($isOngoing ? 'Sedang Berlangsung' : ($isUpcoming ? 'Akan Datang' : 'Selesai')); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="py-12 flex flex-col items-center justify-center text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <div class="mb-4 p-4 rounded-full bg-blue-100 text-blue-500">
                        <i class="fas fa-coffee text-3xl"></i>
                    </div>
                    <h5 class="text-xl font-medium text-gray-800 mb-2">Tidak Ada Jadwal Hari Ini</h5>
                    <p class="text-gray-500 max-w-md">Anda tidak memiliki jadwal mengajar untuk hari ini. Nikmati waktu Anda!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Full Weekly Schedule with card improvements -->
    <div id="weekly-schedule" class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
        <div class="p-6 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-blue-100/50">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-600 rounded-lg mr-4 shadow-md text-white">
                    <i class="fas fa-calendar-week text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Jadwal Mingguan</h3>
            </div>
        </div>
        
        <div class="overflow-x-auto p-6">
            <?php if(isset($schedulesByDay) && count($schedulesByDay) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $daySchedules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 rounded-lg text-blue-800 mb-4">
                                <i class="fas fa-calendar-day mr-2 text-blue-500"></i> 
                                <?php
                                    // Handle both string day names and numeric days
                                    if (is_numeric($day)) {
                                        $dayNames = [
                                            1 => 'Senin',
                                            2 => 'Selasa',
                                            3 => 'Rabu',
                                            4 => 'Kamis',
                                            5 => 'Jumat',
                                            6 => 'Sabtu',
                                            7 => 'Minggu'
                                        ];
                                        echo $dayNames[$day] ?? $day;
                                    } else {
                                        echo $day;
                                    }
                                ?>
                            </h3>
                            
                            <div class="space-y-4">
                                <?php $__currentLoopData = $daySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $isNewSchedule = false;
                                        if (isset($schedule->created_at) && !is_bool($schedule->created_at) && is_object($schedule->created_at)) {
                                            $isNewSchedule = $schedule->created_at->gt(now()->subDays(3));
                                        }
                                        $wasCreatedByAdmin = isset($schedule->created_by) && is_object($schedule->created_by) && $schedule->created_by->hasRole('admin');
                                        
                                        // Safe property access
                                        $subjectName = '';
                                        $classroomName = '';
                                        $startTime = '';
                                        $endTime = '';
                                        $room = '';
                                        
                                        if (is_object($schedule)) {
                                            if (isset($schedule->subject) && is_object($schedule->subject)) {
                                                $subjectName = $schedule->subject->name ?? 'N/A';
                                            }
                                            
                                            if (isset($schedule->classroom) && is_object($schedule->classroom)) {
                                                $classroomName = $schedule->classroom->name ?? 'N/A';
                                            }
                                            
                                            $startTime = $schedule->start_time ?? 'N/A';
                                            $endTime = $schedule->end_time ?? 'N/A';
                                            $room = $schedule->room ?? '';
                                        }
                                    ?>
                                    <div class="p-4 hover:bg-blue-50 transition-colors duration-300 rounded-lg border <?php echo e($isNewSchedule ? 'border-blue-300 animate-pulse-light' : 'border-transparent'); ?>">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-600 text-white shadow-sm">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="flex items-center">
                                                        <p class="text-sm font-semibold text-gray-900"><?php echo e($subjectName); ?></p>
                                                        <?php if($isNewSchedule): ?>
                                                            <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full">Baru</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <p class="text-xs text-gray-500"><?php echo e($classroomName); ?></p>
                                                </div>
                                            </div>
                                            <div class="text-sm font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                                                <?php echo e($startTime); ?> - <?php echo e($endTime); ?>

                                            </div>
                                        </div>
                                        <?php if($room): ?>
                                        <div class="mt-3 text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-map-marker-alt mr-1 text-red-400"></i>
                                            Ruangan: <?php echo e($room); ?>

                                        </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-16">
                    <div class="mx-auto w-20 h-20 mb-5 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                        <i class="fas fa-calendar-times text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Belum Ada Jadwal</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Anda belum memiliki jadwal mengajar. Silakan hubungi administrator untuk mendapatkan jadwal mengajar Anda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Summary Stats with better styling -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <?php
            $totalSchedules = 0;
            $totalClasses = [];
            $totalSubjects = [];
            
            foreach ($schedulesByDay ?? [] as $daySchedules) {
                $totalSchedules += count($daySchedules);
                
                foreach ($daySchedules as $schedule) {
                    $totalClasses[$schedule->classroom->id] = $schedule->classroom->name;
                    $totalSubjects[$schedule->subject->id] = $schedule->subject->name;
                }
            }
        ?>
        
        <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-md p-6 border border-blue-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-lg bg-blue-600 text-white shadow-md">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-blue-800 uppercase tracking-wider">Total Jadwal</h4>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($totalSchedules); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-md p-6 border border-green-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-lg bg-green-600 text-white shadow-md">
                    <i class="fas fa-chalkboard text-xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-green-800 uppercase tracking-wider">Kelas yang Diajar</h4>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(count($totalClasses)); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-md p-6 border border-purple-100 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-lg bg-purple-600 text-white shadow-md">
                    <i class="fas fa-book text-xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-purple-800 uppercase tracking-wider">Mata Pelajaran</h4>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(count($totalSubjects)); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options with improved design -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-all duration-300">
        <div class="p-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-file-export mr-3 p-2 bg-indigo-100 text-indigo-600 rounded-lg"></i> 
                Opsi Ekspor
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-5">Unduh atau cetak jadwal mengajar untuk referensi offline Anda.</p>
            <div class="flex flex-wrap gap-4">
                <a href="<?php echo e(route('guru.schedule.export.pdf')); ?>" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center shadow-sm hover:shadow-md">
                    <i class="fas fa-file-pdf mr-2"></i> Ekspor PDF
                </a>
                <a href="<?php echo e(route('guru.schedule.export.excel')); ?>" class="px-5 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors inline-flex items-center shadow-sm hover:shadow-md">
                    <i class="fas fa-file-excel mr-2"></i> Ekspor Excel
                </a>
                <button class="px-5 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors inline-flex items-center shadow-sm hover:shadow-md" onclick="window.print()">
                    <i class="fas fa-print mr-2"></i> Cetak
                </button>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-fade-in {
        animation: fade-in 0.7s ease-out;
    }
    
    .animate-pulse-light {
        animation: pulse-light 2s infinite;
    }
    
    @keyframes fade-in {
        0% {
            opacity: 0;
            transform: translateY(15px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse-light {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }

    @media print {
        header, .sidebar, footer, .no-print {
            display: none !important;
        }
        
        body {
            background-color: white;
        }
        
        main {
            margin: 0;
            padding: 0;
        }
        
        .shadow-sm, .shadow-md, .shadow-lg, .shadow-xl {
            box-shadow: none !important;
        }
        
        .rounded-xl, .rounded-lg {
            border-radius: 0 !important;
        }
        
        .container {
            max-width: 100% !important;
            padding: 1rem !important;
        }
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .grid-cols-3 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
        
        .grid-cols-2 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced function to highlight current class in schedule
        function updateCurrentSchedule() {
            const now = new Date();
            const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            
            document.querySelectorAll('.schedule-item').forEach(item => {
                const startTime = item.dataset.startTime;
                const endTime = item.dataset.endTime;
                
                if (currentTime >= startTime && currentTime <= endTime) {
                    item.classList.add('current-class');
                } else {
                    item.classList.remove('current-class');
                }
            });
            
            // Update current time display
            const timeDisplay = document.getElementById('current-time');
            if (timeDisplay) {
                timeDisplay.textContent = now.getHours().toString().padStart(2, '0') + ':' + 
                                         now.getMinutes().toString().padStart(2, '0') + ':' + 
                                         now.getSeconds().toString().padStart(2, '0');
            }
        }
        
        // Initial call and set interval to update every 30 seconds
        updateCurrentSchedule();
        setInterval(updateCurrentSchedule, 30000);
        
        // Apply fade-in effect to elements sequentially
        const elements = document.querySelectorAll('.animate-on-load');
        elements.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('animate-fade-in');
            }, 100 * index);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip\resources\views/guru/schedule/index.blade.php ENDPATH**/ ?>