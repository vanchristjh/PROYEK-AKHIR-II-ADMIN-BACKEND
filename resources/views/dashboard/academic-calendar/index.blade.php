@extends('layouts.dashboard')

@section('page-title', 'Kalender Akademik')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('academic-calendar.create') }}" class="btn btn-primary btn-sm me-2">
        <i class="bx bx-plus me-1"></i> Tambah Agenda
    </a>
    <a href="{{ route('academic-calendar.calendar') }}" class="btn btn-light btn-sm">
        <i class="bx bx-calendar me-1"></i> Tampilan Kalender
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bx bx-check-circle fs-4 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Filter Kalender</h6>
                        <form action="{{ route('academic-calendar.index') }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label for="event_type" class="form-label">Jenis Agenda</label>
                                <select class="form-select" id="event_type" name="event_type">
                                    <option value="">Semua Jenis</option>
                                    <option value="academic" {{ request('event_type') == 'academic' ? 'selected' : '' }}>Akademik</option>
                                    <option value="exam" {{ request('event_type') == 'exam' ? 'selected' : '' }}>Ujian</option>
                                    <option value="holiday" {{ request('event_type') == 'holiday' ? 'selected' : '' }}>Libur</option>
                                    <option value="meeting" {{ request('event_type') == 'meeting' ? 'selected' : '' }}>Rapat</option>
                                    <option value="extracurricular" {{ request('event_type') == 'extracurricular' ? 'selected' : '' }}>Ekstrakurikuler</option>
                                    <option value="other" {{ request('event_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="month" class="form-label">Bulan</label>
                                <select class="form-select" id="month" name="month">
                                    <option value="">Semua Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="year" class="form-label">Tahun</label>
                                <select class="form-select" id="year" name="year">
                                    @for($i = now()->year - 2; $i <= now()->year + 2; $i++)
                                        <option value="{{ $i }}" {{ (request('year', now()->year) == $i) ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="academic_year" class="form-label">Tahun Akademik</label>
                                <select class="form-select" id="academic_year" name="academic_year">
                                    <option value="">Semua Tahun Akademik</option>
                                    <option value="2023/2024" {{ request('academic_year') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                                    <option value="2024/2025" {{ request('academic_year') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                </select>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter-alt me-1"></i> Filter
                                </button>
                                <a href="{{ route('academic-calendar.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Timeline -->
        @if($events->isEmpty())
            <div class="text-center py-5">
                <img src="https://via.placeholder.com/150" alt="No events" class="img-fluid mb-3 opacity-50" style="max-width: 150px;">
                <h5 class="text-muted">Tidak ada agenda yang ditemukan</h5>
                <p class="text-muted">Tidak ada agenda yang sesuai dengan filter yang dipilih atau belum ada agenda yang ditambahkan.</p>
                <a href="{{ route('academic-calendar.create') }}" class="btn btn-primary mt-2">
                    <i class="bx bx-plus me-1"></i> Tambah Agenda Baru
                </a>
            </div>
        @else
            @foreach($eventsByMonth as $month => $monthEvents)
                <div class="timeline-month mb-4">
                    <h5 class="timeline-month-title bg-light p-3 rounded-3 mb-3">{{ $month }}</h5>
                    
                    <div class="timeline">
                        @foreach($monthEvents as $event)
                            <div class="timeline-item mb-4">
                                <div class="timeline-item-date me-3">
                                    <div class="timeline-date-circle 
                                        @if($event->is_ongoing) bg-success
                                        @elseif($event->start_date > now()) bg-primary
                                        @else bg-secondary @endif
                                        text-white rounded-circle p-2 d-flex align-items-center justify-content-center fs-5">
                                        {{ $event->start_date->format('d') }}
                                    </div>
                                    <div class="text-center small mt-1">{{ $event->start_date->format('M') }}</div>
                                </div>
                                
                                <div class="timeline-item-content flex-grow-1">
                                    <div class="card {{ $event->is_important ? 'border-danger' : 'border' }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title mb-0">
                                                    {{ $event->title }}
                                                    @if($event->is_important)
                                                        <i class="bx bxs-star text-danger ms-1" data-bs-toggle="tooltip" title="Agenda Penting"></i>
                                                    @endif
                                                </h5>
                                                <div>
                                                    {!! $event->event_type_badge !!}
                                                </div>
                                            </div>
                                            <p class="card-text">{{ Str::limit($event->description, 150) }}</p>
                                            <div class="d-flex flex-wrap small text-muted mb-2">
                                                <div class="me-3">
                                                    <i class="bx bx-calendar-event me-1"></i> {{ $event->duration }}
                                                </div>
                                                @if($event->location)
                                                <div class="me-3">
                                                    <i class="bx bx-map me-1"></i> {{ $event->location }}
                                                </div>
                                                @endif
                                                @if($event->academic_year)
                                                <div class="me-3">
                                                    <i class="bx bx-book me-1"></i> {{ $event->academic_year }}
                                                </div>
                                                @endif
                                                @if($event->semester)
                                                <div class="me-3">
                                                    <i class="bx bx-time me-1"></i> Semester {{ $event->semester }}
                                                </div>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('academic-calendar.show', $event) }}" class="btn btn-sm btn-light">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('academic-calendar.edit', $event) }}" class="btn btn-sm btn-light">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('academic-calendar.destroy', $event) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus agenda ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light text-danger">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 3rem;
    }
    
    .timeline:before {
        content: "";
        position: absolute;
        top: 0;
        left: 15px;
        height: 100%;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        display: flex;
        position: relative;
    }
    
    .timeline-date-circle {
        width: 32px;
        height: 32px;
    }
    
    .timeline-month-title {
        position: sticky;
        top: 0;
        z-index: 10;
        backdrop-filter: blur(5px);
    }
</style>
@endsection
