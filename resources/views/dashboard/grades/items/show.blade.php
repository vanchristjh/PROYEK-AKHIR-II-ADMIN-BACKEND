@extends('layouts.dashboard')

@section('page-title', 'Detail Item Nilai')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('grade-items.enter-grades', $gradeItem) }}" class="btn btn-primary btn-sm me-2">
        <i class="bx bx-pencil me-1"></i> Input Nilai
    </a>
    <a href="{{ route('grade-items.index') }}" class="btn btn-secondary btn-sm">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Informasi Item Nilai</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Nama Item</span>
                        <span class="fw-medium">{{ $gradeItem->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Kategori</span>
                        <span class="fw-medium">{{ $gradeItem->gradeCategory->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Bobot Kategori</span>
                        <span class="fw-medium">{{ $gradeItem->gradeCategory->weight }}%</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Mata Pelajaran</span>
                        <span class="fw-medium">{{ $gradeItem->gradeCategory->subject->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Kelas</span>
                        <span class="fw-medium">{{ $gradeItem->gradeCategory->class->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Tanggal</span>
                        <span class="fw-medium">{{ $gradeItem->date->format('d M Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Nilai Maksimal</span>
                        <span class="fw-medium">{{ $gradeItem->max_score }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Tahun Akademik</span>
                        <span class="fw-medium">{{ $gradeItem->gradeCategory->academic_year }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Semester</span>
                        <span class="fw-medium">{{ $gradeItem->gradeCategory->semester }}</span>
                    </li>
                    <li class="list-group-item px-0">
                        <span class="text-muted">Deskripsi</span>
                        <p class="mb-0 mt-1">{{ $gradeItem->description ?? 'Tidak ada deskripsi' }}</p>
                    </li>
                </ul>
            </div>
            <div class="card-footer bg-white">
                <div class="d-grid gap-2">
                    <a href="{{ route('grade-items.edit', $gradeItem) }}" class="btn btn-outline-primary">
                        <i class="bx bx-edit me-1"></i> Edit Item Nilai
                    </a>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bx bx-trash me-1"></i> Hapus Item Nilai
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Statistik Nilai</h5>
                <a href="{{ route('grade-items.enter-grades', $gradeItem) }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-pencil me-1"></i> Input Nilai
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <h6 class="text-muted mb-1">Rata-rata Kelas</h6>
                            <h3 class="mb-0">{{ number_format($gradeItem->averageScore, 2) }}</h3>
                            <small class="text-muted">dari {{ $gradeItem->max_score }} nilai maksimal</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <h6 class="text-muted mb-1">Nilai Tertinggi</h6>
                            <h3 class="mb-0">{{ number_format($gradeItem->highestScore, 2) }}</h3>
                            <small class="text-muted">dari {{ $gradeItem->max_score }} nilai maksimal</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <h6 class="text-muted mb-1">Nilai Terendah</h6>
                            <h3 class="mb-0">{{ number_format($gradeItem->lowestScore, 2) }}</h3>
                            <small class="text-muted">dari {{ $gradeItem->max_score }} nilai maksimal</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6>Distribusi Nilai</h6>
                    <div class="progress" style="height: 20px;">
                        @php
                            $grades = $gradeItem->studentGrades;
                            $total = $grades->count();
                            $gradeA = $grades->filter(function($grade) { return ($grade->score / $grade->gradeItem->max_score) * 100 >= 90; })->count();
                            $gradeB = $grades->filter(function($grade) { return ($grade->score / $grade->gradeItem->max_score) * 100 >= 80 && ($grade->score / $grade->gradeItem->max_score) * 100 < 90; })->count();
                            $gradeC = $grades->filter(function($grade) { return ($grade->score / $grade->gradeItem->max_score) * 100 >= 70 && ($grade->score / $grade->gradeItem->max_score) * 100 < 80; })->count();
                            $gradeD = $grades->filter(function($grade) { return ($grade->score / $grade->gradeItem->max_score) * 100 >= 60 && ($grade->score / $grade->gradeItem->max_score) * 100 < 70; })->count();
                            $gradeE = $grades->filter(function($grade) { return ($grade->score / $grade->gradeItem->max_score) * 100 < 60; })->count();
                            
                            $aPercent = $total > 0 ? ($gradeA / $total) * 100 : 0;
                            $bPercent = $total > 0 ? ($gradeB / $total) * 100 : 0;
                            $cPercent = $total > 0 ? ($gradeC / $total) * 100 : 0;
                            $dPercent = $total > 0 ? ($gradeD / $total) * 100 : 0;
                            $ePercent = $total > 0 ? ($gradeE / $total) * 100 : 0;
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
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Nilai</th>
                                <th>Persentase</th>
                                <th>Grade</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gradeItem->studentGrades->sortByDesc('score') as $index => $grade)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $grade->student->nis ?? 'N/A' }}</td>
                                <td>{{ $grade->student->name }}</td>
                                <td>{{ number_format($grade->score, 2) }}</td>
                                <td>{{ number_format(($grade->score / $gradeItem->max_score) * 100, 1) }}%</td>
                                <td>
                                    <span class="badge 
                                        @if($grade->letterGrade == 'A') bg-success
                                        @elseif($grade->letterGrade == 'B') bg-primary
                                        @elseif($grade->letterGrade == 'C') bg-info
                                        @elseif($grade->letterGrade == 'D') bg-warning
                                        @else bg-danger @endif">
                                        {{ $grade->letterGrade }}
                                    </span>
                                </td>
                                <td>{{ $grade->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada nilai yang dimasukkan
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus item nilai <strong>{{ $gradeItem->name }}</strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan dan akan menghapus semua nilai siswa terkait.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('grade-items.destroy', $gradeItem) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
