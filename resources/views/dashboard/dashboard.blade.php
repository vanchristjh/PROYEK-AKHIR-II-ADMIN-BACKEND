@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('page-actions')
<div class="d-flex align-items-center">
    <button type="button" class="btn btn-sm btn-primary d-flex align-items-center" id="datePicker">
        <i class="bx bx-calendar me-1"></i> <span id="currentDate">{{ date('d M Y') }}</span>
    </button>
</div>
@endsection

@section('dashboard-content')
<!-- Welcome Section with Motto -->
<div class="card border-0 shadow-sm mb-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="row g-0">
            <div class="col-md-8 p-4">
                <div class="d-flex flex-column h-100 justify-content-center">
                    <h4 class="fw-bold mb-2">Selamat Datang di Sistem Informasi Manajemen SMA</h4>
                    <p class="text-muted mb-3">Rangkuman data per tanggal {{ date('d F Y') }}</p>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle p-2 me-3">
                            <i class="bx bxs-quote-alt-left text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">"Tut Wuri Handayani"</h6>
                            <small class="text-muted">Di belakang memberi dorongan</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-sm btn-primary me-2" onclick="window.location.reload()">
                            <i class="bx bx-refresh me-1"></i> Refresh Data
                        </button>
                        <a href="{{ route('settings.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-cog me-1"></i> Pengaturan
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 bg-primary d-flex align-items-center justify-content-center p-4">
                <div class="text-center text-white">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SMA" class="img-fluid rounded mb-3" style="border: 5px solid rgba(255,255,255,0.2); max-height: 80px;">
                    @else
                        <img src="https://ui-avatars.com/api/?name=SMA&background=0066b3&color=fff&bold=true&size=80" alt="Logo SMA" class="img-fluid rounded mb-3" style="border: 5px solid rgba(255,255,255,0.2)">
                    @endif
                    <h5 class="mb-1">{{ config('app.name', 'SMA Negeri') }}</h5>
                    <p class="mb-0 small opacity-75">Pendidikan Berkualitas untuk Masa Depan Cemerlang</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Students Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm hover-shadow">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-primary bg-opacity-15 rounded-3 p-3 me-3">
                        <i class="bx bxs-user-detail fs-1 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase">Total Siswa</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalStudents }}</h3>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, ($totalStudents/500)*100) }}%" aria-valuenow="{{ $totalStudents }}" aria-valuemin="0" aria-valuemax="500"></div>
                </div>
                <div class="mt-1">
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bx bx-show me-1"></i> Lihat Data Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Teachers Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm hover-shadow">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-info bg-opacity-15 rounded-3 p-3 me-3">
                        <i class="bx bxs-user-badge fs-1 text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase">Total Guru</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalTeachers }}</h3>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ min(100, ($totalTeachers/50)*100) }}%" aria-valuenow="{{ $totalTeachers }}" aria-valuemin="0" aria-valuemax="50"></div>
                </div>
                <div class="mt-1">
                    <a href="{{ route('teachers.index') }}" class="btn btn-sm btn-outline-info w-100">
                        <i class="bx bx-show me-1"></i> Lihat Data Guru
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Classes Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm hover-shadow">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-success bg-opacity-15 rounded-3 p-3 me-3">
                        <i class="bx bxs-school fs-1 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase">Total Kelas</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalClasses }}</h3>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, ($totalClasses/24)*100) }}%" aria-valuenow="{{ $totalClasses }}" aria-valuemin="0" aria-valuemax="24"></div>
                </div>
                <div class="mt-1">
                    <a href="{{ route('classes.index') }}" class="btn btn-sm btn-outline-success w-100">
                        <i class="bx bx-show me-1"></i> Lihat Data Kelas
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Events Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm hover-shadow">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-warning bg-opacity-15 rounded-3 p-3 me-3">
                        <i class="bx bxs-calendar-event fs-1 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase">Acara Bulan Ini</h6>
                        <h3 class="mb-0 fw-bold">{{ $eventsThisMonth }}</h3>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min(100, ($eventsThisMonth/10)*100) }}%" aria-valuenow="{{ $eventsThisMonth }}" aria-valuemin="0" aria-valuemax="10"></div>
                </div>
                <div class="mt-1">
                    <a href="{{ route('academic-calendar.index') }}" class="btn btn-sm btn-outline-warning w-100">
                        <i class="bx bx-show me-1"></i> Lihat Jadwal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Access Section -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0">Menu Utama</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('students.index') }}" class="text-decoration-none">
                            <div class="card h-100 bg-light border-0 hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="bx bxs-user-detail fs-1 text-primary mb-3"></i>
                                    <h6 class="mb-1 text-dark">Data Siswa</h6>
                                    <p class="small text-muted mb-0">Kelola data siswa</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('students.create') }}" class="text-decoration-none">
                            <div class="card h-100 bg-light border-0 hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="bx bx-user-plus fs-1 text-success mb-3"></i>
                                    <h6 class="mb-1 text-dark">Pendaftaran Siswa</h6>
                                    <p class="small text-muted mb-0">Tambah siswa baru</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('teachers.index') }}" class="text-decoration-none">
                            <div class="card h-100 bg-light border-0 hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="bx bxs-user-badge fs-1 text-info mb-3"></i>
                                    <h6 class="mb-1 text-dark">Data Guru</h6>
                                    <p class="small text-muted mb-0">Kelola data guru</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('teachers.create') }}" class="text-decoration-none">
                            <div class="card h-100 bg-light border-0 hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="bx bx-user-plus fs-1 text-warning mb-3"></i>
                                    <h6 class="mb-1 text-dark">Pendaftaran Guru</h6>
                                    <p class="small text-muted mb-0">Tambah guru baru</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add school calendar and announcement section -->
<div class="row g-4 mt-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Kalender Akademik</h5>
                <a href="{{ route('academic-calendar.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-4">
                @if($upcomingEvents && $upcomingEvents->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingEvents as $event)
                            <div class="list-group-item px-0 py-3 d-flex border-top-0">
                                <div class="me-3 text-center">
                                    <div class="badge 
                                        @if($event->event_type == 'exam') bg-danger
                                        @elseif($event->event_type == 'holiday') bg-success
                                        @elseif($event->event_type == 'meeting') bg-info
                                        @elseif($event->event_type == 'extracurricular') bg-warning text-dark
                                        @else bg-primary @endif
                                        text-white fw-bold py-2 px-3">
                                        {{ strtoupper($event->start_date->format('M')) }}<br>{{ $event->start_date->format('d') }}
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $event->title }}
                                        @if($event->is_important)
                                            <i class="bx bxs-star text-danger"></i>
                                        @endif
                                    </h6>
                                    <p class="mb-1 small text-muted">{{ Str::limit($event->description, 100) }}</p>
                                    <div class="d-flex flex-wrap small text-muted">
                                        <span class="me-3"><i class="bx bx-time me-1"></i>{{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }}</span>
                                        @if($event->location)
                                            <span class="badge bg-light text-dark"><i class="bx bx-map me-1"></i>{{ $event->location }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('academic-calendar.index') }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-calendar me-1"></i> Lihat Semua Agenda
                        </a>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-calendar text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3">Tidak Ada Agenda Mendatang</h5>
                        <p class="text-muted">Belum ada agenda yang dijadwalkan dalam waktu dekat</p>
                        <a href="{{ route('academic-calendar.create') }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i> Tambah Agenda Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Pengumuman Terkini</h5>
                @if(isset($announcements) && count($announcements) > 0)
                    <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-primary">Semua</a>
                @endif
            </div>
            <div class="card-body p-4">
                @if(isset($announcements) && count($announcements) > 0)
                    @foreach($announcements as $announcement)
                        <div class="d-flex align-items-center p-3 {{ $loop->first ? 'bg-primary' : ($loop->index == 1 ? 'bg-info' : 'bg-success') }} bg-opacity-10 rounded mb-3">
                            <div class="icon-box {{ $loop->first ? 'bg-primary' : ($loop->index == 1 ? 'bg-info' : 'bg-success') }} rounded-circle p-2 me-3">
                                <i class="bx {{ $loop->first ? 'bx-news' : ($loop->index == 1 ? 'bx-calendar-check' : 'bx-trophy') }} text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $announcement->title }}</h6>
                                <p class="mb-0 small">{{ Str::limit($announcement->content, 60) }}</p>
                                <small class="text-muted">{{ $announcement->created_at ? $announcement->created_at->diffForHumans() : '-' }}</small>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center mt-3">
                        <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-news me-1"></i> Lihat Semua Pengumuman
                        </a>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-news text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3">Tidak Ada Pengumuman Terkini</h5>
                        <p class="text-muted">Belum ada pengumuman yang dibuat</p>
                        @if(Route::has('announcements.create'))
                            <a href="{{ route('announcements.create') }}" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus me-1"></i> Tambah Pengumuman
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Class Management Section -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Manajemen Kelas</h5>
                <a href="{{ route('classes.create') }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Kelas
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    @if(isset($classGroups) && count($classGroups) > 0)
                        @foreach($classGroups as $level => $levelClasses)
                            <div class="col-md-4">
                                <div class="card h-100 border bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">Kelas {{ $level }}</h6>
                                        <div class="list-group list-group-flush">
                                            @forelse($levelClasses as $class)
                                                <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>{{ $class->name }}</span>
                                                        <span class="badge bg-primary rounded-pill">
                                                            {{ $class->students_count ?? ($class->students ? $class->students->count() : 0) }} siswa
                                                        </span>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-3">
                                                    <p class="text-muted mb-0">Belum ada kelas untuk tingkat ini</p>
                                                </div>
                                            @endforelse
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('classes.index', ['level' => $level]) }}" class="btn btn-sm btn-outline-primary w-100">
                                                Lihat Semua Kelas {{ $level }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center py-5">
                            <i class="bx bx-building-house text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3">Tidak Ada Data Kelas</h5>
                            <p class="text-muted">Belum ada kelas yang ditambahkan</p>
                            <a href="{{ route('classes.create') }}" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus me-1"></i> Tambah Kelas Baru
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .icon-box {
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date picker
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#datePicker", {
                dateFormat: "d M Y",
                defaultDate: "{{ date('d M Y') }}",
                onChange: function(selectedDates, dateStr) {
                    document.getElementById('currentDate').textContent = dateStr;
                }
            });
        }
    });
</script>
@endsection