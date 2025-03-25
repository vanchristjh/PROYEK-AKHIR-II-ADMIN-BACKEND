@extends('layouts.dashboard')

@section('page-title', 'Detail Absensi')

@section('page-actions')
<div class="d-flex">
    @if(!$session->is_completed)
    <a href="{{ route('attendance.edit', $session->id) }}" class="btn btn-primary btn-sm me-2">
        <i class="bx bx-edit me-1"></i> Edit
    </a>
    @endif
    <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Informasi Sesi</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Status</span>
                        <span>
                            @if($session->is_completed)
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-warning">Belum Selesai</span>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Kelas</span>
                        <span>{{ $session->class->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Mata Pelajaran</span>
                        <span>{{ $session->subject->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Tanggal</span>
                        <span>{{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Waktu</span>
                        <span>{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Dibuat Oleh</span>
                        <span>{{ $session->creator->name ?? 'N/A' }}</span>
                    </li>
                </ul>

                @if($session->notes)
                <div class="mt-3">
                    <h6>Catatan:</h6>
                    <p class="mb-0">{{ $session->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Ringkasan Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <canvas id="attendanceChart" width="400" height="300"></canvas>
                </div>
                <div class="row text-center">
                    <div class="col">
                        <div class="d-flex flex-column align-items-center">
                            <h3 class="text-success mb-0">{{ $attendanceSummary['hadir'] ?? 0 }}</h3>
                            <span class="small text-muted">Hadir</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-column align-items-center">
                            <h3 class="text-info mb-0">{{ $attendanceSummary['sakit'] ?? 0 }}</h3>
                            <span class="small text-muted">Sakit</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-column align-items-center">
                            <h3 class="text-warning mb-0">{{ $attendanceSummary['izin'] ?? 0 }}</h3>
                            <span class="small text-muted">Izin</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-column align-items-center">
                            <h3 class="text-danger mb-0">{{ $attendanceSummary['alpa'] ?? 0 }}</h3>
                            <span class="small text-muted">Alpa</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Kehadiran Siswa</h5>
                <a href="{{ route('attendance.export', $session->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-download me-1"></i> Ekspor
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>NIS</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances ?? [] as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $attendance->student->name ?? 'N/A' }}</td>
                                <td>{{ $attendance->student->nis ?? '-' }}</td>
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
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Belum ada data kehadiran
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Data from the controller
        const attendanceData = {
            labels: ['Hadir', 'Sakit', 'Izin', 'Alpa', 'Terlambat'],
            datasets: [{
                data: [
                    {{ $attendanceSummary['hadir'] ?? 0 }},
                    {{ $attendanceSummary['sakit'] ?? 0 }},
                    {{ $attendanceSummary['izin'] ?? 0 }},
                    {{ $attendanceSummary['alpa'] ?? 0 }},
                    {{ $attendanceSummary['terlambat'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',  // success - Hadir
                    'rgba(23, 162, 184, 0.7)', // info - Sakit
                    'rgba(255, 193, 7, 0.7)',  // warning - Izin
                    'rgba(220, 53, 69, 0.7)',  // danger - Alpa
                    'rgba(108, 117, 125, 0.7)' // secondary - Terlambat
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(108, 117, 125, 1)'
                ],
                borderWidth: 1
            }]
        };
        
        const attendanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: attendanceData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection
