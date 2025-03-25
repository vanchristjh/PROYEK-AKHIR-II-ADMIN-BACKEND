@extends('layouts.dashboard')

@section('page-title', 'Tampilan Kalender Akademik')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('academic-calendar.index') }}" class="btn btn-light btn-sm me-2">
        <i class="bx bx-list-ul me-1"></i> Tampilan Daftar
    </a>
    <a href="{{ route('academic-calendar.create') }}" class="btn btn-primary btn-sm">
        <i class="bx bx-plus me-1"></i> Tambah Agenda
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- Month Navigation -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('academic-calendar.calendar', ['month' => $month == 1 ? 12 : $month - 1, 'year' => $month == 1 ? $year - 1 : $year]) }}" class="btn btn-outline-secondary">
                <i class="bx bx-chevron-left"></i> Bulan Sebelumnya
            </a>
            
            <h4 class="mb-0">{{ $firstDay->format('F Y') }}</h4>
            
            <a href="{{ route('academic-calendar.calendar', ['month' => $month == 12 ? 1 : $month + 1, 'year' => $month == 12 ? $year + 1 : $year]) }}" class="btn btn-outline-secondary">
                Bulan Berikutnya <i class="bx bx-chevron-right"></i>
            </a>
        </div>

        <!-- Year Navigation -->
        <div class="mb-4 d-flex justify-content-center">
            <div class="btn-group">
                @for($i = $year - 2; $i <= $year + 2; $i++)
                    <a href="{{ route('academic-calendar.calendar', ['month' => $month, 'year' => $i]) }}" 
                       class="btn {{ $i == $year ? 'btn-primary' : 'btn-outline-secondary' }}">
                        {{ $i }}
                    </a>
                @endfor
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="table-responsive">
            <table class="table table-bordered calendar-table">
                <thead>
                    <tr class="text-center bg-light">
                        <th scope="col">Minggu</th>
                        <th scope="col">Senin</th>
                        <th scope="col">Selasa</th>
                        <th scope="col">Rabu</th>
                        <th scope="col">Kamis</th>
                        <th scope="col">Jumat</th>
                        <th scope="col">Sabtu</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $dayOfWeek = $firstDay->copy()->startOfMonth()->dayOfWeek;
                        $dayOfWeek = $dayOfWeek == 0 ? 7 : $dayOfWeek; // Adjust to make Sunday = 7
                        $daysInPreviousMonth = $firstDay->copy()->subMonth()->daysInMonth;
                        $currentDay = 1;
                        $nextMonthDay = 1;
                    @endphp

                    <!-- Generate calendar rows -->
                    @for($i = 0; $i < 6; $i++)
                        @if($currentDay <= $daysInMonth)
                            <tr style="min-height: 100px;">
                                @for($j = 1; $j <= 7; $j++)
                                    @if($i == 0 && $j < $dayOfWeek)
                                        <!-- Previous month days -->
                                        <td class="text-muted bg-light">
                                            <div class="calendar-date text-end">{{ $daysInPreviousMonth - $dayOfWeek + $j + 1 }}</div>
                                        </td>
                                    @elseif($currentDay > $daysInMonth)
                                        <!-- Next month days -->
                                        <td class="text-muted bg-light">
                                            <div class="calendar-date text-end">{{ $nextMonthDay++ }}</div>
                                        </td>
                                    @else
                                        <!-- Current month days -->
                                        <td class="{{ $currentDay == now()->day && $month == now()->month && $year == now()->year ? 'today bg-light' : '' }}">
                                            <div class="calendar-date text-end {{ $j == 7 ? 'text-danger' : '' }}">{{ $currentDay }}</div>
                                            
                                            <!-- Events for this day -->
                                            @if(isset($eventsByDay[$currentDay]))
                                                <div class="calendar-events">
                                                    @foreach($eventsByDay[$currentDay] as $event)
                                                        <a href="{{ route('academic-calendar.show', $event) }}" class="calendar-event d-block mb-1 p-1 rounded text-truncate 
                                                            @if($event->event_type == 'exam') bg-danger text-white
                                                            @elseif($event->event_type == 'holiday') bg-success text-white
                                                            @elseif($event->event_type == 'academic') bg-primary text-white
                                                            @elseif($event->event_type == 'meeting') bg-info text-white
                                                            @elseif($event->event_type == 'extracurricular') bg-warning
                                                            @else bg-secondary text-white @endif"
                                                            data-bs-toggle="tooltip" title="{{ $event->title }}">
                                                            <small>{{ $event->title }}</small>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        @php $currentDay++; @endphp
                                    @endif
                                @endfor
                            </tr>
                        @endif
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Legend -->
        <div class="mt-4">
            <h6 class="mb-2">Keterangan:</h6>
            <div class="d-flex flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded me-2" style="width: 20px; height: 20px;"></div>
                    <span>Akademik</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-danger rounded me-2" style="width: 20px; height: 20px;"></div>
                    <span>Ujian</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-success rounded me-2" style="width: 20px; height: 20px;"></div>
                    <span>Libur</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-info rounded me-2" style="width: 20px; height: 20px;"></div>
                    <span>Rapat</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-warning rounded me-2" style="width: 20px; height: 20px;"></div>
                    <span>Ekstrakurikuler</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-secondary rounded me-2" style="width: 20px; height: 20px;"></div>
                    <span>Lainnya</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .calendar-table td {
        height: 120px;
        vertical-align: top;
        padding: 0.5rem;
        position: relative;
    }
    
    .calendar-date {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .calendar-events {
        overflow-y: auto;
        max-height: 80px;
    }
    
    .calendar-event {
        text-decoration: none;
        font-size: 0.85rem;
    }
    
    .today {
        position: relative;
    }
    
    .today .calendar-date {
        color: #0066b3;
        font-weight: bold;
    }
    
    .today::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 2px solid #0066b3;
        pointer-events: none;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });
</script>
@endsection
