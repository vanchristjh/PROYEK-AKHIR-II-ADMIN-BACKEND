@extends('layouts.dashboard')

@section('page-title', 'Detail Agenda')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('academic-calendar.edit', $academicCalendar) }}" class="btn btn-primary btn-sm me-2">
        <i class="bx bx-edit me-1"></i> Edit
    </a>
    <a href="{{ route('academic-calendar.index') }}" class="btn btn-secondary btn-sm">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $academicCalendar->title }}</h5>
                    <div>
                        {!! $academicCalendar->event_type_badge !!}
                        @if($academicCalendar->is_important)
                            <span class="badge bg-danger ms-1"><i class="bx bxs-star me-1"></i>Penting</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex flex-wrap small text-muted mb-3">
                        <div class="me-4">
                            <i class="bx bx-calendar-event me-1"></i> {{ $academicCalendar->duration }}
                        </div>
                        @if($academicCalendar->location)
                        <div class="me-4">
                            <i class="bx bx-map me-1"></i> {{ $academicCalendar->location }}
                        </div>
                        @endif
                        @if($academicCalendar->academic_year)
                        <div class="me-4">
                            <i class="bx bx-book me-1"></i> Tahun Akademik: {{ $academicCalendar->academic_year }}
                        </div>
                        @endif
                        @if($academicCalendar->semester)
                        <div class="me-4">
                            <i class="bx bx-time me-1"></i> Semester {{ $academicCalendar->semester }}
                        </div>
                        @endif
                        <div>
                            <i class="bx bx-user me-1"></i> Target: 
                            @switch($academicCalendar->target_audience)
                                @case('all')
                                    Semua
                                    @break
                                @case('students')
                                    Siswa
                                    @break
                                @case('teachers')
                                    Guru
                                    @break
                                @case('staff')
                                    Staf
                                    @break
                            @endswitch
                        </div>
                    </div>
                    
                    <div class="agenda-description">
                        @if($academicCalendar->description)
                            <p>{{ $academicCalendar->description }}</p>
                        @else
                            <p class="text-muted">Tidak ada deskripsi tambahan untuk agenda ini.</p>
                        @endif
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        <i class="bx bx-user-circle me-1"></i> Dibuat oleh: {{ $academicCalendar->creator ? $academicCalendar->creator->name : 'Admin' }}
                    </div>
                    <div class="small text-muted">
                        <i class="bx bx-calendar-check me-1"></i> Dibuat: {{ $academicCalendar->created_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('academic-calendar.edit', $academicCalendar) }}" class="btn btn-outline-primary flex-grow-1 d-flex align-items-center justify-content-center">
                <i class="bx bx-edit me-2"></i> Edit Agenda
            </a>
            <form action="{{ route('academic-calendar.destroy', $academicCalendar) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus agenda ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger d-flex align-items-center">
                    <i class="bx bx-trash me-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Status Agenda</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Status</span>
                        <span>
                            @if($academicCalendar->is_ongoing)
                                <span class="badge bg-success">Sedang Berlangsung</span>
                            @elseif($academicCalendar->start_date > now())
                                <span class="badge bg-primary">Akan Datang</span>
                            @else
                                <span class="badge bg-secondary">Telah Selesai</span>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Jenis Agenda</span>
                        <span>{!! $academicCalendar->event_type_badge !!}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Prioritas</span>
                        <span>
                            @if($academicCalendar->is_important)
                                <span class="badge bg-danger">Penting</span>
                            @else
                                <span class="badge bg-light text-dark">Normal</span>
                            @endif
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Related Events Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Agenda Terkait</h5>
            </div>
            <div class="card-body">
                @php
                    // Find related events (same event type or same day)
                    $relatedEvents = \App\Models\AcademicCalendar::where('id', '!=', $academicCalendar->id)
                        ->where(function($query) use ($academicCalendar) {
                            $query->where('event_type', $academicCalendar->event_type)
                                ->orWhereDate('start_date', $academicCalendar->start_date->format('Y-m-d'));
                        })
                        ->orderBy('start_date')
                        ->take(5)
                        ->get();
                @endphp

                @if($relatedEvents->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($relatedEvents as $event)
                            <a href="{{ route('academic-calendar.show', $event) }}" class="list-group-item list-group-item-action px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $event->title }}</h6>
                                    <small>{!! $event->event_type_badge !!}</small>
                                </div>
                                <small class="text-muted">
                                    <i class="bx bx-calendar me-1"></i> {{ $event->start_date->format('d M Y') }}
                                </small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">Tidak ada agenda terkait yang ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .agenda-description {
        line-height: 1.6;
    }
</style>
@endsection
