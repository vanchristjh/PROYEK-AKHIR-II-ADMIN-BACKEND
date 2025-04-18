@extends('layouts.dashboard')

@section('page-title', 'Pengaturan Notifikasi')

@section('dashboard-content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Menu Pengaturan</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('settings.account') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.account') ? 'active' : '' }}">
                        <i class="bx bx-user me-3"></i> Akun
                    </a>
                    <a href="{{ route('settings.notifications') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.notifications') ? 'active' : '' }}">
                        <i class="bx bx-bell me-3"></i> Notifikasi
                    </a>
                    <a href="{{ route('settings.appearance') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.appearance') ? 'active' : '' }}">
                        <i class="bx bx-palette me-3"></i> Tampilan
                    </a>
                    <a href="{{ route('settings.system') }}" class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.system') ? 'active' : '' }}">
                        <i class="bx bx-cog me-3"></i> Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        @if (session('success'))
            <div class="alert alert-success mb-4 d-flex align-items-center">
                <i class="bx bx-check-circle fs-4 me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger mb-4 d-flex align-items-center">
                <i class="bx bx-error-circle fs-4 me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Preferensi Notifikasi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update-notifications') }}" method="POST" id="notificationForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Email Notifications -->
                    <div class="card border mb-4">
                        <div class="card-header bg-light py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">Notifikasi Email</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enableEmailNotif" 
                                        {{ (isset($notificationSettings['email_login_activity']) && $notificationSettings['email_login_activity']) ||
                                           (isset($notificationSettings['email_announcements']) && $notificationSettings['email_announcements']) ||
                                           (isset($notificationSettings['email_events']) && $notificationSettings['email_events']) ||
                                           (isset($notificationSettings['email_system']) && $notificationSettings['email_system']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enableEmailNotif"></label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input email-notification" type="checkbox" id="emailLoginActivity" 
                                        name="notifications[email_login_activity]" 
                                        {{ isset($notificationSettings['email_login_activity']) && $notificationSettings['email_login_activity'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailLoginActivity">
                                        <span class="fw-medium">Aktivitas Login</span>
                                        <p class="text-muted small mb-0">Dapatkan notifikasi ketika ada login baru di akun Anda</p>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input email-notification" type="checkbox" id="emailAnnouncements" 
                                        name="notifications[email_announcements]" 
                                        {{ isset($notificationSettings['email_announcements']) && $notificationSettings['email_announcements'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailAnnouncements">
                                        <span class="fw-medium">Pengumuman</span>
                                        <p class="text-muted small mb-0">Menerima pemberitahuan saat ada pengumuman baru</p>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input email-notification" type="checkbox" id="emailEvents" 
                                        name="notifications[email_events]" 
                                        {{ isset($notificationSettings['email_events']) && $notificationSettings['email_events'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailEvents">
                                        <span class="fw-medium">Kegiatan & Acara</span>
                                        <p class="text-muted small mb-0">Pemberitahuan tentang kegiatan dan acara mendatang</p>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input email-notification" type="checkbox" id="emailSystem" 
                                        name="notifications[email_system]" 
                                        {{ isset($notificationSettings['email_system']) && $notificationSettings['email_system'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailSystem">
                                        <span class="fw-medium">Pembaruan Sistem</span>
                                        <p class="text-muted small mb-0">Informasi tentang pembaruan dan pemeliharaan sistem</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Browser Notifications -->
                    <div class="card border mb-4">
                        <div class="card-header bg-light py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">Notifikasi Browser</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enableBrowserNotif"
                                        {{ (isset($notificationSettings['browser_messages']) && $notificationSettings['browser_messages']) ||
                                           (isset($notificationSettings['browser_announcements']) && $notificationSettings['browser_announcements']) ||
                                           (isset($notificationSettings['browser_events']) && $notificationSettings['browser_events']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enableBrowserNotif"></label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input browser-notification" type="checkbox" id="browserMessages" 
                                        name="notifications[browser_messages]" 
                                        {{ isset($notificationSettings['browser_messages']) && $notificationSettings['browser_messages'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="browserMessages">
                                        <span class="fw-medium">Pesan</span>
                                        <p class="text-muted small mb-0">Notifikasi saat ada pesan baru</p>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input browser-notification" type="checkbox" id="browserAnnouncements" 
                                        name="notifications[browser_announcements]" 
                                        {{ isset($notificationSettings['browser_announcements']) && $notificationSettings['browser_announcements'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="browserAnnouncements">
                                        <span class="fw-medium">Pengumuman</span>
                                        <p class="text-muted small mb-0">Notifikasi saat ada pengumuman baru</p>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input browser-notification" type="checkbox" id="browserEvents" 
                                        name="notifications[browser_events]" 
                                        {{ isset($notificationSettings['browser_events']) && $notificationSettings['browser_events'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="browserEvents">
                                        <span class="fw-medium">Kegiatan & Acara</span>
                                        <p class="text-muted small mb-0">Notifikasi untuk acara yang akan datang</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-secondary" id="requestPermissionBtn">
                                    <i class="bx bx-bell me-1"></i> Izinkan Notifikasi Browser
                                </button>
                                <div class="ms-3">
                                    <span class="badge bg-light text-dark" id="permissionStatus">Belum diizinkan</span>
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">Browser Anda harus mengizinkan notifikasi agar fitur ini berfungsi</small>
                        </div>
                    </div>
                    
                    <!-- System Notifications -->
                    <div class="card border mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="card-title mb-0">Notifikasi Sistem</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="attendanceReminder" 
                                        name="notifications[attendance_reminder]" 
                                        {{ isset($notificationSettings['attendance_reminder']) && $notificationSettings['attendance_reminder'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="attendanceReminder">
                                        <span class="fw-medium">Pengingat Absensi</span>
                                        <p class="text-muted small mb-0">Pengingat harian untuk melakukan absensi</p>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="taskReminder" 
                                        name="notifications[task_reminder]" 
                                        {{ isset($notificationSettings['task_reminder']) && $notificationSettings['task_reminder'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="taskReminder">
                                        <span class="fw-medium">Pengingat Tugas</span>
                                        <p class="text-muted small mb-0">Pengingat untuk tenggat waktu tugas</p>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="reminderTiming" class="form-label">Waktu Pengingat</label>
                                <select class="form-select" id="reminderTiming" name="notifications[reminder_timing]">
                                    <option value="immediate" {{ isset($notificationSettings['reminder_timing']) && $notificationSettings['reminder_timing'] == 'immediate' ? 'selected' : '' }}>Segera</option>
                                    <option value="15min" {{ isset($notificationSettings['reminder_timing']) && $notificationSettings['reminder_timing'] == '15min' ? 'selected' : '' }}>15 menit sebelumnya</option>
                                    <option value="30min" {{ isset($notificationSettings['reminder_timing']) && $notificationSettings['reminder_timing'] == '30min' ? 'selected' : '' }}>30 menit sebelumnya</option>
                                    <option value="1hour" {{ isset($notificationSettings['reminder_timing']) && $notificationSettings['reminder_timing'] == '1hour' ? 'selected' : '' }}>1 jam sebelumnya</option>
                                    <option value="1day" {{ isset($notificationSettings['reminder_timing']) && $notificationSettings['reminder_timing'] == '1day' ? 'selected' : '' }}>1 hari sebelumnya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary" id="saveButton">
                            <i class="bx bx-save me-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission with loading indicator
        const notificationForm = document.getElementById('notificationForm');
        const saveButton = document.getElementById('saveButton');
        
        if (notificationForm) {
            notificationForm.addEventListener('submit', function() {
                saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                saveButton.disabled = true;
            });
        }
        
        // Email notifications toggle
        const emailToggle = document.getElementById('enableEmailNotif');
        const emailNotifications = document.querySelectorAll('.email-notification');
        
        emailToggle.addEventListener('change', function() {
            emailNotifications.forEach(checkbox => {
                checkbox.disabled = !this.checked;
                if (!this.checked) {
                    checkbox.checked = false;
                }
            });
        });
        
        // Set initial state
        if (!emailToggle.checked) {
            emailNotifications.forEach(checkbox => {
                checkbox.disabled = true;
            });
        }
        
        // Browser notifications toggle
        const browserToggle = document.getElementById('enableBrowserNotif');
        const browserNotifications = document.querySelectorAll('.browser-notification');
        
        browserToggle.addEventListener('change', function() {
            browserNotifications.forEach(checkbox => {
                checkbox.disabled = !this.checked;
                if (!this.checked) {
                    checkbox.checked = false;
                }
            });
            
            // If enabling browser notifications, check permission
            if (this.checked && Notification.permission === 'default') {
                requestPermissionBtn.disabled = false;
            } else {
                requestPermissionBtn.disabled = Notification.permission !== 'default';
            }
        });
        
        // Set initial state
        if (!browserToggle.checked) {
            browserNotifications.forEach(checkbox => {
                checkbox.disabled = true;
            });
        }
        
        // Check browser notification permission
        const permissionStatus = document.getElementById('permissionStatus');
        const requestPermissionBtn = document.getElementById('requestPermissionBtn');
        
        if ('Notification' in window) {
            updatePermissionStatus(Notification.permission);
            
            requestPermissionBtn.addEventListener('click', function() {
                Notification.requestPermission().then(function(permission) {
                    updatePermissionStatus(permission);
                    
                    if (permission === 'granted') {
                        // Show a test notification
                        const notification = new Notification('Notifikasi SMA Admin', {
                            body: 'Notifikasi browser telah diaktifkan!',
                            icon: '/images/logo.jpg'
                        });
                    }
                });
            });
        } else {
            permissionStatus.textContent = 'Tidak Didukung';
            permissionStatus.className = 'badge bg-secondary';
            requestPermissionBtn.disabled = true;
            browserToggle.disabled = true;
        }
        
        function updatePermissionStatus(permission) {
            switch (permission) {
                case 'granted':
                    permissionStatus.textContent = 'Diizinkan';
                    permissionStatus.className = 'badge bg-success';
                    requestPermissionBtn.disabled = true;
                    break;
                case 'denied':
                    permissionStatus.textContent = 'Ditolak';
                    permissionStatus.className = 'badge bg-danger';
                    break;
                default:
                    permissionStatus.textContent = 'Belum Diizinkan';
                    permissionStatus.className = 'badge bg-warning text-dark';
                    requestPermissionBtn.disabled = false;
            }
        }
    });
</script>
@endsection
