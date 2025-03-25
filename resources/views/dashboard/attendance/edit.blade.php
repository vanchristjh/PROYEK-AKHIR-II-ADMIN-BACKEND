@extends('layouts.dashboard')

@section('page-title', 'Isi Absensi')

@section('page-actions')
<a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Session Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Kelas</p>
                                <h6>{{ $session->class->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Mata Pelajaran</p>
                                <h6>{{ $session->subject->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Tanggal</p>
                                <h6>{{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-1 text-muted small">Waktu</p>
                                <h6>{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('attendance.update', $session->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nama Siswa</th>
                            <th>NIS</th>
                            <th style="width: 150px;">Status</th>
                            <th style="width: 250px;">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students ?? [] as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->nis ?? '-' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <input type="hidden" name="student_id[]" value="{{ $student->id }}">
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="hadir-{{ $student->id }}" value="hadir" {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == 'hadir') ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-success btn-sm" for="hadir-{{ $student->id }}">H</label>
                                    
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="izin-{{ $student->id }}" value="izin" {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == 'izin') ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-warning btn-sm" for="izin-{{ $student->id }}">I</label>
                                    
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="sakit-{{ $student->id }}" value="sakit" {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == 'sakit') ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-info btn-sm" for="sakit-{{ $student->id }}">S</label>
                                    
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="alpa-{{ $student->id }}" value="alpa" {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == 'alpa') ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-danger btn-sm" for="alpa-{{ $student->id }}">A</label>
                                    
                                    <input type="radio" class="btn-check" name="status[{{ $student->id }}]" id="terlambat-{{ $student->id }}" value="terlambat" {{ (isset($attendances[$student->id]) && $attendances[$student->id]->status == 'terlambat') ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-secondary btn-sm" for="terlambat-{{ $student->id }}">T</label>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="notes[{{ $student->id }}]" placeholder="Catatan (opsional)" value="{{ isset($attendances[$student->id]) ? $attendances[$student->id]->notes : '' }}">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Tidak ada siswa dalam kelas ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <div class="d-flex justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_completed" name="is_completed" value="1" {{ $session->is_completed ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_completed">
                            Tandai sebagai selesai
                        </label>
                        <div class="small text-muted">Absensi yang ditandai selesai tidak dapat diubah lagi.</div>
                    </div>
                    
                    <div>
                        <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="bx bx-x me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Absensi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quick actions for attendance
        const selectAllHadir = document.getElementById('select-all-hadir');
        
        if (selectAllHadir) {
            selectAllHadir.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('[id^="hadir-"]').forEach(radio => {
                    radio.checked = true;
                });
            });
        }
    });
</script>
@endsection
