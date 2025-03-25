@extends('layouts.dashboard')

@section('page-title', 'Jadwal Guru')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('teacher-schedules.weekly') }}" class="btn btn-light btn-sm me-2">
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
        <!-- Navigation Tabs -->
        <ul class="nav nav-pills nav-fill mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="#list-view" data-bs-toggle="tab">
                    <i class="bx bx-list-ul me-1"></i> Tampilan Daftar
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#weekly-view" data-bs-toggle="tab">
                    <i class="bx bx-calendar me-1"></i> Tampilan Mingguan
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="list-view">
                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Pilih Guru</h6>
                                <form action="{{ route('teacher-schedules.index') }}" method="GET" class="row g-3">
                                    <div class="col-md-6">
                                        <select class="form-select" id="teacher_id" name="teacher_id">
                                            @foreach ($teachers as $t)
                                                <option value="{{ $t->id }}" {{ $teacher && $teacher->id == $t->id ? 'selected' : '' }}>
                                                    {{ $t->name }}
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
                
                @if($teacher)
                <!-- Teacher Info -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 bg-primary bg-opacity-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 64px; height: 64px; font-size: 24px;">
                                            <i class="bx bxs-user-badge"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-0">{{ $teacher->name }}</h5>
                                        <p class="text-muted mb-0">{{ $teacher->email }}</p>
                                        <div class="mt-2">
                                            <span class="badge bg-primary">{{ $schedulesByDay->flatten()->count() ?? 0 }} jadwal</span>dwal</span>dwal</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule By Day -->
                <div class="schedule-tabs">
                    <ul class="nav nav-tabs" id="dayTabs" role="tablist">
                        @php
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
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $index => $day)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                        id="{{ $day }}-tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#{{ $day }}-content" 
                                        type="button" 
                                        role="tab">
                                    {{ $dayNames[$day] }}
                                    @if(isset($schedulesByDay[$day]))
                                        <span class="badge bg-primary ms-1">{{ count($schedulesByDay[$day]) }}</span>
                                    @endif
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="tab-content mt-3" id="dayTabsContent">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $index => $day)
                            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                                 id="{{ $day }}-content" 
                                 role="tabpanel" 
                                 aria-labelledby="{{ $day }}-tab">
                                
                                @if(isset($schedulesByDay[$day]))
                                    @include('dashboard.schedules._day_schedule', [
                                        'schedules' => $schedulesByDay[$day],
                                        'displayTeacher' => false,
                                        'dayNames' => $dayNames,
                                    ])
                                @else
                                    <div class="text-center py-5 my-4 bg-light rounded">
                                        <i class="bx bx-calendar-x text-secondary mb-2" style="font-size: 2.5rem;"></i>
                                        <p class="text-muted mb-0">Tidak ada jadwal untuk hari {{ strtolower($dayNames[$day]) }}.</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <img src="https://via.placeholder.com/150" alt="Select teacher" class="img-fluid mb-3 opacity-50" style="max-width: 150px;">
                    <h5 class="text-muted">Silakan Pilih Guru</h5>
                    <p class="text-muted">Pilih guru untuk melihat jadwal mengajar.</p>
                </div>
                @endif
            </div>
            
            <div class="tab-pane fade" id="weekly-view">
                @if($teacher)
                    <div class="text-center mb-4">
                        <h5>{{ $teacher->name }} - Jadwal Mingguan</h5>
                        <p class="text-muted">Menampilkan jadwal mengajar untuk semua hari</p>
                    </div>
                    
                    @include('dashboard.schedules._weekly_schedule', [
                        'weeklySchedule' => $weeklySchedule ?? [],
                        'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                        'timeSlots' => $timeSlots ?? ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00']
                    ])
                @else
                <div class="text-center py-5">
                    <img src="https://via.placeholder.com/150" alt="Select teacher" class="img-fluid mb-3 opacity-50" style="max-width: 150px;">
                    <h5 class="text-muted">Silakan Pilih Guru</h5>
                    <p class="text-muted">Pilih guru untuk melihat jadwal mengajar.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Redirect to weekly view when weekly tab is clicked
        document.querySelector('a[href="#weekly-view"]').addEventListener('click', function(e) {
            e.preventDefault();
            const teacherId = document.getElementById('teacher_id').value;
            window.location.href = "{{ route('teacher-schedules.weekly') }}?teacher_id=" + teacherId;
        });
    });
</script>
@endsection
