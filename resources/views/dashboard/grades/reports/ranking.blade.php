@extends('layouts.dashboard')

@section('page-title', 'Peringkat Nilai Kelas')

@section('page-actions')
<a href="{{ route('academic-reports.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali ke Laporan
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4">Filter Peringkat Kelas</h5>
        
        <form action="{{ route('academic-reports.class-ranking') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="class_id" class="form-label">Kelas</label>
                <select class="form-select" id="class_id" name="class_id" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-4">
                <label for="academic_year" class="form-label">Tahun Akademik</label>
                <select class="form-select" id="academic_year" name="academic_year" required>
                    <option value="">-- Pilih Tahun Akademik --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select" id="semester" name="semester" required>
                    <option value="">-- Pilih --</option>
                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                </select>
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bx bx-search me-1"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

@if($rankingData)
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            Peringkat Kelas: {{ $rankingData['class']->name }} ({{ $rankingData['academic_year'] }} - Semester {{ $rankingData['semester'] }})
        </h5>
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
            <i class="bx bx-printer me-1"></i> Cetak Peringkat
        </button>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr class="table-primary">
                        <th>Peringkat</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Rata-rata Nilai</th>
                        <th>Grade</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rankingData['students'] as $studentData)
                        <tr>
                            <td>
                                @if($studentData['rank'] <= 3)
                                    <span class="badge bg-warning text-dark">{{ $studentData['rank'] }}</span>
                                @else
                                    {{ $studentData['rank'] }}
                                @endif
                            </td>
                            <td>{{ $studentData['student']->nis ?? 'N/A' }}</td>
                            <td>{{ $studentData['student']->name }}</td>
                            <td>{{ number_format($studentData['average'], 2) }}</td>
                            <td>
                                <span class="badge 
                                    @if($studentData['letter_grade'] == 'A') bg-success
                                    @elseif($studentData['letter_grade'] == 'B') bg-primary
                                    @elseif($studentData['letter_grade'] == 'C') bg-info
                                    @elseif($studentData['letter_grade'] == 'D') bg-warning
                                    @else bg-danger @endif">
                                    {{ $studentData['letter_grade'] }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('academic-reports.index', [
                                    'class_id' => $rankingData['class']->id,
                                    'student_id' => $studentData['student']->id,
                                    'academic_year' => $rankingData['academic_year'],
                                    'semester' => $rankingData['semester']
                                ]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show me-1"></i> Detail Rapor
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Tidak ada data nilai untuk kelas ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(count($rankingData['students']) > 0)
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">Statistik Kelas</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="p-3 border rounded text-center">
                                        <h6 class="text-muted mb-1">Rata-rata Kelas</h6>
                                        <h3>{{ number_format(collect($rankingData['students'])->avg('average'), 2) }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded text-center">
                                        <h6 class="text-muted mb-1">Nilai Tertinggi</h6>
                                        <h3>{{ number_format(collect($rankingData['students'])->max('average'), 2) }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded text-center">
                                        <h6 class="text-muted mb-1">Nilai Terendah</h6>
                                        <h3>{{ number_format(collect($rankingData['students'])->min('average'), 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            
                            <h6 class="mb-3">Distribusi Grade</h6>
                            <div class="progress mb-2" style="height: 20px;">
                                @php
                                    $totalStudents = count($rankingData['students']);
                                    $gradeA = collect($rankingData['students'])->filter(function($data) { return $data['letter_grade'] == 'A'; })->count();
                                    $gradeB = collect($rankingData['students'])->filter(function($data) { return $data['letter_grade'] == 'B'; })->count();
                                    $gradeC = collect($rankingData['students'])->filter(function($data) { return $data['letter_grade'] == 'C'; })->count();
                                    $gradeD = collect($rankingData['students'])->filter(function($data) { return $data['letter_grade'] == 'D'; })->count();
                                    $gradeE = collect($rankingData['students'])->filter(function($data) { return $data['letter_grade'] == 'E'; })->count();
                                    
                                    $aPercent = $totalStudents > 0 ? ($gradeA / $totalStudents) * 100 : 0;
                                    $bPercent = $totalStudents > 0 ? ($gradeB / $totalStudents) * 100 : 0;
                                    $cPercent = $totalStudents > 0 ? ($gradeC / $totalStudents) * 100 : 0;
                                    $dPercent = $totalStudents > 0 ? ($gradeD / $totalStudents) * 100 : 0;
                                    $ePercent = $totalStudents > 0 ? ($gradeE / $totalStudents) * 100 : 0;
                                @endphp
                                
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $aPercent }}%" aria-valuenow="{{ $aPercent }}" aria-valuemin="0" aria-valuemax="100" title="A: {{ $gradeA }} siswa ({{ number_format($aPercent, 1) }}%)"></div>
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $bPercent }}%" aria-valuenow="{{ $bPercent }}" aria-valuemin="0" aria-valuemax="100" title="B: {{ $gradeB }} siswa ({{ number_format($bPercent, 1) }}%)"></div>
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $cPercent }}%" aria-valuenow="{{ $cPercent }}" aria-valuemin="0" aria-valuemax="100" title="C: {{ $gradeC }} siswa ({{ number_format($cPercent, 1) }}%)"></div>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $dPercent }}%" aria-valuenow="{{ $dPercent }}" aria-valuemin="0" aria-valuemax="100" title="D: {{ $dPercent }} siswa ({{ number_format($dPercent, 1) }}%)"></div>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $ePercent }}%" aria-valuenow="{{ $ePercent }}" aria-valuemin="0" aria-valuemax="100" title="E: {{ $gradeE }} siswa ({{ number_format($ePercent, 1) }}%)"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-2 small text-muted">
                                <div>A: {{ $gradeA }} siswa ({{ number_format($aPercent, 1) }}%)</div>
                                <div>B: {{ $gradeB }} siswa ({{ number_format($bPercent, 1) }}%)</div>
                                <div>C: {{ $gradeC }} siswa ({{ number_format($cPercent, 1) }}%)</div>
                                <div>D: {{ $gradeD }} siswa ({{ number_format($dPercent, 1) }}%)</div>
                                <div>E: {{ $gradeE }} siswa ({{ number_format($ePercent, 1) }}%)</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">Top 3 Peringkat Kelas</h6>
                        </div>
                        <div class="card-body">
                            @foreach($rankingData['students']->take(3) as $index => $topStudent)
                                <div class="d-flex align-items-center mb-3 p-3 {{ $index === 0 ? 'bg-warning bg-opacity-10' : 'bg-light' }} rounded">
                                    <div class="me-3">
                                        <div class="avatar bg-primary rounded-circle text-center fs-4 text-white" style="width: 48px; height: 48px; line-height: 48px;">
                                            {{ $index + 1 }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $topStudent['student']->name }}</h6>
                                        <p class="mb-0 small">NIS: {{ $topStudent['student']->nis ?? 'N/A' }} | Rata-rata: {{ number_format($topStudent['average'], 2) }} ({{ $topStudent['letter_grade'] }})</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@elseif(request('class_id') && request('academic_year') && request('semester'))
<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bx bx-search-alt text-muted" style="font-size: 3rem;"></i>
        <h5 class="mt-3">Tidak ada data peringkat yang ditemukan</h5>
        <p class="text-muted">Data nilai untuk kelas dan periode yang dipilih tidak ditemukan.</p>
    </div>
</div>
@else
<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bx bx-bar-chart-alt-2 text-muted" style="font-size: 3rem;"></i>
        <h5 class="mt-3">Silahkan Pilih Filter</h5>
        <p class="text-muted">Pilih kelas, tahun akademik dan semester untuk melihat peringkat kelas.</p>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2 if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('#class_id, #academic_year, #semester').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
