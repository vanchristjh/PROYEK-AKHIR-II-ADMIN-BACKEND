@extends('layouts.dashboard')

@section('page-title', 'Tampilan Kalender Akademik')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('academic-calendar.index') }}" class="btn btn-light btn-sm me-2 d-flex align-items-center">
        <i class="bx bx-list-ul me-1"></i> Tampilan Daftar
    </a>
    <a href="{{ route('academic-calendar.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
        <i class="bx bx-plus me-1"></i> Tambah Agenda
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- Month Navigation -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('academic-calendar.calendar', ['month' => $month == 1 ? 12 : $month - 1, 'year' => $month == 1 ? $year - 1 : $year]) }}" class="btn btn-outline-secondary month-nav-btn">
                <i class="bx bx-chevron-left"></i> Bulan Sebelumnya
            </a>
            
            <h4 class="mb-0 current-month-title">{{ $firstDay->format('F Y') }}</h4>
            
            <a href="{{ route('academic-calendar.calendar', ['month' => $month == 12 ? 1 : $month + 1, 'year' => $month == 12 ? $year + 1 : $year]) }}" class="btn btn-outline-secondary month-nav-btn">
                Bulan Berikutnya <i class="bx bx-chevron-right"></i>
            </a>
        </div>

        <!-- Year Navigation -->
        <div class="mb-4 d-flex justify-content-center">
            <div class="btn-group year-nav">
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
                <div class="d-flex align-items-center legend-item">
                    <div class="bg-primary rounded me-2 legend-color"></div>
                    <span>Akademik</span>
                </div>
                <div class="d-flex align-items-center legend-item">
                    <div class="bg-danger rounded me-2 legend-color"></div>
                    <span>Ujian</span>
                </div>
                <div class="d-flex align-items-center legend-item">
                    <div class="bg-success rounded me-2 legend-color"></div>
                    <span>Libur</span>
                </div>
                <div class="d-flex align-items-center legend-item">
                    <div class="bg-info rounded me-2 legend-color"></div>
                    <span>Rapat</span>
                </div>
                <div class="d-flex align-items-center legend-item">
                    <div class="bg-warning rounded me-2 legend-color"></div>
                    <span>Ekstrakurikuler</span>
                </div>
                <div class="d-flex align-items-center legend-item">
                    <div class="bg-secondary rounded me-2 legend-color"></div>
                    <span>Lainnya</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .calendar-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .calendar-table th, 
    .calendar-table td {
        border: 1px solid #e9ecef;
        width: 14.28%;
    }
    
    .calendar-table th {
        background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
        color: white;
        font-weight: 500;
        text-align: center;
        padding: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.9rem;
    }
    
    .calendar-table td {
        height: 130px;
        vertical-align: top;
        padding: 0.5rem;
        position: relative;
        background-color: #fff;
        transition: background-color 0.2s;
    }
    
    .calendar-table td:hover {
        background-color: #f8f9fa;
    }
    
    .calendar-date {
        font-weight: bold;
        margin-bottom: 8px;
        font-size: 1.1rem;
        position: relative;
        z-index: 1;
    }
    
    .calendar-events {
        overflow-y: auto;
        max-height: 95px;
        position: relative;
        z-index: 2;
    }
    
    .calendar-event {
        text-decoration: none;
        font-size: 0.85rem;
        padding: 0.5rem !important;
        margin-bottom: 0.35rem !important;
        display: block;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
    }
    
    .calendar-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .today {
        position: relative;
        background-color: #fff8e6 !important;
    }
    
    .today .calendar-date {
        color: #ff9800;
        font-weight: bold;
    }
    
    .today::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 2px solid #ff9800;
        pointer-events: none;
        z-index: 0;
    }
    
    /* Different styling for days not in current month */
    .text-muted.bg-light .calendar-date {
        opacity: 0.5;
        font-weight: normal;
    }
    
    /* Custom color for Sundays */
    .text-danger {
        color: #dc3545 !important;
    }
    
    /* Event legend styling */
    .legend-item {
        display: flex;
        align-items: center;
        margin-right: 1rem;
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }
    
    .legend-item:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }
    
    .legend-color {
        width: 24px;
        height: 24px;
        border-radius: 0.5rem;
        margin-right: 0.75rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Month navigation buttons */
    .month-nav-btn {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 500;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }
    
    .month-nav-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    /* Current month/year title */
    .current-month-title {
        font-weight: 600;
        letter-spacing: 1px;
        padding: 0.5rem 2rem;
        border-radius: 2rem;
        background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 15px rgba(0, 102, 179, 0.2);
    }
    
    /* Year navigation */
    .year-nav {
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .year-nav .btn {
        border-radius: 0;
        font-weight: 500;
    }
    
    .year-nav .btn.btn-primary {
        box-shadow: none;
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
