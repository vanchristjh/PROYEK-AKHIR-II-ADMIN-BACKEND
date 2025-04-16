@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
@endphp

@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('page-actions')
<div class="d-flex align-items-center">
    <button type="button" class="btn btn-sm btn-primary d-flex align-items-center" id="datePicker">
        <i class="bx bx-calendar me-1"></i> <span id="currentDate">{{ date('d M Y') }}</span>
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" id="refreshData">
        <i class="bx bx-refresh"></i>
    </button>
</div>
@endsection

@section('dashboard-content')
<!-- Welcome Section with Motto -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
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
                                <button class="btn btn-sm btn-primary me-2" id="refreshDataBtn">
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
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Students Card -->
    <div class="col-md-3 col-sm-6">
        <div class="card h-100 border-0 shadow-sm hover-shadow">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-primary bg-opacity-15 rounded-3 p-3 me-3">
                        <i class="bx bxs-graduation fs-1 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase">Total Siswa</h6>
                        <h3 class="mb-0 fw-bold" id="totalStudentsDisplay">{{ $totalStudents ?? 0 }}</h3>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, (($totalStudents ?? 0)/500)*100) }}%" aria-valuenow="{{ $totalStudents ?? 0 }}" aria-valuemin="0" aria-valuemax="500"></div>
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
    <div class="col-md-3 col-sm-6">
        <div class="card h-100 border-0 shadow-sm hover-shadow">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-info bg-opacity-15 rounded-3 p-3 me-3">
                        <i class="bx bxs-user-badge fs-1 text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase">Total Guru</h6>
                        <h3 class="mb-0 fw-bold" id="totalTeachersDisplay">{{ $totalTeachers ?? 0 }}</h3>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ min(100, (($totalTeachers ?? 0)/50)*100) }}%" aria-valuenow="{{ $totalTeachers ?? 0 }}" aria-valuemin="0" aria-valuemax="50"></div>
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
    <div class="col-md-3 col-sm-6">
        <div class="card h-100 border-0 shadow-sm hover-shadow">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-success bg-opacity-15 rounded-3 p-3 me-3">
                        <i class="bx bxs-school fs-1 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase">Total Kelas</h6>
                        <h3 class="mb-0 fw-bold" id="totalClassesDisplay">{{ $totalClasses ?? 0 }}</h3>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, (($totalClasses ?? 0)/24)*100) }}%" aria-valuenow="{{ $totalClasses ?? 0 }}" aria-valuemin="0" aria-valuemax="24"></div>
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
    <div class="col-md-3 col-sm-6">
        <div class="card h-100 border-0 shadow-sm hover-shadow">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-warning bg-opacity-15 rounded-3 p-3 me-3">
                        <i class="bx bxs-calendar-event fs-1 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small text-uppercase">Agenda Bulan Ini</h6>
                        <h3 class="mb-0 fw-bold" id="eventsThisMonthDisplay">{{ $eventsThisMonth ?? 0 }}</h3>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min(100, (($eventsThisMonth ?? 0)/10)*100) }}%" aria-valuenow="{{ $eventsThisMonth ?? 0 }}" aria-valuemin="0" aria-valuemax="10"></div>
                </div>
                <div class="mt-1">
                    <a href="{{ route('academic-calendar.index') }}" class="btn btn-sm btn-outline-warning w-100">
                        <i class="bx bx-calendar me-1"></i> Lihat Jadwal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Summary Section -->
<div class="row mb-4">
    <div class="col-12 mb-3">
        <h5 class="section-title">
            <i class="bx bx-line-chart text-primary me-2"></i> Statistik Kehadiran
        </h5>
    </div>

    <!-- Student Attendance Card -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bx bx-user-check me-2 text-success"></i> Kehadiran Siswa
                </h5>
                <a href="{{ route('attendance.report') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-chart me-1"></i> Lihat Detail
                </a>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="p-3 border rounded bg-success bg-opacity-10 mb-2">
                            <h3 class="text-success mb-0">{{ $studentAttendanceSummary['present_percentage'] ?? '0%' }}</h3>
                        </div>
                        <span class="small text-muted">Hadir</span>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded bg-danger bg-opacity-10 mb-2">
                            <h3 class="text-danger mb-0">{{ $studentAttendanceSummary['absent_percentage'] ?? '0%' }}</h3>
                        </div>
                        <span class="small text-muted">Tidak Hadir</span>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded bg-info bg-opacity-10 mb-2">
                            <h3 class="text-info mb-0">{{ $studentAttendanceSummary['total_records'] ?? '0' }}</h3>
                        </div>
                        <span class="small text-muted">Total Absensi</span>
                    </div>
                </div>
                <div class="mt-4">
                    <h6 class="card-subtitle mb-2">Status Kehadiran</h6>
                    <div class="progress-wrapper mb-3">
                        <span class="d-flex justify-content-between">
                            <span>Hadir</span>
                            <span class="text-success">{{ $studentAttendanceSummary['present_percentage'] ?? '0%' }}</span>
                        </span>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $studentAttendanceSummary['present_percentage'] ?? '0%' }}"></div>
                        </div>
                    </div>
                    <div class="progress-wrapper mb-3">
                        <span class="d-flex justify-content-between">
                            <span>Sakit</span>
                            <span class="text-info">{{ isset($studentAttendanceSummary['statuses']['sakit']) ? round(($studentAttendanceSummary['statuses']['sakit'] / max(1, $studentAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}</span>
                        </span>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: {{ isset($studentAttendanceSummary['statuses']['sakit']) ? round(($studentAttendanceSummary['statuses']['sakit'] / max(1, $studentAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}"></div>
                        </div>
                    </div>
                    <div class="progress-wrapper mb-3">
                        <span class="d-flex justify-content-between">
                            <span>Izin</span>
                            <span class="text-warning">{{ isset($studentAttendanceSummary['statuses']['izin']) ? round(($studentAttendanceSummary['statuses']['izin'] / max(1, $studentAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}</span>
                        </span>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: {{ isset($studentAttendanceSummary['statuses']['izin']) ? round(($studentAttendanceSummary['statuses']['izin'] / max(1, $studentAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}"></div>
                        </div>
                    </div>
                    <div class="progress-wrapper mb-3">
                        <span class="d-flex justify-content-between">
                            <span>Alpa</span>
                            <span class="text-danger">{{ isset($studentAttendanceSummary['statuses']['alpa']) ? round(($studentAttendanceSummary['statuses']['alpa'] / max(1, $studentAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}</span>
                        </span>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-danger" style="width: {{ isset($studentAttendanceSummary['statuses']['alpa']) ? round(($studentAttendanceSummary['statuses']['alpa'] / max(1, $studentAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher Attendance Card -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bx bx-user-voice me-2 text-primary"></i> Kehadiran Guru
                </h5>
                <a href="{{ route('teacher-attendance.report') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-chart me-1"></i> Lihat Detail
                </a>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="p-3 border rounded bg-success bg-opacity-10 mb-2">
                            <h3 class="text-success mb-0">{{ $teacherAttendanceSummary['present_percentage'] ?? '0%' }}</h3>
                        </div>
                        <span class="small text-muted">Hadir</span>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded bg-danger bg-opacity-10 mb-2">
                            <h3 class="text-danger mb-0">{{ $teacherAttendanceSummary['absent_percentage'] ?? '0%' }}</h3>
                        </div>
                        <span class="small text-muted">Tidak Hadir</span>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border rounded bg-info bg-opacity-10 mb-2">
                            <h3 class="text-info mb-0">{{ $teacherAttendanceSummary['total_records'] ?? '0' }}</h3>
                        </div>
                        <span class="small text-muted">Total Absensi</span>
                    </div>
                </div>
                <div class="mt-4">
                    <h6 class="card-subtitle mb-2">Status Kehadiran</h6>
                    <div class="progress-wrapper mb-3">
                        <span class="d-flex justify-content-between">
                            <span>Hadir</span>
                            <span class="text-success">{{ $teacherAttendanceSummary['present_percentage'] ?? '0%' }}</span>
                        </span>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $teacherAttendanceSummary['present_percentage'] ?? '0%' }}"></div>
                        </div>
                    </div>
                    <div class="progress-wrapper mb-3">
                        <span class="d-flex justify-content-between">
                            <span>Sakit</span>
                            <span class="text-info">{{ isset($teacherAttendanceSummary['statuses']['sakit']) ? round(($teacherAttendanceSummary['statuses']['sakit'] / max(1, $teacherAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}</span>
                        </span>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: {{ isset($teacherAttendanceSummary['statuses']['sakit']) ? round(($teacherAttendanceSummary['statuses']['sakit'] / max(1, $teacherAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}"></div>
                        </div>
                    </div>
                    <div class="progress-wrapper mb-3">
                        <span class="d-flex justify-content-between">
                            <span>Izin</span>
                            <span class="text-warning">{{ isset($teacherAttendanceSummary['statuses']['izin']) ? round(($teacherAttendanceSummary['statuses']['izin'] / max(1, $teacherAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}</span>
                        </span>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: {{ isset($teacherAttendanceSummary['statuses']['izin']) ? round(($teacherAttendanceSummary['statuses']['izin'] / max(1, $teacherAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}"></div>
                        </div>
                    </div>
                    <div class="progress-wrapper mb-3">
                        <span class="d-flex justify-content-between">
                            <span>Alpa</span>
                            <span class="text-danger">{{ isset($teacherAttendanceSummary['statuses']['alpa']) ? round(($teacherAttendanceSummary['statuses']['alpa'] / max(1, $teacherAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}</span>
                        </span>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-danger" style="width: {{ isset($teacherAttendanceSummary['statuses']['alpa']) ? round(($teacherAttendanceSummary['statuses']['alpa'] / max(1, $teacherAttendanceSummary['total_records'])) * 100) . '%' : '0%' }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Access Section -->
<div class="row mb-4">
    <div class="col-12 mb-3">
        <h5 class="section-title">
            <i class="bx bx-grid-alt text-primary me-2"></i> Menu Utama
        </h5>
    </div>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-3 col-sm-6">
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
                    <div class="col-md-3 col-sm-6">
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
                    <div class="col-md-3 col-sm-6">
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
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('classes.create') }}" class="text-decoration-none">
                            <div class="card h-100 bg-light border-0 hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="bx bx-buildings fs-1 text-success mb-3"></i>
                                    <h6 class="mb-1 text-dark">Buat Kelas Baru</h6>
                                    <p class="small text-muted mb-0">Tambah kelas baru</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('academic-grades.index') }}" class="text-decoration-none">
                            <div class="card h-100 bg-light border-0 hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="bx bx-book-open fs-1 text-primary mb-3"></i>
                                    <h6 class="mb-1 text-dark">Nilai Akademik</h6>
                                    <p class="small text-muted mb-0">Kelola nilai siswa</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calendar and Announcement Section -->
<div class="row g-4 mb-4">
    <div class="col-12 mb-3">
        <h5 class="section-title">
            <i class="bx bx-calendar-event text-primary me-2"></i> Informasi Terkini
        </h5>
    </div>
    
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Kalender Akademik</h5>
                <a href="{{ route('academic-calendar.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-4">
                @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
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
<div class="row">
    <div class="col-12 mb-3">
        <h5 class="section-title">
            <i class="bx bx-buildings text-primary me-2"></i> Manajemen Kelas
        </h5>
    </div>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Kelas</h5>
                <a href="{{ route('classes.create') }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Kelas
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    @if(isset($classGroups) && count($classGroups) > 0)
                        @foreach($classGroups as $level => $levelClasses)
                            <div class="col-md-4 mb-4">
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
    /* Basic styling */
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
    .section-title {
        font-weight: 600;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding-bottom: 10px;
        margin-bottom: 15px;
        color: #333;
    }
    
    /* Dark mode compatibility */
    .dark-mode .badge.text-dark {
        color: #343a40 !important;
    }
    .dark-mode .bg-light {
        background-color: var(--light) !important;
    }
    .dark-mode .hover-shadow:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
    }
    .dark-mode .text-dark {
        color: var(--text-color) !important;
    }
    .dark-mode .section-title {
        border-color: rgba(255,255,255,0.1);
        color: #e4e6ef;
    }

    /* Loading indicators */
    .loading {
        position: relative;
    }
    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    .dark-mode .loading::after {
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .icon-box {
            width: 45px;
            height: 45px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date picker initialization
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#datePicker", {
                dateFormat: "d M Y",
                defaultDate: "{{ date('d M Y') }}",
                onChange: function(selectedDates, dateStr) {
                    document.getElementById('currentDate').textContent = dateStr;
                    // You could add an AJAX call here to refresh data based on selected date
                    showToast('Tanggal diubah ke ' + dateStr, 'info');
                }
            });
        } else {
            console.warn('Flatpickr library not loaded');
        }

        // Refresh data functionality
        function refreshDashboardData() {
            // Show loading indicators
            document.querySelectorAll('.card').forEach(card => {
                card.classList.add('loading');
            });
            
            // Make AJAX request to get fresh data
            fetch('{{ route('dashboard') }}?ajax=true')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update dashboard stats
                    if (data.stats) {
                        document.getElementById('totalStudentsDisplay').textContent = data.stats.totalStudents || 0;
                        document.getElementById('totalTeachersDisplay').textContent = data.stats.totalTeachers || 0;
                        document.getElementById('totalClassesDisplay').textContent = data.stats.totalClasses || 0;
                        document.getElementById('eventsThisMonthDisplay').textContent = data.stats.eventsThisMonth || 0;
                    }
                    
                    // Remove loading indicators
                    document.querySelectorAll('.card').forEach(card => {
                        card.classList.remove('loading');
                    });
                    
                    // Show success notification
                    showToast('Data berhasil diperbarui', 'success');
                })
                .catch(error => {
                    console.error('Error refreshing data:', error);
                    
                    // Remove loading indicators
                    document.querySelectorAll('.card').forEach(card => {
                        card.classList.remove('loading');
                    });
                    
                    // Show error notification
                    showToast('Gagal memperbarui data: ' + error.message, 'error');
                });
        }

        // Add click handler for refresh buttons
        document.getElementById('refreshData').addEventListener('click', refreshDashboardData);
        document.getElementById('refreshDataBtn').addEventListener('click', refreshDashboardData);
        
        // Dark mode compatibility for notification badges
        function updateThemeStyles() {
            if (document.body.classList.contains('dark-mode')) {
                // Apply dark theme specific adjustments if needed
                document.querySelectorAll('.bg-light.text-dark').forEach(el => {
                    el.style.color = '#e4e6ef';
                });
            } else {
                // Restore light theme styles
                document.querySelectorAll('.bg-light.text-dark').forEach(el => {
                    el.style.color = '';
                });
            }
        }

        // Watch for theme changes
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.attributeName === 'class') {
                    updateThemeStyles();
                }
            });
        });
        observer.observe(document.body, { attributes: true });
        
        // Initial theme check
        updateThemeStyles();
        
        // Update notification counter if applicable
        function updateNotificationBadge() {
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            const notificationBadges = document.querySelectorAll('.notifications .badge');
            
            notificationBadges.forEach(badge => {
                badge.textContent = unreadCount > 0 ? unreadCount : '';
                badge.style.display = unreadCount > 0 ? '' : 'none';
            });
        }
        
        // Call once on page load
        if (typeof updateNotificationBadge === 'function') {
            updateNotificationBadge();
        }
    });
</script>
@endsection