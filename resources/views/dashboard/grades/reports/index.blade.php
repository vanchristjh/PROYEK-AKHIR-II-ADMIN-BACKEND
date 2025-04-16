@extends('layouts.dashboard')

@section('page-title', 'Laporan Nilai Akademik')

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4">Filter Laporan Nilai</h5>
        
        <form action="{{ route('academic-reports.index') }}" method="GET" class="row g-3">
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
                <label for="student_id" class="form-label">Siswa</label>
                <select class="form-select" id="student_id" name="student_id" {{ count($students) > 0 ? 'required' : 'disabled' }}>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }} ({{ $student->nis ?? 'No NIS' }})
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Pilih kelas terlebih dahulu</div>
            </div>
            
            <div class="col-md-4">
                <label for="subject_id" class="form-label">Mata Pelajaran</label>
                <select class="form-select" id="subject_id" name="subject_id">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
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
            
            <div class="col-md-4">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select" id="semester" name="semester" required>
                    <option value="">-- Pilih Semester --</option>
                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                </select>
            </div>
            
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bx bx-search me-1"></i> Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>
</div>

@if($reportData)
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            Rapor Akademik: {{ $reportData['student']->name }}
        </h5>
        <div>
            <form action="{{ route('academic-reports.export-pdf') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="student_id" value="{{ $reportData['student']->id }}">
                <input type="hidden" name="academic_year" value="{{ $reportData['academic_year'] }}">
                <input type="hidden" name="semester" value="{{ $reportData['semester'] }}">
                @if(request('subject_id'))
                    <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                @endif
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bx bxs-file-pdf me-1"></i> Export PDF
                </button>
            </form>
            <a href="{{ route('academic-reports.class-ranking', ['class_id' => request('class_id'), 'academic_year' => request('academic_year'), 'semester' => request('semester')]) }}" class="btn btn-info btn-sm text-white ms-2">
                <i class="bx bx-bar-chart-alt-2 me-1"></i> Lihat Peringkat Kelas
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr>
                        <th width="150">Nama Siswa</th>
                        <td width="20">:</td>
                        <td>{{ $reportData['student']->name }}</td>
                    </tr>
                    <tr>
                        <th>NIS/NISN</th>
                        <td>:</td>
                        <td>{{ $reportData['student']->nis ?? 'N/A' }}/{{ $reportData['student']->nisn ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>:</td>
                        <td>{{ $reportData['student']->class->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr>
                        <th width="150">Tahun Akademik</th>
                        <td width="20">:</td>
                        <td>{{ $reportData['academic_year'] }}</td>
                    </tr>
                    <tr>
                        <th>Semester</th>
                        <td>:</td>
                        <td>{{ $reportData['semester'] }}</td>
                    </tr>
                    <tr>
                        <th>Nilai Rata-rata</th>
                        <td>:</td>
                        <td>
                            <strong>{{ number_format($reportData['overall_average'], 2) }}</strong>
                            <span class="badge 
                                @if($reportData['overall_letter_grade'] == 'A') bg-success
                                @elseif($reportData['overall_letter_grade'] == 'B') bg-primary
                                @elseif($reportData['overall_letter_grade'] == 'C') bg-info
                                @elseif($reportData['overall_letter_grade'] == 'D') bg-warning
                                @else bg-danger @endif ms-2">
                                {{ $reportData['overall_letter_grade'] }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="accordion" id="reportAccordion">
            @forelse($reportData['subjects'] as $subjectName => $subjectData)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($subjectName) }}">
                            <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                <strong>{{ $subjectName }}</strong>
                                <div>
                                    <span class="badge 
                                        @if($reportData['overall_letter_grade'] == 'A') bg-success
                                        @elseif($reportData['overall_letter_grade'] == 'B') bg-primary
                                        @elseif($reportData['overall_letter_grade'] == 'C') bg-info
                                        @elseif($reportData['overall_letter_grade'] == 'D') bg-warning
                                        @else bg-danger @endif">
                                        {{ number_format($subjectData['weighted_average'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse{{ Str::slug($subjectName) }}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Bobot</th>
                                            <th>Nilai Rata-rata</th>
                                            <th>Grade</th>
                                            <th>Nilai Tertimbang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subjectData['categories'] as $categoryName => $categoryData)
                                            <tr>
                                                <td>{{ $categoryName }}</td>
                                                <td>{{ $categoryData['weight'] }}%</td>
                                                <td>{{ number_format($categoryData['average'], 2) }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($categoryData['letter_grade'] == 'A') bg-success
                                                        @elseif($categoryData['letter_grade'] == 'B') bg-primary
                                                        @elseif($categoryData['letter_grade'] == 'C') bg-info
                                                        @elseif($categoryData['letter_grade'] == 'D') bg-warning
                                                        @else bg-danger @endif">
                                                        {{ $categoryData['letter_grade'] }}
                                                    </span>
                                                </td>
                                                <td>{{ number_format($categoryData['weighted_score'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light fw-bold">
                                            <td colspan="2">Total</td>
                                            <td colspan="2">Bobot Total: {{ $subjectData['total_weight'] }}%</td>
                                            <td>{{ number_format($subjectData['total_score'], 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <h6 class="mt-4 mb-3">Detail Penilaian</h6>
                            @foreach($subjectData['categories'] as $categoryName => $categoryData)
                                <h6 class="card-subtitle mb-3">{{ $categoryName }}</h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Item</th>
                                                <th>Tanggal</th>
                                                <th>Nilai Maksimal</th>
                                                <th>Nilai</th>
                                                <th>Persentase</th>
                                                <th>Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($categoryData['items'] as $item)
                                                <tr>
                                                    <td>{{ $item['name'] }}</td>
                                                    <td>{{ $item['date'] }}</td>
                                                    <td>{{ $item['max_score'] }}</td>
                                                    <td>{{ $item['score'] ?? 'Belum dinilai' }}</td>
                                                    <td>{{ number_format($item['percentage'], 2) }}%</td>
                                                    <td>
                                                        @if($item['score'])
                                                            <span class="badge 
                                                                @if($item['letter_grade'] == 'A') bg-success
                                                                @elseif($item['letter_grade'] == 'B') bg-primary
                                                                @elseif($item['letter_grade'] == 'C') bg-info
                                                                @elseif($item['letter_grade'] == 'D') bg-warning
                                                                @else bg-danger @endif">
                                                                {{ $item['letter_grade'] }}
                                                            </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="bx bx-info-circle me-1"></i> Tidak ada data nilai yang ditemukan untuk mata pelajaran yang dipilih.
                </div>
            @endforelse
        </div>
    </div>
</div>
@elseif(request('class_id') && request('student_id') && request('academic_year') && request('semester'))
<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bx bx-search-alt text-muted" style="font-size: 3rem;"></i>
        <h5 class="mt-3">Tidak ada data nilai yang ditemukan</h5>
        <p class="text-muted">Data nilai tidak ditemukan untuk siswa dan periode yang dipilih.</p>
    </div>
</div>
@else
<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bx bx-file text-muted" style="font-size: 3rem;"></i>
        <h5 class="mt-3">Silahkan Pilih Filter</h5>
        <p class="text-muted">Pilih kelas, siswa, tahun akademik dan semester untuk melihat laporan nilai.</p>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2 if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('#class_id, #student_id, #subject_id, #academic_year, #semester').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
        
        // Handle class selection to load students
        const classSelect = document.getElementById('class_id');
        const studentSelect = document.getElementById('student_id');
        
        if (classSelect && studentSelect) {
            classSelect.addEventListener('change', function() {
                const classId = this.value;
                
                if (!classId) {
                    studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
                    studentSelect.disabled = true;
                    return;
                }
                
                // Fetch students from the selected class
                fetch(`/api/classes/${classId}/students`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch students');
                        }
                        return response.json();
                    })
                    .then(data => {
                        studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
                        
                        if (data && data.length > 0) {
                            data.forEach(student => {
                                const option = document.createElement('option');
                                option.value = student.id;
                                option.textContent = `${student.name} (${student.nis || 'No NIS'})`;
                                studentSelect.appendChild(option);
                            });
                            
                            studentSelect.disabled = false;
                        } else {
                            studentSelect.innerHTML = '<option value="">Tidak ada siswa di kelas ini</option>';
                            studentSelect.disabled = true;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching students:', error);
                        studentSelect.innerHTML = '<option value="">Error loading students</option>';
                        studentSelect.disabled = true;
                    });
            });
        }
    });
</script>
@endsection
