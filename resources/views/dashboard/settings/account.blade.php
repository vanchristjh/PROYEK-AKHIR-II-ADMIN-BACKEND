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
                    <a href="{{ route('settings.account') }}" class="list-group-item list-group-item-action d-flex align-items-center active">
                        <i class="bx bx-user me-3"></i> Akun
                    </a>
                    <a href="{{ route('settings.notifications') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-bell me-3"></i> Notifikasi
                    </a>
                    <a href="{{ route('settings.appearance') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-palette me-3"></i> Tampilan
                    </a>
                    <a href="{{ route('settings.system') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-cog me-3"></i> Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        @if (session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Informasi Akun</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update-account') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="mb-3 text-center">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/'.Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="rounded-circle img-thumbnail mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 100px; height: 100px; font-size: 40px;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="mt-2">
                                    <label for="profile_photo" class="btn btn-sm btn-outline-secondary">
                                        <i class="bx bx-camera"></i> Ganti
                                    </label>
                                    <input type="file" class="d-none" id="profile_photo" name="profile_photo" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="role" class="form-label">Peran</label>
                                    <input type="text" class="form-control" id="role" value="{{ Auth::user()->role == 'admin' ? 'Administrator' : (Auth::user()->role == 'teacher' ? 'Guru' : 'Siswa') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="card-title mb-0">Ubah Password</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Kosongkan semua bidang password jika tidak ingin mengubah password.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Aktivitas Login</h5>
                <span class="badge bg-primary">7 hari terakhir</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>IP Address</th>
                                <th>Browser</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ now()->format('d M Y, H:i') }}</td>
                                <td>192.168.1.1</td>
                                <td>Chrome 114.0</td>
                                <td><span class="badge bg-success">Berhasil</span></td>
                            </tr>
                            <tr>
                                <td>{{ now()->subDays(1)->format('d M Y, H:i') }}</td>
                                <td>192.168.1.1</td>
                                <td>Chrome 114.0</td>
                                <td><span class="badge bg-success">Berhasil</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('profile_photo').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.avatar-placeholder, img.rounded-circle').remove();
                var img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('rounded-circle', 'img-thumbnail', 'mb-2');
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                document.querySelector('.mb-3.text-center').prepend(img);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection
