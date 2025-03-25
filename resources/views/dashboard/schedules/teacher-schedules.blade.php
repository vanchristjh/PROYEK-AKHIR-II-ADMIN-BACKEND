@extends('layouts.dashboard')

@section('page-title', 'Jadwal Guru')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('teacher-schedules.weekly') }}" class="btn btn-light btn-sm me-2">
        <i class="bx bx-calendar-week me-1"></i> Tampilan Mingguan
    </a>
    <a href="{{ route('schedules.create') }}?teacher_form=1" class="btn btn-primary btn-sm">
        <i class="bx bx-plus me-1"></i> Tambah Jadwal Guru
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Jadwal Mengajar Guru</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-export me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="#"><i class="bx bxs-file-pdf me-1 text-danger"></i> Export PDF</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bx bxs-file-excel me-1 text-success"></i> Export Excel</a></li>
                        <li><a class="dropdown-item" href="#" onclick="window.print()"><i class="bx bx-printer me-1 text-primary"></i> Print</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <!-- Teacher Filter Form -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Pilih Guru</h6>
                                <form action="{{ route('teacher-schedules.index') }}" method="GET" class="row g-3">
                                    <div class="col-md-8">
                                        <label for="teacher_id" class="form-label">Guru</label>
                                        <select class="form-select" id="teacher_id" name="teacher_id">
                                            @foreach($teachers as $t)
                                                <option value="{{ $t->id }}" {{ $teacher && $teacher->id == $t->id ? 'selected' : '' }}>
                                                    {{ $t->name }} {{ $t->subject ? '('.$t->subject.')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-filter-alt me-1"></i> Tampilkan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Teacher Profile Card -->
                <div class="card mb-4 border">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=0D6EFD&color=fff&size=80" 
                                     class="rounded-circle" alt="{{ $teacher->name }}" width="80">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fw-bold mb-1">{{ $teacher->name }}</h5>
                                <p class="text-muted mb-1">
                                    {{ $teacher->subject ?? 'Mata Pelajaran: (Belum disetel)' }}
                                    @if($teacher->position)
                                        <span class="badge bg-info ms-2">{{ $teacher->position }}</span>
                                    @endif
                                </p>
                                <div class="d-flex flex-wrap small text-muted">
                                    @if($teacher->nip)
                                        <div class="me-3"><i class="bx bx-id-card me-1"></i> NIP: {{ $teacher->nip }}</div>
                                    @endif
                                    <div class="me-3"><i class="bx bx-envelope me-1"></i> {{ $teacher->email }}</div>
                                    @if($teacher->phone_number)
                                        <div class="me-3"><i class="bx bx-phone me-1"></i> {{ $teacher->phone_number }}</div>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-primary">{{ $schedules->count() }} Jadwal Mengajar</span>
                                    <a href="{{ route('teacher-schedules.weekly') }}?teacher_id={{ $teacher->id }}" class="badge bg-light text-primary border border-primary ms-1">
                                        <i class="bx bx-calendar-week"></i> Lihat Jadwal Mingguan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule List -->
                @if($schedules->isEmpty())
                    <div class="text-center py-5">
                        <img src="https://via.placeholder.com/150" alt="No schedules" class="img-fluid mb-3 opacity-50" style="max-width: 120px;">
                        <h5 class="text-muted">Tidak ada jadwal untuk guru ini</h5>
                        <p class="text-muted">Guru ini belum memiliki jadwal yang ditambahkan.</p>
                        <a href="{{ route('schedules.create') }}?teacher_id={{ $teacher->id }}&teacher_form=1" class="btn btn-primary mt-2">
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
                                    {{ $day === 'monday' ? 'Senin' : 
                                       ($day === 'tuesday' ? 'Selasa' : 
                                       ($day === 'wednesday' ? 'Rabu' : 
                                       ($day === 'thursday' ? 'Kamis' : 
                                       ($day === 'friday' ? 'Jumat' : 
                                       ($day === 'saturday' ? 'Sabtu' : 'Minggu'))))) }}
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
                                                    <th>Ruangan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($schedulesByDay[$day]->sortBy('start_time') as $schedule)
                                                    <tr>
                                                        <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                                        <td>{{ $schedule->subject }}</td>
                                                        <td>{{ $schedule->class ? $schedule->class->name : '-' }}</td>
                                                        <td>{{ $schedule->room ?: '-' }}</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-outline-primary" title="Detail">
                                                                    <i class="bx bx-show"></i>
                                                                </a>
                                                                <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-outline-secondary" title="Edit">
                                                                    <i class="bx bx-edit"></i>
                                                                </a>
                                                                <button type="button" class="btn btn-outline-danger" 
                                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                                        data-id="{{ $schedule->id }}"
                                                                        data-subject="{{ $schedule->subject }}"
                                                                        title="Hapus">
                                                                    <i class="bx bx-trash"></i>
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
    </div>
    
    <div class="col-lg-3">
        <!-- Teacher Schedule Summary -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Ringkasan Jadwal</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @php
                        $dayCount = $schedules->groupBy('day_of_week')->count();
                        $totalHours = 0;
                        foreach ($schedules as $schedule) {
                            $start = $schedule->start_time;
                            $end = $schedule->end_time;
                            $totalHours += $start->diffInMinutes($end) / 60;
                        }
                        $classes = $schedules->pluck('class.name')->filter()->unique();
                    @endphp
                    
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bx bx-calendar-check me-2 text-primary"></i> Jumlah Hari Mengajar
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $dayCount }} hari</span>
                    </div>
                    
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bx bx-time me-2 text-primary"></i> Total Jam Mengajar
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ number_format($totalHours, 1) }} jam</span>
                    </div>
                    
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bx bxs-school me-2 text-primary"></i> Jumlah Kelas Diajar
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $classes->count() }} kelas</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Classes Taught -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Kelas yang Diajar</h5>
            </div>
            <div class="card-body p-0">
                @if($classes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($classes as $className)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bx bxs-school me-2 text-success"></i> {{ $className }}
                                </div>
                                @php
                                    $classSchedules = $schedules->filter(function($schedule) use ($className) {
                                        return $schedule->class && $schedule->class->name === $className;
                                    });
                                @endphp
                                <span class="badge bg-success rounded-pill">{{ $classSchedules->count() }} jadwal</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Belum ada kelas yang diajar</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Actions -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('schedules.create') }}?teacher_id={{ $teacher->id }}&teacher_form=1" class="btn btn-primary">
                        <i class="bx bx-plus-circle me-1"></i> Tambah Jadwal Baru
                    </a>
                    <a href="{{ route('teacher-schedules.weekly') }}?teacher_id={{ $teacher->id }}" class="btn btn-outline-primary">
                        <i class="bx bx-calendar-week me-1"></i> Lihat Jadwal Mingguan
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="bx bx-printer me-1"></i> Cetak Jadwal
                    </a>
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
        if (deleteModal) {
            const deleteForm = document.getElementById('deleteForm');
            const scheduleSubject = document.getElementById('scheduleSubject');
            
            document.querySelectorAll('button[data-bs-target="#deleteModal"]').forEach(button => {
                button.addEventListener('click', function() {
                    const scheduleId = this.getAttribute('data-id');
                    const subject = this.getAttribute('data-subject');
                    
                    deleteForm.action = `/schedules/${scheduleId}`;
                    scheduleSubject.textContent = subject;
                });
            });
        }
        
        // Auto-submit teacher filter
        const teacherSelect = document.getElementById('teacher_id');
        if (teacherSelect) {
            teacherSelect.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
    });
</script>
@endsection
