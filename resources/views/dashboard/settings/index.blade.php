@extends('layouts.dashboard')

@section('page-title', 'Pengaturan')

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
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Pengaturan Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 border">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                        <i class="bx bx-user fs-3 text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Pengaturan Akun</h5>
                                        <small class="text-muted">Ubah informasi akun dan password</small>
                                    </div>
                                </div>
                                <p class="text-muted mb-4">Perbarui data pribadi, email, password, dan pengaturan akun lainnya.</p>
                                <a href="{{ route('settings.account') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bx bx-edit me-1"></i> Kelola Akun
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                        <i class="bx bx-bell fs-3 text-info"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Notifikasi</h5>
                                        <small class="text-muted">Kelola pemberitahuan sistem</small>
                                    </div>
                                </div>
                                <p class="text-muted mb-4">Atur preferensi notifikasi untuk email, aplikasi, dan pemberitahuan lainnya.</p>
                                <a href="{{ route('settings.notifications') }}" class="btn btn-outline-info btn-sm">
                                    <i class="bx bx-edit me-1"></i> Atur Notifikasi
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                        <i class="bx bx-palette fs-3 text-success"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Tampilan</h5>
                                        <small class="text-muted">Sesuaikan tampilan aplikasi</small>
                                    </div>
                                </div>
                                <p class="text-muted mb-4">Ubah tema, warna, dan preferensi tampilan sesuai keinginan Anda.</p>
                                <a href="{{ route('settings.appearance') }}" class="btn btn-outline-success btn-sm">
                                    <i class="bx bx-edit me-1"></i> Atur Tampilan
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                        <i class="bx bx-cog fs-3 text-warning"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Sistem</h5>
                                        <small class="text-muted">Konfigurasi pengaturan sistem</small>
                                    </div>
                                </div>
                                <p class="text-muted mb-4">Konfigurasi nama sekolah, tahun ajaran, dan pengaturan sistem lainnya.</p>
                                <a href="{{ route('settings.system') }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bx bx-edit me-1"></i> Pengaturan Sistem
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
