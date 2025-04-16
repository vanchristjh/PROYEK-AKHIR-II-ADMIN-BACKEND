@extends('layouts.dashboard')

@section('page-title', 'Input Nilai Siswa')

@section('page-actions')
<a href="{{ route('grade-items.show', $gradeItem) }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">
                    <i class="bx bx-info-circle text-primary me-2"></i>
                    Informasi Nilai
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">Mata Pelajaran</p>
                        <h6 class="fw-bold">{{ $gradeItem->gradeCategory->subject->name ?? 'N/A' }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">Kelas</p>
                        <h6 class="fw-bold">{{ $gradeItem->gradeCategory->class->name ?? 'N/A' }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">Kategori</p>
                        <h6 class="fw-bold">{{ $gradeItem->gradeCategory->name }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">Item Nilai</p>
                        <h6 class="fw-bold">{{ $gradeItem->name }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">Tanggal</p>
                        <h6 class="fw-bold">{{ $gradeItem->date->format('d M Y') }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">Nilai Maksimal</p>
                        <h6 class="fw-bold">{{ $gradeItem->max_score }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Tips Input Nilai:</h6>
                        <ul class="mb-0">
                            <li>Masukkan nilai dengan angka desimal yang sesuai (max {{ $gradeItem->max_score }})</li>
                            <li>Kosongkan nilai jika siswa belum mengikuti atau belum dinilai</li>
                            <li>Gunakan catatan untuk informasi tambahan (opsional)</li>
                            <li>Gunakan Tab untuk berpindah antar kolom dengan cepat</li>
                            <li>Klik "Simpan Nilai" setelah selesai melakukan input</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title">Input Nilai Siswa</h5>
            <div class="input-group" style="width: 300px;">
                <span class="input-group-text"><i class="bx bx-search"></i></span>
                <input type="text" class="form-control" id="studentSearch" placeholder="Cari siswa...">
            </div>
        </div>

        <form action="{{ route('grade-items.save-grades', $gradeItem) }}" method="POST">
            @csrf

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Foto</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th width="15%">Nilai</th>
                            <th width="30%">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                        <tr class="student-row">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($student->profile_photo)
                                    <img src="{{ asset('storage/'.$student->profile_photo) }}" alt="{{ $student->name }}" class="rounded-circle" width="40" height="40">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=2d4059&color=fff&size=40" alt="{{ $student->name }}" class="rounded-circle" width="40" height="40">
                                @endif
                            </td>
                            <td>{{ $student->nis ?? 'N/A' }}</td>
                            <td>{{ $student->name }}</td>
                            <td>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    class="form-control" 
                                    name="grades[{{ $index }}][student_id]" 
                                    value="{{ $student->id }}" 
                                    hidden
                                >
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    class="form-control" 
                                    name="grades[{{ $index }}][score]" 
                                    placeholder="0-{{ $gradeItem->max_score }}" 
                                    min="0" 
                                    max="{{ $gradeItem->max_score }}" 
                                    value="{{ $existingGrades[$student->id]->score ?? '' }}"
                                >
                            </td>
                            <td>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="grades[{{ $index }}][notes]" 
                                    placeholder="Catatan (opsional)" 
                                    value="{{ $existingGrades[$student->id]->notes ?? '' }}"
                                >
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Tidak ada siswa yang terdaftar di kelas ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <a href="{{ route('grade-items.show', $gradeItem) }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div>
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="bx bx-reset me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Simpan Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const studentSearch = document.getElementById('studentSearch');
        const studentRows = document.querySelectorAll('.student-row');
        
        if (studentSearch) {
            studentSearch.addEventListener('input', function() {
                const searchValue = this.value.toLowerCase();
                
                studentRows.forEach(row => {
                    const studentName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const studentNis = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    
                    if (studentName.includes(searchValue) || studentNis.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
