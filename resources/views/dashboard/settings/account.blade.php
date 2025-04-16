@extends('layouts.dashboard')

@section('page-title', 'Pengaturan Akun')

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
                <h5 class="card-title mb-0">Profil Akun</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update-account') }}" method="POST" enctype="multipart/form-data" id="accountForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <div class="avatar-upload">
                                <div class="avatar-preview rounded-circle border" style="width: 120px; height: 120px; overflow: hidden;">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="Profile" class="img-fluid" id="imagePreview">
                                    @else
                                        <div style="width: 100%; height: 100%; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                            <i class="bx bx-user" style="font-size: 3rem; color: #adb5bd;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2 text-center">
                                    <label for="profile_photo" class="btn btn-sm btn-outline-secondary">
                                        <i class="bx bx-upload me-1"></i> Ubah Foto
                                    </label>
                                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="d-none">
                                    @error('profile_photo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="role" class="form-label">Peran</label>
                                    <input type="text" class="form-control" id="role" value="{{ ucfirst($user->role) }}" disabled readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="user_since" class="form-label">Anggota Sejak</label>
                                    <input type="text" class="form-control" id="user_since" value="{{ $user->created_at->format('d F Y') }}" disabled readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="mb-3">Ubah Password</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                    <i class="bx bx-hide"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                    <i class="bx bx-hide"></i>
                                </button>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Minimal 8 karakter</div>
                        </div>
                        <div class="col-md-4">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary" id="saveButton">
                            <i class="bx bx-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Riwayat Login</h5>
            </div>
            <div class="card-body">
                @if(count($loginActivities) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>IP Address</th>
                                    <th>Perangkat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loginActivities as $activity)
                                <tr>
                                    <td>{{ $activity->login_at }}</td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td>{{ $activity->device }}</td>
                                    <td>
                                        @if($activity->status === 'success')
                                            <span class="badge bg-success">Berhasil</span>
                                        @else
                                            <span class="badge bg-danger">Gagal</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-4 text-center">
                        <div class="mb-3">
                            <i class="bx bx-time text-secondary" style="font-size: 3rem;"></i>
                        </div>
                        <p class="mb-0 text-muted">Belum ada riwayat login tersimpan</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card shadow-sm border-danger">
            <div class="card-header bg-white text-danger py-3">
                <h5 class="card-title mb-0">Zona Berbahaya</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div>
                            <i class="bx bx-error-circle fs-4 me-2"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading mb-1">Perhatian!</h6>
                            <p class="mb-0">Tindakan berikut tidak dapat dibatalkan. Harap berhati-hati.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#logoutAllModal">
                        <i class="bx bx-log-out me-1"></i> Logout dari Semua Sesi
                    </button>
                </div>
                
                @if($user->role !== 'admin')
                <div>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bx bx-trash me-1"></i> Hapus Akun
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Logout All Sessions Modal -->
<div class="modal fade" id="logoutAllModal" tabindex="-1" aria-labelledby="logoutAllModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutAllModalLabel">Logout dari Semua Sesi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda akan keluar dari semua perangkat yang saat ini login menggunakan akun Anda. Anda perlu login kembali setelah melakukan tindakan ini.</p>
                <p class="mb-0">Apakah Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('logout') }}" method="POST" id="logoutAllForm">
                    @csrf
                    <input type="hidden" name="logout_all" value="1">
                    <button type="submit" class="btn btn-danger">
                        <i class="bx bx-log-out me-1"></i> Logout dari Semua Sesi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Hapus Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bx bx-error-circle me-1"></i> Tindakan ini tidak dapat dibatalkan!
                </div>
                <p>Menghapus akun akan:</p>
                <ul>
                    <li>Menghapus permanen semua data terkait akun Anda</li>
                    <li>Menghilangkan akses Anda ke sistem</li>
                    <li>Mencabut semua hak akses dan izin Anda</li>
                </ul>
                <div class="mb-3">
                    <label for="confirm_delete" class="form-label">Ketik "HAPUS" untuk mengkonfirmasi</label>
                    <input type="text" class="form-control" id="confirm_delete" placeholder="HAPUS" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('settings.delete-account') }}" method="POST" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="deleteAccountBtn" disabled>
                        <i class="bx bx-trash me-1"></i> Hapus Akun Permanen
                    </button>
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
        const accountForm = document.getElementById('accountForm');
        const saveButton = document.getElementById('saveButton');
        
        if (accountForm) {
            accountForm.addEventListener('submit', function() {
                saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                saveButton.disabled = true;
            });
        }
        
        // Image preview
        const profilePhotoInput = document.getElementById('profile_photo');
        const imagePreview = document.getElementById('imagePreview');
        
        if (profilePhotoInput) {
            profilePhotoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const avatarPreview = document.querySelector('.avatar-preview');
                        
                        // Remove any existing content or icon
                        avatarPreview.innerHTML = '';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid';
                        img.id = 'imagePreview';
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';
                        
                        avatarPreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Password visibility toggle
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                }
            });
        });
        
        // Delete account confirmation
        const confirmDeleteInput = document.getElementById('confirm_delete');
        const deleteAccountBtn = document.getElementById('deleteAccountBtn');
        
        if (confirmDeleteInput && deleteAccountBtn) {
            confirmDeleteInput.addEventListener('input', function() {
                deleteAccountBtn.disabled = this.value !== 'HAPUS';
            });
        }
    });
</script>
@endsection
