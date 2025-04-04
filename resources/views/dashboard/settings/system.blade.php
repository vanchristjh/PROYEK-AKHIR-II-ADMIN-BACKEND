@extends('layouts.dashboard')

@section('page-title', 'Pengaturan Sistem')

@section('dashboard-content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Menu Pengaturan</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('settings.account') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-user me-3"></i> Akun
                    </a>
                    <a href="{{ route('settings.notifications') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-bell me-3"></i> Notifikasi
                    </a>
                    <a href="{{ route('settings.appearance') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-palette me-3"></i> Tampilan
                    </a>
                    <a href="{{ route('settings.system') }}" class="list-group-item list-group-item-action d-flex align-items-center active">
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
                <h5 class="card-title mb-0">Pengaturan Umum</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update-system') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="app_name" class="form-label">Nama Aplikasi</label>
                            <input type="text" class="form-control @error('app_name') is-invalid @enderror" id="app_name" name="app_name" value="{{ old('app_name', config('app.name', 'SMA Admin Dashboard')) }}" required>
                            @error('app_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="school_name" class="form-label">Nama Sekolah</label>
                            <input type="text" class="form-control @error('school_name') is-invalid @enderror" id="school_name" name="school_name" value="{{ old('school_name', 'SMA Negeri 1 Girsang Sipangan Bolon') }}" required>
                            @error('school_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="school_address" class="form-label">Alamat Sekolah</label>
                            <textarea class="form-control @error('school_address') is-invalid @enderror" id="school_address" name="school_address" rows="2">{{ old('school_address', 'Jalan Pendidikan No. 1, Girsang Sipangan Bolon') }}</textarea>
                            @error('school_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="academic_year" class="form-label">Tahun Ajaran</label>
                            <input type="text" class="form-control @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year" value="{{ old('academic_year', '2023/2024') }}" required>
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="timezone" class="form-label">Zona Waktu</label>
                            <select class="form-select @error('timezone') is-invalid @enderror" id="timezone" name="timezone">
                                <option value="Asia/Jakarta" {{ old('timezone', config('app.timezone')) == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                <option value="Asia/Makassar" {{ old('timezone', config('app.timezone')) == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                <option value="Asia/Jayapura" {{ old('timezone', config('app.timezone')) == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                            </select>
                            @error('timezone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode') ? 'checked' : '' }}>
                        <label class="form-check-label" for="maintenance_mode">Mode Maintenance</label>
                        <div class="form-text">Aktifkan mode maintenance untuk menonaktifkan akses ke sistem kecuali administrator</div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Backup & Restore</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border">
                            <div class="card-body">
                                <h6 class="mb-3">Backup Database</h6>
                                <p class="text-muted mb-4">Buat cadangan database untuk menyimpan seluruh data aplikasi.</p>
                                <button type="button" class="btn btn-primary btn-sm">
                                    <i class="bx bx-download me-1"></i> Buat Backup
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border">
                            <div class="card-body">
                                <h6 class="mb-3">Restore Database</h6>
                                <p class="text-muted mb-4">Pulihkan data aplikasi dari file cadangan yang tersedia.</p>
                                <button type="button" class="btn btn-secondary btn-sm">
                                    <i class="bx bx-upload me-1"></i> Restore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div>
                            <i class="bx bx-info-circle fs-4 me-2"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading mb-1">Perhatian!</h6>
                            <p class="mb-0">Backup database secara rutin untuk mencegah kehilangan data. Restore database akan menimpa semua data yang ada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
