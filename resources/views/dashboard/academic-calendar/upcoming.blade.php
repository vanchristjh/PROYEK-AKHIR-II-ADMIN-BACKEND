@extends('layouts.dashboard')

@section('page-title', 'Agenda Mendatang')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('academic-calendar.index') }}" class="btn btn-light btn-sm me-2">
        <i class="bx bx-list-ul me-1"></i> Semua Agenda
    </a>
    <a href="{{ route('academic-calendar.calendar') }}" class="btn btn-light btn-sm me-2">
        <i class="bx bx-calendar me-1"></i> Tampilan Kalender
    </a>
    <a href="{{ route('academic-calendar.create') }}" class="btn btn-primary btn-sm">
        <i class="bx bx-plus me-1"></i> Tambah Agenda
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <!-- Current Events -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Agenda Sedang Berlangsung</h5>
                <span class="badge bg-success">{{ $currentEvents->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($currentEvents->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($currentEvents as $event)
                            <a href="{{ route('academic-calendar.show', $event) }}" class="list-group-item list-group-item-action p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0">{{ $event->title }}</h6>
                                    {!! $event->event_type_badge !!}
                                </div>
                                <div class="small text-muted d-flex flex-wrap gap-2">
                                    <span><i class="bx bx-calendar-event me-1"></i>{{ $event->duration }}</span>
                                    @if($event->location)
                                        <span><i class="bx bx-map me-1"></i>{{ $event->location }}</span>
                                    @endif
                                </div>
                                <div class="progress mt-2" style="height: 5px;">
                                    @php
                                        $total = $event->end_date->diffInSeconds($event->start_date);
                                        $elapsed = now()->diffInSeconds($event->start_date);
                                        $percent = min(100, max(0, ($elapsed / $total) * 100));
                                    @endphp
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%"></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <img src="https://via.placeholder.com/100" alt="No events" class="img-fluid mb-3 opacity-50" style="max-width: 100px;">
                        <p class="text-muted">Tidak ada agenda yang sedang berlangsung</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Agenda Mendatang</h5>
                <span class="badge bg-primary">{{ $upcomingEvents->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($upcomingEvents->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingEvents as $event)
                            <a href="{{ route('academic-calendar.show', $event) }}" class="list-group-item list-group-item-action p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0">
                                        {{ $event->title }}
                                        @if($event->is_important)
                                            <i class="bx bxs-star text-danger ms-1"></i>
                                        @endif
                                    </h6>
                                    {!! $event->event_type_badge !!}
                                </div>
                                <div class="small text-muted d-flex flex-wrap gap-2">
                                    <span><i class="bx bx-calendar-event me-1"></i>{{ $event->start_date->format('d M Y H:i') }}</span>
                                    @if($event->location)
                                        <span><i class="bx bx-map me-1"></i>{{ $event->location }}</span>
                                    @endif
                                </div>
                                <div class="small mt-1">
                                    <span class="text-primary">
                                        @php
                                            $diffDays = now()->diffInDays($event->start_date, false);
                                            if ($diffDays == 0) {
                                                echo '<span class="text-success">Hari ini</span>';
                                            } elseif ($diffDays == 1) {
                                                echo '<span class="text-warning">Besok</span>';
                                            } elseif ($diffDays > 1 && $diffDays <= 7) {
                                                echo '<span class="text-primary">' . $diffDays . ' hari lagi</span>';
                                            } else {
                                                echo '<span class="text-muted">' . $event->start_date->format('d M Y') . '</span>';
                                            }
                                        @endphp
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <img src="https://via.placeholder.com/100" alt="No events" class="img-fluid mb-3 opacity-50" style="max-width: 100px;">
                        <p class="text-muted">Tidak ada agenda mendatang</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Calendar Summary -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Ringkasan Kalender</h5>
        <a href="{{ route('academic-calendar.calendar') }}" class="btn btn-sm btn-outline-primary">
            <i class="bx bx-calendar me-1"></i> Tampilan Kalender
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper me-3 bg-primary text-white rounded-circle p-3">
                                <i class="bx bx-calendar-check fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Agenda Bulan Ini</h6>
                                @php
                                    $thisMonthEvents = \App\Models\AcademicCalendar::whereMonth('start_date', now()->month)
                                        ->whereYear('start_date', now()->year)
                                        ->count();
                                @endphp
                                <h3 class="mb-0">{{ $thisMonthEvents }}</h3>
                            </div>
                        </div>
                        <hr>
                        <div class="small">
                            @php
                                $mostFrequentType = \App\Models\AcademicCalendar::whereMonth('start_date', now()->month)
                                    ->whereYear('start_date', now()->year)
                                    ->selectRaw('event_type, count(*) as count')
                                    ->groupBy('event_type')
                                    ->orderBy('count', 'desc')
                                    ->first();
                            @endphp
                            @if($mostFrequentType)
                                <p class="text-muted">Jenis agenda terbanyak: 
                                    @switch($mostFrequentType->event_type)
                                        @case('academic')
                                            <span class="badge bg-primary">Akademik</span>
                                            @break
                                        @case('exam')
                                            <span class="badge bg-danger">Ujian</span>
                                            @break
                                        @case('holiday')
                                            <span class="badge bg-success">Libur</span>
                                            @break
                                        @case('meeting')
                                            <span class="badge bg-info">Rapat</span>
                                            @break
                                        @case('extracurricular')
                                            <span class="badge bg-warning text-dark">Ekstrakurikuler</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">Lainnya</span>
                                    @endswitch
                                    ({{ $mostFrequentType->count }})
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper me-3 bg-danger text-white rounded-circle p-3">
                                <i class="bx bx-calendar-star fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Agenda Penting Mendatang</h6>
                                @php
                                    $importantEvents = \App\Models\AcademicCalendar::where('is_important', true)
                                        ->where('start_date', '>=', now())
                                        ->count();
                                @endphp
                                <h3 class="mb-0">{{ $importantEvents }}</h3>
                            </div>
                        </div>
                        <hr>
                        <div class="small">
                            @php
                                $nextImportant = \App\Models\AcademicCalendar::where('is_important', true)
                                    ->where('start_date', '>=', now())
                                    ->orderBy('start_date')
                                    ->first();
                            @endphp
                            @if($nextImportant)
                                <p class="text-muted">Agenda penting terdekat: <br>
                                    <strong>{{ $nextImportant->title }}</strong> 
                                    <span class="d-block mt-1">
                                        <i class="bx bx-calendar-event me-1"></i>{{ $nextImportant->start_date->format('d M Y') }}
                                    </span>
                                </p>
                            @else
                                <p class="text-muted">Tidak ada agenda penting yang akan datang.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper me-3 bg-success text-white rounded-circle p-3">
                                <i class="bx bx-calendar-edit fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Total Agenda</h6>
                                @php
                                    $totalEvents = \App\Models\AcademicCalendar::count();
                                @endphp
                                <h3 class="mb-0">{{ $totalEvents }}</h3>
                            </div>
                        </div>
                        <hr>
                        <div class="small">
                            <p class="text-muted">
                                @php
                                    $typeCounts = \App\Models\AcademicCalendar::selectRaw('event_type, count(*) as count')
                                        ->groupBy('event_type')
                                        ->pluck('count', 'event_type')
                                        ->toArray();
                                @endphp
                                
                                @foreach($typeCounts as $type => $count)
                                    <span class="d-inline-block me-2 mb-1">
                                        @switch($type)
                                            @case('academic')
                                                <span class="badge bg-primary">{{ $count }}</span> Akademik
                                                @break
                                            @case('exam')
                                                <span class="badge bg-danger">{{ $count }}</span> Ujian
                                                @break
                                            @case('holiday')
                                                <span class="badge bg-success">{{ $count }}</span> Libur
                                                @break
                                            @case('meeting')
                                                <span class="badge bg-info">{{ $count }}</span> Rapat
                                                @break
                                            @case('extracurricular')
                                                <span class="badge bg-warning">{{ $count }}</span> Ekstrakurikuler
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $count }}</span> Lainnya
                                        @endswitch
                                    </span>
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
