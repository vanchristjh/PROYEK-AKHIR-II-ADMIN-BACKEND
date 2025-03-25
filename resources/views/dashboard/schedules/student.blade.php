@extends('layouts.dashboard')

@section('page-title', 'Jadwal Kelas')

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
    <a href="{{ route('student-schedules.weekly') }}" class="btn btn-light btn-sm me-2">
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
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Pilih Kelas</h6>
                        <form action="{{ route('student-schedules.index') }}" method="GET" class="row g-3">
                            <div class="col-md-6">
                                <select class="form-select" id="class_id" name="class_id">
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $class && $class->id == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter-alt me-1"></i> Tampilkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Info -->
        @if($class)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0 bg-success bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" 
                                     style="width: 64px; height: 64px; font-size: 24px;">
                                    <i class="bx bxs-school"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ $class->name }}</h5>
                                <p class="text-muted mb-0">{{ $class->level }}</p>
                                <div class="text-muted small mt-1">
                                    <span class="badge bg-success">{{ $schedules->count() }} jadwal</span>
                                    @if($class->students_count)
                                    <span class="badge bg-primary ms-1">{{ $class->students_count }} siswa</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Schedule List -->
        @if($schedules->isEmpty())
            <div class="text-center py-5">
                <img src="https://via.placeholder.com/150" alt="No schedules" class="img-fluid mb-3 opacity-50" style="max-width: 150px;">
                <h5 class="text-muted">Tidak ada jadwal untuk kelas ini</h5>
                <p class="text-muted">Kelas ini belum memiliki jadwal yang ditambahkan.</p>
                <a href="{{ route('schedules.create') }}?class_id={{ $class ? $class->id : '' }}" class="btn btn-primary mt-2">
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
                                            <th>Guru</th>
                                            <th>Ruangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($schedulesByDay[$day]->sortBy('start_time') as $schedule)
                                            <tr>
                                                <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                                <td>{{ $schedule->subject }}</td>
                                                <td>{{ $schedule->teacher ? $schedule->teacher->name : '-' }}</td>
                                                <td>{{ $schedule->room ?: '-' }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-light" title="Detail">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-light" title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
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
@endsection
