@extends('layouts.dashboard')

@section('page-title', 'Laporan Absensi')

@section('page-actions')
<a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Filter Laporan</h6>
                        <form action="{{ route('attendance.report') }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label for="class_id" class="form-label">Kelas</label>
                                <select class="form-select" id="class_id" name="class_id">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes ?? [] as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="student_id" class="form-label">Siswa</label>
                                <select class="form-select" id="student_id" name="student_id">
                                    <option value="">Semua Siswa</option>
                                    @foreach($students ?? [] as $student)
                                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="month" class="form-label">Bulan</label>
                                <select class="form-select" id="month" name="month">
                                    <option value="">Semua Bulan</option>
                                    @foreach(range(1, 12) as $month)
                                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="year" class="form-label">Tahun</label>
                                <select class="form-select" id="year" name="year">
                                    @foreach(range(date('Y') - 2, date('Y')) as $year)
                                        <option value="{{ $year }}" {{ (request('year', date('Y')) == $year) ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter-alt me-1"></i> Filter
                                </button>
                                <a href="{{ route('attendance.report') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </a>
                                <button type="submit" class="btn btn-success" name="export" value="1">
                                    <i class="bx bx-download me-1"></i> Ekspor ke Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Grafik Kehadiran</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Ringkasan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light text-center">
                                    <h3 class="text-primary mb-0">{{ $summary['total_sessions'] ?? 0 }}</h3>
                                    <small class="text-muted">Total Sesi</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light text-center">
                                    <h3 class="text-success mb-0">{{ $summary['attendance_percentage'] ?? '0%' }}</h3>
                                    <small class="text-muted">Kehadiran</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light text-center">
                                    <h3 class="text-warning mb-0">{{ $summary['total_students'] ?? 0 }}</h3>
                                    <small class="text-muted">Total Siswa</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light text-center">
                                    <h3 class="text-danger mb-0">{{ $summary['absent_count'] ?? 0 }}</h3>
                                    <small class="text-muted">Tidak Hadir</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6>Persentase Status</h6>
                            <div class="progress-wrapper mb-3">
                                <span class="d-flex justify-content-between">
                                    <span>Hadir</span>
                                    <span class="text-success">{{ $percentages['hadir'] ?? '0%' }}</span>
                                </span>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $percentages['hadir'] ?? '0%' }}"></div>
                                </div>
                            </div>
                            <div class="progress-wrapper mb-3">
                                <span class="d-flex justify-content-between">
                                    <span>Sakit</span>
                                    <span class="text-info">{{ $percentages['sakit'] ?? '0%' }}</span>
                                </span>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: {{ $percentages['sakit'] ?? '0%' }}"></div>
                                </div>
                            </div>
                            <div class="progress-wrapper mb-3">
                                <span class="d-flex justify-content-between">
                                    <span>Izin</span>
                                    <span class="text-warning">{{ $percentages['izin'] ?? '0%' }}</span>
                                </span>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $percentages['izin'] ?? '0%' }}"></div>
                                </div>
                            </div>
                            <div class="progress-wrapper mb-3">
                                <span class="d-flex justify-content-between">
                                    <span>Alpa</span>
                                    <span class="text-danger">{{ $percentages['alpa'] ?? '0%' }}</span>
                                </span>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: {{ $percentages['alpa'] ?? '0%' }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Data Absensi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances ?? [] as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->session->date)->format('d M Y') }}</td>
                                <td>{{ $attendance->student->name ?? 'N/A' }}</td>
                                <td>{{ $attendance->session->class->name ?? 'N/A' }}</td>
                                <td>{{ $attendance->session->subject->name ?? 'N/A' }}</td>
                                <td>
                                    @switch($attendance->status)
                                        @case('hadir')
                                            <span class="badge bg-success">Hadir</span>
                                            @break
                                        @case('izin')
                                            <span class="badge bg-warning text-dark">Izin</span>
                                            @break
                                        @case('sakit')
                                            <span class="badge bg-info">Sakit</span>
                                            @break
                                        @case('alpa')
                                            <span class="badge bg-danger">Alpa</span>
                                            @break
                                        @case('terlambat')
                                            <span class="badge bg-secondary">Terlambat</span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-dark">{{ $attendance->status }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $attendance->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Tidak ada data absensi yang ditemukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

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
        // Attendance Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        const chartData = {
            labels: {!! json_encode($chartData['labels'] ?? []) !!},
            datasets: [
                {
                    label: 'Hadir',
                    data: {!! json_encode($chartData['hadir'] ?? []) !!},
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Sakit',
                    data: {!! json_encode($chartData['sakit'] ?? []) !!},
                    backgroundColor: 'rgba(23, 162, 184, 0.2)',
                    borderColor: 'rgba(23, 162, 184, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Izin',
                    data: {!! json_encode($chartData['izin'] ?? []) !!},
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Alpa',
                    data: {!! json_encode($chartData['alpa'] ?? []) !!},
                    backgroundColor: 'rgba(220, 53, 69, 0.2)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]
        };
        
        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
        
        // Dependent dropdowns (class -> students)
        const classSelect = document.getElementById('class_id');
        const studentSelect = document.getElementById('student_id');
        
        if (classSelect && studentSelect) {
            classSelect.addEventListener('change', function() {
                const classId = this.value;
                
                // Clear student dropdown
                studentSelect.innerHTML = '<option value="">Semua Siswa</option>';
                
                if (classId) {
                    // Make AJAX request to get students for this class
                    fetch(`/api/classes/${classId}/students`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(student => {
                                const option = document.createElement('option');
                                option.value = student.id;
                                option.textContent = student.name;
                                studentSelect.appendChild(option);
                            });
                        });
                }
            });
        }
    });
</script>
@endsection
