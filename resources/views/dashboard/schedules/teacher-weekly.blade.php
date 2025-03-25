@extends('layouts.dashboard')

@section('page-title', 'Jadwal Mingguan Guru')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('teacher-schedules.index') }}" class="btn btn-light btn-sm me-2">
        <i class="bx bx-list-ul me-1"></i> Tampilan Daftar
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
                        <h6 class="card-title mb-3">Pilih Guru</h6>
                        <form action="{{ route('teacher-schedules.weekly') }}" method="GET" class="row g-3">
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

        <!-- Teacher Info -->
        @if($teacher)
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
                                    <span class="badge bg-primary">{{ array_sum(array_map(function($day) { return array_sum(array_map(function($slot) { return count($slot); }, $day)); }, $weeklySchedule)) ?? 0 }} jadwal</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Weekly Schedule -->
        <div class="table-responsive">
            <table class="table table-bordered schedule-table">
                <thead>
                    <tr class="text-center bg-light">
                        <th scope="col" width="10%">Waktu</th>
                        <th scope="col" width="12.85%">Senin</th>
                        <th scope="col" width="12.85%">Selasa</th>
                        <th scope="col" width="12.85%">Rabu</th>
                        <th scope="col" width="12.85%">Kamis</th>
                        <th scope="col" width="12.85%">Jumat</th>
                        <th scope="col" width="12.85%">Sabtu</th>
                        <th scope="col" width="12.85%">Minggu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($timeSlots ?? [] as $timeSlot)
                        <tr>
                            <td class="bg-light text-center align-middle fw-bold">{{ $timeSlot }}</td>
                            
                            @foreach ($days ?? [] as $day)
                                <td class="schedule-cell">
                                    @if(isset($weeklySchedule[$day][$timeSlot]) && count($weeklySchedule[$day][$timeSlot]) > 0)
                                        @foreach ($weeklySchedule[$day][$timeSlot] as $schedule)
                                            <div class="schedule-item mb-2 p-2 border rounded bg-primary bg-opacity-10">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge bg-primary">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</span>
                                                    <a href="{{ route('schedules.show', $schedule) }}" class="text-primary">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                </div>
                                                <h6 class="mb-0 text-truncate">{{ $schedule->subject }}</h6>
                                                <div class="small">
                                                    @if($schedule->class)
                                                        <div class="text-truncate fw-bold">Kelas: {{ $schedule->class->name }}</div>
                                                    @endif
                                                    @if($schedule->room)
                                                        <div class="text-truncate text-muted"><i class="bx bx-map-pin"></i> {{ $schedule->room }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-2">
                                            <span class="text-muted small">-</span>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .schedule-table {
        table-layout: fixed;
    }
    
    .schedule-cell {
        height: 140px;
        vertical-align: top;
        padding: 0.5rem;
        overflow-y: auto;
    }
    
    .schedule-item {
        font-size: 0.85rem;
    }
    
    .schedule-table th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 1;
    }
</style>
@endsection
