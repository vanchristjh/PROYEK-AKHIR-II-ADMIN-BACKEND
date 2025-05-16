

<?php $__env->startSection('title', 'Pengaturan Akun'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">
                <i class="fas fa-cog me-2"></i>Pengaturan Akun
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('guru.dashboard')); ?>" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('guru.dashboard')); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
      <!-- Alerts -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
            <i class="fas fa-check-circle me-1"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> Terjadi kesalahan:
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
      <!-- Main content area -->
    <div class="row">
        <!-- Left sidebar navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow animate__animated animate__fadeInLeft">
                <div class="card-body p-0">
                    <div class="bg-primary p-4 text-center">
                        <div class="avatar-wrapper mb-3">
                            <?php if($user->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" class="rounded-circle img-thumbnail border-white shadow" style="width: 100px; height: 100px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <h5 class="mb-1 text-white"><?php echo e($user->name); ?></h5>
                        <p class="text-white-50 small mb-2"><?php echo e($user->email); ?></p>
                        <span class="badge bg-light text-primary">Guru</span>
                    </div>
                    
                    <div class="list-group list-group-flush settings-nav mt-2">
                        <a href="#account" class="list-group-item list-group-item-action d-flex align-items-center active" data-bs-toggle="list">
                            <i class="fas fa-user-circle me-3 text-primary"></i>
                            <span>Informasi Akun</span>
                        </a>
                        <a href="#password" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="list">
                            <i class="fas fa-lock me-3 text-primary"></i>
                            <span>Ubah Password</span>
                        </a>
                        <a href="#preferences" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="list">
                            <i class="fas fa-bell me-3 text-primary"></i>
                            <span>Notifikasi & Preferensi</span>
                        </a>
                        <a href="#appearance" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="list">
                            <i class="fas fa-paint-brush me-3 text-primary"></i>
                            <span>Tampilan Aplikasi</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
          <!-- Settings content -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- Account Information Tab -->
                <div class="tab-pane fade show active" id="account">
                    <div class="card border-0 shadow animate__animated animate__fadeInUp mb-4">
                        <div class="card-header py-3 bg-white">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-user-circle text-primary me-2"></i>
                                Informasi Akun
                            </h5>
                            <p class="text-muted small mb-0">Kelola data dan profil akun Anda</p>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?php echo e(route('guru.profile.update')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                
                                <div class="row mb-4">
                                    <div class="col-lg-4">
                                        <div class="text-center">
                                            <div class="mb-3 position-relative d-inline-block">
                                                <div class="avatar-preview rounded-circle overflow-hidden mb-3 mx-auto border shadow" style="width: 150px; height: 150px;">
                                                    <?php if($user->avatar): ?>
                                                        <img id="avatar-preview-img" src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" class="img-fluid w-100 h-100 object-fit-cover">
                                                    <?php else: ?>
                                                        <div id="avatar-default" class="bg-primary text-white d-flex align-items-center justify-content-center w-100 h-100" style="font-size: 4rem;">
                                                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                                        </div>
                                                        <img id="avatar-preview-img" src="" alt="" class="img-fluid w-100 h-100 object-fit-cover d-none">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="position-relative">
                                                    <input type="file" id="avatar-upload" name="avatar" class="d-none" accept="image/*">
                                                    <label for="avatar-upload" class="btn btn-sm btn-outline-primary px-3 position-relative">
                                                        <i class="fas fa-camera me-2"></i> Ganti Foto
                                                    </label>
                                                    <?php if($user->avatar): ?>
                                                        <button type="button" id="remove-avatar" class="btn btn-sm btn-outline-danger ms-2">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <input type="hidden" name="remove_avatar" id="remove-avatar-input" value="0">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <p class="text-muted small">Unggah foto profil dengan ukuran maksimal 2MB<br>(format: JPG, PNG, atau GIF)</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-8">                                        <div class="mb-4">
                                            <label for="name" class="form-label fw-medium">Nama Lengkap</label>
                                            <div class="input-group input-group-seamless">
                                                <span class="input-group-text bg-light text-primary border-0 shadow-sm">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input type="text" class="form-control shadow-sm border-0" id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required placeholder="Masukkan nama lengkap">
                                            </div>
                                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="text-danger small mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    <?php echo e($message); ?>

                                                </div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                          <div class="mb-4">
                                            <label for="email" class="form-label fw-medium">Alamat Email</label>
                                            <div class="input-group input-group-seamless">
                                                <span class="input-group-text bg-light text-primary border-0 shadow-sm">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                                <input type="email" class="form-control shadow-sm border-0" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required placeholder="email@example.com">
                                            </div>
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="text-danger small mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    <?php echo e($message); ?>

                                                </div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                          <div class="mb-4">
                                            <label for="username" class="form-label fw-medium">Username</label>
                                            <div class="input-group input-group-seamless">
                                                <span class="input-group-text bg-light text-primary border-0 shadow-sm">
                                                    <i class="fas fa-at"></i>
                                                </span>
                                                <input type="text" class="form-control shadow-sm border-0" id="username" name="username" value="<?php echo e(old('username', $user->username)); ?>" required placeholder="Masukkan username">
                                            </div>
                                            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="text-danger small mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    <?php echo e($message); ?>

                                                </div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                                  <div class="text-end mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Change Password Tab -->                <div class="tab-pane fade" id="password">
                    <div class="card border-0 shadow animate__animated animate__fadeInUp mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-lock text-primary me-2"></i>
                                Ubah Password
                            </h5>
                            <p class="text-muted small mb-0">Ubah password login akun Anda</p>
                        </div>
                        <div class="card-body p-4">
                            <form method="post" action="<?php echo e(route('password.update')); ?>" class="mt-6 space-y-6">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('put'); ?>
                                  <div class="mb-4">
                                    <label for="current_password" class="form-label fw-medium">Password Saat Ini</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light text-primary border-0 shadow-sm">
                                            <i class="fas fa-key"></i>
                                        </span>
                                        <input type="password" class="form-control shadow-sm border-0 password-input" id="current_password" name="current_password" required placeholder="Masukkan password saat ini">
                                        <button class="btn btn-outline-secondary border-0 toggle-password shadow-sm" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                  <div class="mb-4">
                                    <label for="password" class="form-label fw-medium">Password Baru</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light text-primary border-0 shadow-sm">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control shadow-sm border-0 password-input" id="password" name="password" required placeholder="Masukkan password baru">
                                        <button class="btn btn-outline-secondary border-0 toggle-password shadow-sm" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="form-text small mt-2">
                                        <i class="fas fa-info-circle text-primary me-1"></i>
                                        Password harus minimal 8 karakter dan mengandung huruf dan angka.
                                    </div>
                                </div>
                                  <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password Baru</label>
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-text bg-light text-primary border-0 shadow-sm">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control shadow-sm border-0 password-input" id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi password baru">
                                        <button class="btn btn-outline-secondary border-0 toggle-password shadow-sm" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                  <div class="text-end mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="fas fa-key me-2"></i> Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Notification Preferences Tab -->                <div class="tab-pane fade" id="preferences">
                    <div class="card border-0 shadow animate__animated animate__fadeInUp mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-bell text-primary me-2"></i>
                                Notifikasi & Preferensi
                            </h5>
                            <p class="text-muted small mb-0">Sesuaikan preferensi notifikasi dan penggunaan</p>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?php echo e(route('guru.settings.update')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                  <div class="mb-4">
                                    <h6 class="fw-bold d-flex align-items-center">
                                        <i class="fas fa-envelope-open text-primary me-2"></i>
                                        Pengaturan Email
                                    </h6>
                                    <p class="text-muted small mb-3">Pilih kapan Anda ingin menerima notifikasi email</p>
                                    
                                    <div class="card border-0 shadow-sm p-3 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" 
                                                <?php echo e(isset($user->preferences['email_notifications']) && $user->preferences['email_notifications'] ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-medium" for="email_notifications">
                                                Notifikasi Email
                                                <small class="d-block text-muted mt-1">Terima semua notifikasi melalui email</small>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="card border-0 shadow-sm p-3 mb-3 ms-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="assignment_reminders" name="assignment_reminders"
                                                <?php echo e(isset($user->preferences['assignment_reminders']) && $user->preferences['assignment_reminders'] ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-medium" for="assignment_reminders">
                                                Pengingat Tugas
                                                <small class="d-block text-muted mt-1">Terima pengingat tentang tenggat tugas</small>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="card border-0 shadow-sm p-3 ms-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="new_submissions" name="new_submissions"
                                                <?php echo e(isset($user->preferences['new_submissions']) && $user->preferences['new_submissions'] ? 'checked' : ''); ?>>
                                            <label class="form-check-label fw-medium" for="new_submissions">
                                                Pengumpulan Baru
                                                <small class="d-block text-muted mt-1">Terima notifikasi saat siswa mengumpulkan tugas</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                  <div class="mb-4">
                                    <h6 class="fw-bold d-flex align-items-center">
                                        <i class="fas fa-sliders-h text-primary me-2"></i>
                                        Preferensi Lainnya
                                    </h6>
                                    <p class="text-muted small mb-3">Sesuaikan pengalaman penggunaan aplikasi Anda</p>
                                    
                                    <div class="card border-0 shadow-sm p-3 mb-3">
                                        <div class="mb-0">
                                            <label class="form-label fw-medium mb-2">Bahasa Aplikasi</label>
                                            <select class="form-select shadow-sm border-0" name="language" disabled>
                                                <option value="id" selected>Bahasa Indonesia</option>
                                                <option value="en">English</option>
                                            </select>
                                            <div class="mt-2">
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i> Coming Soon
                                                </span>
                                                <small class="text-muted ms-2">Fitur ini akan segera tersedia</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                  <div class="text-end mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="fas fa-save me-2"></i> Simpan Preferensi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Appearance Settings Tab -->                <div class="tab-pane fade" id="appearance">
                    <div class="card border-0 shadow animate__animated animate__fadeInUp mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-paint-brush text-primary me-2"></i>
                                Tampilan Aplikasi
                            </h5>
                            <p class="text-muted small mb-0">Sesuaikan tampilan dan tema aplikasi</p>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?php echo e(route('guru.settings.update')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                  <div class="mb-4">
                                    <h6 class="fw-bold d-flex align-items-center mb-3">
                                        <i class="fas fa-desktop text-primary me-2"></i>
                                        Tema Aplikasi
                                    </h6>
                                    
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="card border-0 shadow-sm p-3">
                                            <div class="form-check theme-check mb-0">
                                                <input class="form-check-input visually-hidden" type="radio" name="theme" id="theme-light" value="light" 
                                                    <?php echo e((!isset($user->preferences['theme']) || $user->preferences['theme'] == 'light') ? 'checked' : ''); ?>>
                                                <label class="form-check-label theme-option light" for="theme-light">
                                                    <div class="theme-preview">
                                                        <div class="theme-sidebar"></div>
                                                        <div class="theme-content"></div>
                                                    </div>
                                                    <div class="theme-title">Terang</div>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="card border-0 shadow-sm p-3">
                                            <div class="form-check theme-check mb-0">
                                                <input class="form-check-input visually-hidden" type="radio" name="theme" id="theme-dark" value="dark"
                                                    <?php echo e((isset($user->preferences['theme']) && $user->preferences['theme'] == 'dark') ? 'checked' : ''); ?> disabled>
                                                <label class="form-check-label theme-option dark" for="theme-dark">
                                                    <div class="theme-preview">
                                                        <div class="theme-sidebar"></div>
                                                        <div class="theme-content"></div>
                                                    </div>
                                                    <div class="theme-title">
                                                        Gelap 
                                                        <span class="badge bg-warning text-dark rounded-pill ms-1">
                                                            <i class="fas fa-clock me-1"></i> Coming Soon
                                                        </span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                  <div class="mb-4">
                                    <h6 class="fw-bold d-flex align-items-center mb-3">
                                        <i class="fas fa-palette text-primary me-2"></i>
                                        Warna Aksen
                                    </h6>
                                    
                                    <div class="card border-0 shadow-sm p-3 mb-2">
                                        <div class="d-flex flex-wrap gap-3 mb-2">
                                            <div class="form-check color-check">
                                                <input class="form-check-input visually-hidden" type="radio" name="accent_color" id="color-blue" value="blue" checked disabled>
                                                <label class="form-check-label color-option blue" for="color-blue">
                                                    <i class="fas fa-check color-check-icon"></i>
                                                </label>
                                            </div>
                                            
                                            <div class="form-check color-check">
                                                <input class="form-check-input visually-hidden" type="radio" name="accent_color" id="color-green" value="green" disabled>
                                                <label class="form-check-label color-option green" for="color-green">
                                                    <i class="fas fa-check color-check-icon"></i>
                                                </label>
                                            </div>
                                            
                                            <div class="form-check color-check">
                                                <input class="form-check-input visually-hidden" type="radio" name="accent_color" id="color-purple" value="purple" disabled>
                                                <label class="form-check-label color-option purple" for="color-purple">
                                                    <i class="fas fa-check color-check-icon"></i>
                                                </label>
                                            </div>
                                            
                                            <div class="form-check color-check">
                                                <input class="form-check-input visually-hidden" type="radio" name="accent_color" id="color-red" value="red" disabled>
                                                <label class="form-check-label color-option red" for="color-red">
                                                    <i class="fas fa-check color-check-icon"></i>
                                                </label>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i> Coming Soon
                                            </span>
                                            <small class="text-muted ms-2">Fitur pemilihan warna akan segera tersedia</small>
                                        </div>
                                    </div>
                                </div>
                                  <div class="text-end mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="fas fa-save me-2"></i> Simpan Tampilan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>    </div>
    
    <!-- Footer -->
    <footer class="text-center mt-4">
        <p class="text-muted">Copyright &copy; SMAN 1 Girsip <?php echo e(date('Y')); ?> | E-Learning System v1.0</p>
    </footer>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    /* General Settings Styles */
    .settings-nav .list-group-item {
        border: none;
        border-radius: 0;
        padding: 0.85rem 1.25rem;
        font-weight: 500;
        color: #495057;
        transition: all 0.25s ease;
    }
    
    .settings-nav .list-group-item:hover {
        background-color: #f8f9fa;
        color: var(--bs-primary);
    }
    
    .settings-nav .list-group-item.active {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
        border-left: 4px solid var(--bs-primary);
        font-weight: 600;
    }
    
    /* Theme Preview Styles */
    .theme-check {
        margin: 0;
    }
    
    .theme-option {
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        border: 2px solid transparent;
        transition: all 0.2s;
    }
    
    .theme-option:hover {
        border-color: rgba(67, 97, 238, 0.3);
    }
    
    input[name="theme"]:checked + .theme-option {
        border-color: #4361ee;
    }
    
    .theme-preview {
        width: 120px;
        height: 80px;
        border-radius: 4px;
        overflow: hidden;
        display: flex;
        margin-bottom: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .theme-sidebar {
        width: 30%;
        height: 100%;
    }
    
    .theme-content {
        width: 70%;
        height: 100%;
    }
    
    .theme-option.light .theme-sidebar {
        background-color: #313a46;
    }
    
    .theme-option.light .theme-content {
        background-color: #f8f9fa;
    }
    
    .theme-option.dark .theme-sidebar {
        background-color: #232323;
    }
    
    .theme-option.dark .theme-content {
        background-color: #2a2a2a;
    }
    
    .theme-title {
        font-size: 0.875rem;
        font-weight: 500;
        text-align: center;
        color: #495057;
    }
    
    /* Color Options Styles */
    .color-check {
        margin: 0;
    }
    
    .color-option {
        cursor: pointer;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid transparent;
        transition: all 0.2s;
    }
    
    .color-option.blue {
        background-color: #4361ee;
    }
    
    .color-option.green {
        background-color: #10b981;
    }
    
    .color-option.purple {
        background-color: #8b5cf6;
    }
    
    .color-option.red {
        background-color: #ef4444;
    }
    
    .color-check-icon {
        color: white;
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    input[name="accent_color"]:checked + .color-option {
        border-color: white;
        box-shadow: 0 0 0 2px currentColor;
    }
    
    input[name="accent_color"]:checked + .color-option .color-check-icon {
        opacity: 1;
    }
    
    /* Password Toggle */
    .toggle-password {
        cursor: pointer;
    }
    
    /* Animation */
    .tab-pane.fade {
        transition: opacity 0.3s ease;
    }
      /* Avatar Preview */
    .avatar-preview {
        position: relative;
        transition: all 0.3s;
    }
    
    .avatar-preview:hover {
        opacity: 0.9;
    }
    
    /* Custom Input Group Styling */
    .input-group-seamless {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        border-radius: 0.375rem;
        overflow: hidden;
    }
    
    .input-group-seamless .form-control:focus {
        box-shadow: none;
        border-color: transparent;
        background-color: #fff;
    }
    
    .input-group-seamless .form-control::placeholder {
        color: #adb5bd;
        font-size: 0.9rem;
    }
    
    .form-control:focus + .btn-outline-secondary {
        border-color: #86b7fe;
    }
    
    /* Animation effects */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        
        // Password visibility toggle with improved animation
        const togglePasswordBtns = document.querySelectorAll('.toggle-password');
        togglePasswordBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    
                    // Add visual feedback
                    this.setAttribute('data-bs-original-title', 'Sembunyikan Password');
                    if (bootstrap.Tooltip.getInstance(this)) {
                        bootstrap.Tooltip.getInstance(this).dispose();
                        new bootstrap.Tooltip(this).show();
                    }
                    
                    // Flash the input briefly to indicate the change
                    input.classList.add('bg-light');
                    setTimeout(() => input.classList.remove('bg-light'), 200);
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                    
                    // Update tooltip
                    this.setAttribute('data-bs-original-title', 'Tampilkan Password');
                    if (bootstrap.Tooltip.getInstance(this)) {
                        bootstrap.Tooltip.getInstance(this).dispose();
                        new bootstrap.Tooltip(this).show();
                    }
                }
            });
            
            // Initialize tooltips for password buttons
            new bootstrap.Tooltip(btn, {
                title: 'Tampilkan Password',
                placement: 'top',
                trigger: 'hover'
            });
        });
        
        // Avatar preview functionality
        const avatarUpload = document.getElementById('avatar-upload');
        const avatarPreviewImg = document.getElementById('avatar-preview-img');
        const avatarDefault = document.getElementById('avatar-default');
        
        if (avatarUpload && avatarPreviewImg) {
            avatarUpload.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (avatarDefault) avatarDefault.classList.add('d-none');
                        avatarPreviewImg.src = e.target.result;
                        avatarPreviewImg.classList.remove('d-none');
                    };
                    reader.readAsDataURL(this.files[0]);
                    
                    if (document.getElementById('remove-avatar-input')) {
                        document.getElementById('remove-avatar-input').value = "0";
                    }
                }
            });
        }
        
        // Remove avatar functionality
        const removeAvatarBtn = document.getElementById('remove-avatar');
        if (removeAvatarBtn) {
            removeAvatarBtn.addEventListener('click', function() {
                if (avatarDefault) avatarDefault.classList.remove('d-none');
                if (avatarPreviewImg) {
                    avatarPreviewImg.src = '';
                    avatarPreviewImg.classList.add('d-none');
                }
                if (avatarUpload) avatarUpload.value = '';
                document.getElementById('remove-avatar-input').value = "1";
            });
        }
        
        // Handle tab navigation from URL hash
        const hash = window.location.hash;
        if (hash) {
            const tab = document.querySelector(`a[href="${hash}"]`);
            if (tab) {
                bootstrap.Tab.getOrCreateInstance(tab).show();
            }
        }
        
        // Update URL when tab changes
        const tabEls = document.querySelectorAll('a[data-bs-toggle="list"]');
        tabEls.forEach(tabEl => {
            tabEl.addEventListener('shown.bs.tab', event => {
                history.replaceState(null, null, event.target.getAttribute('href'));
            });
        });
          // Auto-dismiss alerts
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 5000);
        });
        
        // Enhanced form animations and interactions
        const formInputs = document.querySelectorAll('.form-control, .form-select');
        formInputs.forEach(input => {
            // Add focus animation
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('shadow-lg');
                this.classList.add('bg-light');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('shadow-lg');
                this.classList.remove('bg-light');
            });
        });
        
        // Enhanced settings navigation
        const settingsNavItems = document.querySelectorAll('.settings-nav .list-group-item');
        settingsNavItems.forEach(item => {
            item.addEventListener('click', function() {
                // Add a subtle animation when clicking nav items
                const targetTab = document.querySelector(this.getAttribute('href'));
                if (targetTab) {
                    targetTab.classList.add('animate__animated', 'animate__fadeIn');
                    setTimeout(() => {
                        targetTab.classList.remove('animate__animated', 'animate__fadeIn');
                    }, 500);
                }
            });
        });
        
        // Add accessibility improvements
        document.querySelectorAll('[role="button"], button, a, input, .form-check-label').forEach(el => {
            if (!el.hasAttribute('tabindex')) {
                el.setAttribute('tabindex', '0');
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip\resources\views/guru/settings/index.blade.php ENDPATH**/ ?>