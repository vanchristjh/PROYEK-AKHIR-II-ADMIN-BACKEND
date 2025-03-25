@extends('layouts.dashboard')

@section('page-title', 'Jadwal Pelajaran')

@php
    // Define day names mapping for translations
    $dayNames = [
        'monday' => 'Senin',
        'tuesday' => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday' => 'Kamis',
        'friday' => 'Jumat',
        'saturday' => 'Sabtu',
        'sunday' => 'Minggu',
    ];
@endphp

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('schedules.weekly') }}" class="btn btn-light btn-sm me-2">
        <i class="bx bx-calendar-week me-1"></i> Tampilan Mingguan
    </a>
    <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-sm">
        <i class="bx bx-plus me-1"></i> Tambah Jadwal
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Filter Jadwal</h6>
                        <form action="{{ route('schedules.index') }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label for="class_id" class="form-label">Kelas</label>
                                <select class="form-select" id="class_id" name="class_id">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="teacher_id" class="form-label">Guru</label>
                                <select class="form-select" id="teacher_id" name="teacher_id">
                                    <option value="">Semua Guru</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="day" class="form-label">Hari</label>
                                <select class="form-select" id="day" name="day">
                                    <option value="">Semua Hari</option>
                                    <option value="monday" {{ request('day') == 'monday' ? 'selected' : '' }}>Senin</option>
                                    <option value="tuesday" {{ request('day') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                                    <option value="wednesday" {{ request('day') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                                    <option value="thursday" {{ request('day') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                                    <option value="friday" {{ request('day') == 'friday' ? 'selected' : '' }}>Jumat</option>
                                    <option value="saturday" {{ request('day') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                                    <option value="sunday" {{ request('day') == 'sunday' ? 'selected' : '' }}>Minggu</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="subject" class="form-label">Mata Pelajaran</label>
                                <input type="text" class="form-control" id="subject" name="subject" value="{{ request('subject') }}">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter-alt me-1"></i> Filter
                                </button>
                                <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule List -->
        @if($schedules->isEmpty())
            <div class="text-center py-5">
                <img src="https://via.placeholder.com/150" alt="No schedules" class="img-fluid mb-3 opacity-50" style="max-width: 150px;">
                <h5 class="text-muted">Tidak ada jadwal yang ditemukan</h5>
                <p class="text-muted">Tidak ada jadwal yang sesuai dengan filter yang dipilih atau belum ada jadwal yang ditambahkan.</p>
                <a href="{{ route('schedules.create') }}" class="btn btn-primary mt-2">
                    <i class="bx bx-plus me-1"></i> Tambah Jadwal Baru
                </a>
            </div>
        @else
            <ul class="nav nav-tabs mb-3" id="scheduleTabs" role="tablist">
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $index => $day)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                id="{{ $day }}-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#{{ $day }}-tab-pane" 
                                type="button" 
                                role="tab" 
                                aria-controls="{{ $day }}-tab-pane" 
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                            {{ $dayNames[$day] }}
                            @if(isset($schedulesByDay[$day]))
                                <span class="badge bg-primary ms-1">{{ count($schedulesByDay[$day]) }}</span>
                            @endif
                        </button>
                    </li>
                @endforeach
            </ul>
            
            <div class="tab-content" id="scheduleTabContent">
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $index => $day)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                         id="{{ $day }}-tab-pane" 
                         role="tabpanel" 
                         aria-labelledby="{{ $day }}-tab" 
                         tabindex="0">
                        
                        @if(isset($schedulesByDay[$day]) && count($schedulesByDay[$day]) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Kelas</th>
                                            <th>Guru</th>
                                            <th>Ruangan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($schedulesByDay[$day]->sortBy('start_time') as $schedule)
                                            <tr>
                                                <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                                <td>{{ $schedule->subject }}</td>
                                                <td>{{ $schedule->class ? $schedule->class->name : '-' }}</td>
                                                <td>{{ $schedule->teacher ? $schedule->teacher->name : '-' }}</td>
                                                <td>{{ $schedule->room ?: '-' }}</td>
                                                <td>
                                                    @if($schedule->is_active)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-light" title="Detail">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-light" title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-light delete-btn" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal" 
                                                                data-id="{{ $schedule->id }}"
                                                                data-subject="{{ $schedule->subject }}"
                                                                title="Hapus">
                                                            <i class="bx bx-trash text-danger"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">Tidak ada jadwal untuk hari ini.</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Hapus Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus jadwal mata pelajaran <span id="scheduleSubject" class="fw-bold"></span>?</p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete modal
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            const deleteForm = document.getElementById('deleteForm');
            const scheduleSubject = document.getElementById('scheduleSubject');
            
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const scheduleId = this.getAttribute('data-id');
                    const subject = this.getAttribute('data-subject');
                    
                    deleteForm.action = `/schedules/${scheduleId}`;
                    scheduleSubject.textContent = subject;
                });
            });
        }
    });
</script>
@endsection
