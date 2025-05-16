

<?php $__env->startSection('title', 'Kelola Pengumuman'); ?>

<?php $__env->startSection('header', 'Kelola Pengumuman'); ?>

<?php $__env->startSection('navigation'); ?>
    <?php echo $__env->make('guru.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-bullhorn text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-red-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold mb-1">Pengumuman</h2>
                <p class="text-red-100">Kelola pengumuman untuk siswa dan guru</p>
            </div>
            <a href="<?php echo e(route('guru.announcements.create')); ?>" class="px-4 py-2 bg-white text-red-600 rounded-lg hover:bg-red-50 transition-colors shadow-md hover:shadow-lg flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Buat Pengumuman</span>
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md animate-fade-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-4 bg-gray-50 border-b">
            <form action="<?php echo e(route('guru.announcements.index')); ?>" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-grow min-w-[200px]">
                    <label for="importance" class="block text-sm font-medium text-gray-700 mb-1">Filter Prioritas</label>
                    <select name="importance" id="importance" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" onchange="this.form.submit()">
                        <option value="all" <?php echo e(request('importance') == 'all' || !request('importance') ? 'selected' : ''); ?>>Semua Pengumuman</option>
                        <option value="important" <?php echo e(request('importance') == 'important' ? 'selected' : ''); ?>>Pengumuman Penting</option>
                        <option value="regular" <?php echo e(request('importance') == 'regular' ? 'selected' : ''); ?>>Pengumuman Reguler</option>
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pengumuman</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" placeholder="Cari judul pengumuman..." class="rounded-lg pr-10 border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 w-full">
                        <button type="submit" class="absolute inset-y-0 right-0 px-3 flex items-center bg-red-500 text-white rounded-r-lg">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <?php if(request('importance') || request('search')): ?>
                    <a href="<?php echo e(route('guru.announcements.index')); ?>" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        <span>Reset</span>
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <?php if(isset($announcements) && $announcements->count() > 0): ?>
            <div class="divide-y divide-gray-100">
                <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-6 hover:bg-gray-50 transition-colors <?php echo e($announcement->is_important ? 'bg-red-50' : ''); ?>">
                        <div class="flex items-start">
                            <div class="bg-<?php echo e($announcement->is_important ? 'red' : 'gray'); ?>-100 p-3 rounded-lg text-<?php echo e($announcement->is_important ? 'red' : 'gray'); ?>-600 mr-4 flex-shrink-0">
                                <i class="fas <?php echo e($announcement->is_important ? 'fa-exclamation-circle' : 'fa-bell'); ?> text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                        <?php echo e($announcement->title); ?>

                                        <?php if($announcement->is_important): ?>
                                            <span class="ml-2 px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Penting</span>
                                        <?php endif; ?>
                                    </h3>
                                    <div class="text-sm text-gray-500">
                                        <?php echo e($announcement->publish_date->format('d M Y')); ?>

                                    </div>
                                </div>
                                <p class="text-gray-600 mt-2 text-sm line-clamp-2"><?php echo e($announcement->content); ?></p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user mr-1"></i>
                                        <?php echo e($announcement->author->name ?? 'Unknown'); ?>

                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-users mr-1"></i>
                                        <?php if($announcement->audience === 'all'): ?>
                                            Semua Pengguna
                                        <?php elseif($announcement->audience === 'students'): ?>
                                            Siswa
                                        <?php elseif($announcement->audience === 'teachers'): ?>
                                            Guru
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center gap-2">
                                    <a href="<?php echo e(route('guru.announcements.show', $announcement)); ?>" class="px-3 py-1 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 text-sm flex items-center gap-1 transition-colors">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <?php if($announcement->author_id == auth()->id()): ?>
                                        <a href="<?php echo e(route('guru.announcements.edit', $announcement)); ?>" class="px-3 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 text-sm flex items-center gap-1 transition-colors">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="<?php echo e(route('guru.announcements.destroy', $announcement)); ?>" method="POST" class="inline" onsubmit="event.preventDefault(); showDeleteModal('<?php echo e(route('guru.announcements.destroy', $announcement)); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-800 rounded hover:bg-red-200 text-sm flex items-center gap-1 transition-colors">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="px-6 py-3 bg-gray-50">
                <?php echo e($announcements->links()); ?>

            </div>
        <?php else: ?>
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-500 mb-4">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-800 mb-1">Tidak ada pengumuman ditemukan</h4>
                <p class="text-gray-500 mb-4">Belum ada pengumuman yang ditambahkan atau sesuai dengan filter Anda.</p>
                <a href="<?php echo e(route('guru.announcements.create')); ?>" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Buat Pengumuman Baru
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
        <div id="modalOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Hapus Pengumuman
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modal-description">
                                    Apakah Anda yakin ingin menghapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form id="deleteForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                        <button type="button" id="cancelDelete" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-fade-in {
        animation: fade-in 0.6s ease-in-out;
    }
    
    @keyframes fade-in {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function showDeleteModal(actionUrl) {
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const modalOverlay = document.getElementById('modalOverlay');
        const cancelDelete = document.getElementById('cancelDelete');

        deleteForm.action = actionUrl;
        deleteModal.classList.remove('hidden');

        modalOverlay.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        cancelDelete.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip\resources\views/guru/announcements/index.blade.php ENDPATH**/ ?>