@extends('layouts.dashboard')

@section('page-title', 'Laporan Absensi Guru')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('teacher-attendance.index') }}" class="btn btn-secondary btn-sm">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row mb-4">
    <!-- Summary Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-lg bg-light-primary rounded">
                        <div class="avatar-content">
                            <i class="bx bx-calendar fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0">{{ $summary['total_sessions'] ?? 0 }}</h5>
                        <p class="text-muted mb-0">Total Sesi Absensi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-lg bg-light-success rounded">
                        <div class="avatar-content">
                            <i class="bx bx-user-check fs-3 text-success"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0">{{ $summary['total_teachers'] ?? 0 }}</h5>
                        <p class="text-muted mb-0">Total Guru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-lg bg-light-info rounded">
                        <div class="avatar-content">
                            <i class="bx bx-line-chart fs-3 text-info"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0">{{ $summary['attendance_percentage'] ?? '0%' }}</h5>
                        <p class="text-muted mb-0">Tingkat Kehadiran</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-lg bg-light-danger rounded">
                        <div class="avatar-content">
                            <i class="bx bx-user-x fs-3 text-danger"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0">{{ $summary['absent_count'] ?? 0 }}</h5>
                        <p class="text-muted mb-0">Total Ketidakhadiran</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Filter Card -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher-attendance.report') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="teacher_id" class="form-label">Guru</label>
                        <select class="form-select" id="teacher_id" name="teacher_id">
                            <option value="">Semua Guru</option>
                            @foreach($teachers ?? [] as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="month" class="form-label">Bulan</label>
                        <select class="form-select" id="month" name="month">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $month)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="year" class="form-label">Tahun</label>
                        <select class="form-select" id="year" name="year">
                            @foreach(range(date('Y'), date('Y')-5) as $year)
                                <option value="{{ $year }}" {{ request('year', date('Y')) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="activity_type" class="form-label">Jenis Kegiatan</label>
                        <select class="form-select" id="activity_type" name="activity_type">
                            <option value="">Semua Kegiatan</option>
                            @foreach($activityTypes ?? [] as $type)
                                <option value="{{ $type }}" {{ request('activity_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bx bx-filter-alt me-1"></i> Filter
                        </button>
                        <a href="{{ route('teacher-attendance.report') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-reset me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Chart & Status Card -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Grafik Kehadiran Guru</h5>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary active" id="show-monthly">Bulanan</button>
                    <button type="button" class="btn btn-outline-secondary" id="show-status">Status</button>
                </div>
            </div>
            <div class="card-body">
                <div id="monthly-chart-container">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
                <div id="status-chart-container" style="display: none;">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Status Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th>
                                <th class="text-center">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="badge bg-success me-1">H</span> Hadir
                                </td>
                                <td class="text-center">{{ $percentages['hadir'] ?? '0%' }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge bg-info me-1">S</span> Sakit
                                </td>
                                <td class="text-center">{{ $percentages['sakit'] ?? '0%' }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge bg-warning text-dark me-1">I</span> Izin
                                </td>
                                <td class="text-center">{{ $percentages['izin'] ?? '0%' }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge bg-danger me-1">A</span> Alpa
                                </td>
                                <td class="text-center">{{ $percentages['alpa'] ?? '0%' }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary me-1">T</span> Terlambat
                                </td>
                                <td class="text-center">{{ $percentages['terlambat'] ?? '0%' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <a href="#" class="btn btn-outline-primary w-100" onclick="printReport()">
                        <i class="bx bx-printer me-1"></i> Cetak Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Attendance Records -->
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Riwayat Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Nama Guru</th>
                                <th>Status</th>
                                <th>Waktu Absen</th>
                                <th>Catatan</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances ?? [] as $attendance)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance->session->date)->format('d M Y') }}</td>
                                <td>{{ $attendance->session->activity_type ?? 'Umum' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($attendance->teacher && $attendance->teacher->profile_photo)
                                            <img src="{{ asset('storage/'.$attendance->teacher->profile_photo) }}" alt="{{ $attendance->teacher->name ?? 'N/A' }}" class="rounded-circle me-2" width="32" height="32">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->teacher->name ?? 'N/A') }}&background=2d4059&color=fff&size=32" alt="{{ $attendance->teacher->name ?? 'N/A' }}" class="rounded-circle me-2" width="32" height="32">
                                        @endif
                                        {{ $attendance->teacher->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    @if($attendance->status == 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($attendance->status == 'izin')
                                        <span class="badge bg-warning text-dark">Izin</span>
                                    @elseif($attendance->status == 'sakit')
                                        <span class="badge bg-info">Sakit</span>
                                    @elseif($attendance->status == 'alpa')
                                        <span class="badge bg-danger">Alpa</span>
                                    @elseif($attendance->status == 'terlambat')
                                        <span class="badge bg-secondary">Terlambat</span>
                                    @endif
                                </td>
                                <td>{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($attendance->notes, 30) ?? '-' }}</td>
                                <td>
                                    @if($attendance->photo)
                                        <a href="{{ asset('storage/'.$attendance->photo) }}" target="_blank" class="d-inline-block">
                                            <img src="{{ asset('storage/'.$attendance->photo) }}" alt="Foto kehadiran" class="img-thumbnail" style="height: 40px; width: 40px; object-fit: cover;">
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bx bx-calendar-x fs-3 d-block mb-2"></i>
                                    Tidak ada data absensi guru yang ditemukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $attendances->links() ?? '' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get chart data from PHP
        const chartData = @json($chartData ?? []);
        
        // Monthly/Status chart toggle
        const monthlyChartContainer = document.getElementById('monthly-chart-container');
        const statusChartContainer = document.getElementById('status-chart-container');
        const showMonthlyBtn = document.getElementById('show-monthly');
        const showStatusBtn = document.getElementById('show-status');
        
        showMonthlyBtn.addEventListener('click', function() {
            monthlyChartContainer.style.display = 'block';
            statusChartContainer.style.display = 'none';
            showMonthlyBtn.classList.add('active');
            showStatusBtn.classList.remove('active');
        });
        
        showStatusBtn.addEventListener('click', function() {
            monthlyChartContainer.style.display = 'none';
            statusChartContainer.style.display = 'block';
            showMonthlyBtn.classList.remove('active');
            showStatusBtn.classList.add('active');
        });
        
        // Monthly Chart (Line chart for attendance over time)
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: chartData.labels || [],
                datasets: [
                    {
                        label: 'Hadir',
                        data: chartData.hadir || [],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.1,
                        fill: true
                    },
                    {
                        label: 'Sakit',
                        data: chartData.sakit || [],
                        borderColor: '#17a2b8',
                        backgroundColor: 'rgba(23, 162, 184, 0.1)',
                        tension: 0.1,
                        fill: true
                    },
                    {
                        label: 'Izin',
                        data: chartData.izin || [],
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.1,
                        fill: true
                    },
                    {
                        label: 'Alpa',
                        data: chartData.alpa || [],
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.1,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Grafik Kehadiran Guru'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Status Chart (Doughnut chart showing percentage by status)
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        
        // Calculate total for each status
        const totalHadir = chartData.hadir ? chartData.hadir.reduce((a, b) => a + b, 0) : 0;
        const totalSakit = chartData.sakit ? chartData.sakit.reduce((a, b) => a + b, 0) : 0;
        const totalIzin = chartData.izin ? chartData.izin.reduce((a, b) => a + b, 0) : 0;
        const totalAlpa = chartData.alpa ? chartData.alpa.reduce((a, b) => a + b, 0) : 0;
        const totalTerlambat = chartData.terlambat ? chartData.terlambat.reduce((a, b) => a + b, 0) : 0;
        
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alpa', 'Terlambat'],
                datasets: [{
                    data: [totalHadir, totalSakit, totalIzin, totalAlpa, totalTerlambat],
                    backgroundColor: [
                        '#28a745', // Success green for hadir
                        '#17a2b8', // Info blue for sakit
                        '#ffc107', // Warning yellow for izin
                        '#dc3545', // Danger red for alpa
                        '#6c757d'  // Secondary gray for terlambat
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Status Kehadiran'
                    }
                }
            }
        });
    });
    
    // Print function
    function printReport() {
        const printWindow = window.open('', '_blank');
        const params = new URLSearchParams(window.location.search);
        const teacherId = params.get('teacher_id') || 'Semua';
        const month = params.get('month') || 'Semua';
        const year = params.get('year') || new Date().getFullYear();
        const activityType = params.get('activity_type') || 'Semua';
        
        // Get month name if month is specified
        let monthName = 'Semua Bulan';
        if (month) {
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            monthName = monthNames[month - 1];
        }
        
        // Get teacher name if teacher_id is specified
        let teacherName = 'Semua Guru';
        if (teacherId && teacherId !== 'Semua') {
            const teacherSelect = document.getElementById('teacher_id');
            const selectedOption = teacherSelect.options[teacherSelect.selectedIndex];
            if (selectedOption) {
                teacherName = selectedOption.text;
            }
        }
        
        printWindow.document.write(`
            <html>
            <head>
                <title>Laporan Absensi Guru</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: Arial, sans-serif; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .school-name { font-size: 18px; font-weight: bold; }
                    .report-title { font-size: 16px; margin-top: 5px; }
                    .report-meta { font-size: 14px; margin-top: 5px; }
                    table { width: 100%; margin-top: 20px; border-collapse: collapse; }
                    th, td { padding: 10px; border: 1px solid #ddd; }
                    th { background-color: #f8f9fa; }
                    .text-center { text-align: center; }
                    .footer { margin-top: 30px; text-align: right; }
                    .summary-box {
                        border: 1px solid #ddd;
                        padding: 15px;
                        margin-bottom: 20px;
                    }
                    .summary-title {
                        font-weight: bold;
                        margin-bottom: 10px;
                    }
                    .summary-grid {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 10px;
                    }
                    .summary-item {
                        display: flex;
                        justify-content: space-between;
                    }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="container mt-4">
                    <div class="header">
                        <div class="school-name">SEKOLAH MENENGAH ATAS</div>
                        <div class="report-title">LAPORAN ABSENSI GURU</div>
                        <div class="report-meta">Periode: ${monthName} ${year}</div>
                        <div class="report-meta">Guru: ${teacherName}, Kegiatan: ${activityType}</div>
                    </div>
                    
                    <div class="summary-box">
                        <div class="summary-title">Ringkasan Kehadiran</div>
                        <div class="summary-grid">
                            <div class="summary-item">
                                <span>Total Sesi Absensi:</span>
                                <span>${{{ $summary['total_sessions'] ?? 0 }}}</span>
                            </div>
                            <div class="summary-item">
                                <span>Total Guru:</span>
                                <span>${{{ $summary['total_teachers'] ?? 0 }}}</span>
                            </div>
                            <div class="summary-item">
                                <span>Tingkat Kehadiran:</span>
                                <span>${{{ $summary['attendance_percentage'] ?? '0%' }}}</span>
                            </div>
                            <div class="summary-item">
                                <span>Total Ketidakhadiran:</span>
                                <span>${{{ $summary['absent_count'] ?? 0 }}}</span>
                            </div>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Nama Guru</th>
                                <th>Status</th>
                                <th>Waktu Absen</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances ?? [] as $attendance)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance->session->date)->format('d M Y') }}</td>
                                <td>{{ $attendance->session->activity_type ?? 'Umum' }}</td>
                                <td>{{ $attendance->teacher->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($attendance->status) }}</td>
                                <td>{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</td>
                                <td>{{ $attendance->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="footer">
                        <p>
                            <div>Mengetahui,</div>
                            <div style="margin-top: 80px;">Kepala Sekolah</div>
                        </p>
                    </div>
                    
                    <div class="mt-3 no-print">
                        <button class="btn btn-primary btn-sm" onclick="window.print()">Cetak</button>
                        <button class="btn btn-secondary btn-sm" onclick="window.close()">Tutup</button>
                    </div>
                </div>
            </body>
            </html>
        `);
        
        printWindow.document.close();
        setTimeout(() => {
            printWindow.focus();
        }, 500);
    }
</script>
@endsection
