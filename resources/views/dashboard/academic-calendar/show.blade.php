@extends('layouts.dashboard')

@php
use Illuminate\Support\Str;
use Carbon\Carbon;
@endphp

@section('page-title', 'Detail Agenda Akademik')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('academic-calendar.index') }}" class="btn btn-secondary btn-sm me-2">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
    <a href="{{ route('academic-calendar.edit', $academicCalendar) }}" class="btn btn-primary btn-sm">
        <i class="bx bx-edit me-1"></i> Edit
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    {!! $academicCalendar->event_type_badge !!}
                    <h4 class="mb-0 ms-2">{{ $academicCalendar->title }}</h4>
                    @if($academicCalendar->is_important)
                        <span class="badge bg-danger ms-2"><i class="bx bxs-star me-1"></i> Agenda Penting</span>
                    @endif
                </div>
                
                <div class="event-date-badge mb-4 d-flex align-items-center">
                    <div class="event-date-icon me-3 rounded text-center p-3 {{ $academicCalendar->is_ongoing ? 'bg-success' : ($academicCalendar->start_date > now() ? 'bg-primary' : 'bg-secondary') }} text-white">
                        <div class="day fs-2 fw-bold lh-1">{{ $academicCalendar->start_date->format('d') }}</div>
                        <div class="month small text-uppercase">{{ $academicCalendar->start_date->format('M') }}</div>
                    </div>
                    <div>
                        <div class="text-muted mb-1">
                            <i class="bx bx-calendar me-1"></i>
                            {{ $academicCalendar->start_date->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            @if(!$academicCalendar->start_date->isSameDay($academicCalendar->end_date))
                                - {{ $academicCalendar->end_date->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            @endif
                        </div>
                        <div class="text-muted">
                            <i class="bx bx-time me-1"></i>
                            {{ $academicCalendar->start_date->format('H:i') }} - {{ $academicCalendar->end_date->format('H:i') }}
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5>Deskripsi</h5>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($academicCalendar->description ?? 'Tidak ada deskripsi')) !!}
                    </div>
                </div>
                
                @if($academicCalendar->location)
                <div class="mb-4">
                    <h5>Lokasi</h5>
                    <div class="p-3 bg-light rounded d-flex align-items-center">
                        <i class="bx bx-map fs-4 me-2 text-primary"></i>
                        <span>{{ $academicCalendar->location }}</span>
                    </div>
                </div>
                @endif
                
                <div class="mt-4">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('academic-calendar.edit', $academicCalendar) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> Edit Agenda
                        </a>
                        <form action="{{ route('academic-calendar.destroy', $academicCalendar) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus agenda ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bx bx-trash me-1"></i> Hapus Agenda
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Status Agenda</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center mb-3">
                    @if($academicCalendar->is_ongoing)
                        <div class="badge bg-success p-3 fs-6">
                            <i class="bx bx-calendar-check me-1"></i> Sedang Berlangsung
                        </div>
                    @elseif($academicCalendar->start_date > now())
                        <div class="badge bg-primary p-3 fs-6">
                            <i class="bx bx-calendar me-1"></i> Akan Datang
                        </div>
                    @else
                        <div class="badge bg-secondary p-3 fs-6">
                            <i class="bx bx-calendar-x me-1"></i> Telah Selesai
                        </div>
                    @endif
                </div>
                
                @if($academicCalendar->start_date > now())
                    <div class="text-center mb-3">
                        <div class="text-muted">Akan dimulai dalam:</div>
                        <div class="fs-5 fw-bold" id="countdown">Menghitung...</div>
                    </div>
                @endif
                
                @if($academicCalendar->is_ongoing)
                    <div class="progress mb-3" style="height: 10px">
                        @php
                            $totalDuration = $academicCalendar->end_date->timestamp - $academicCalendar->start_date->timestamp;
                            $elapsedDuration = now()->timestamp - $academicCalendar->start_date->timestamp;
                            $progressPercentage = min(100, max(0, ($elapsedDuration / $totalDuration) * 100));
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPercentage }}%" 
                             aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Details Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Informasi Tambahan</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Target Peserta</span>
                        <span class="fw-medium">
                            @switch($academicCalendar->target_audience)
                                @case('students')
                                    <span class="badge bg-info">Siswa</span>
                                    @break
                                @case('teachers')
                                    <span class="badge bg-info">Guru</span>
                                    @break
                                @case('staff')
                                    <span class="badge bg-info">Staf</span>
                                    @break
                                @default
                                    <span class="badge bg-info">Semua</span>
                            @endswitch
                        </span>
                    </li>
                    
                    @if($academicCalendar->academic_year)
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Tahun Akademik</span>
                        <span class="fw-medium">{{ $academicCalendar->academic_year }}</span>
                    </li>
                    @endif
                    
                    @if($academicCalendar->semester)
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Semester</span>
                        <span class="fw-medium">{{ $academicCalendar->semester }}</span>
                    </li>
                    @endif
                    
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Durasi</span>
                        <span class="fw-medium">{{ $academicCalendar->duration }}</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- System Info Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Informasi Sistem</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Dibuat Pada</span>
                        <span class="fw-medium">{{ $academicCalendar->created_at->format('d M Y H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Terakhir Diperbarui</span>
                        <span class="fw-medium">{{ $academicCalendar->updated_at->format('d M Y H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Dibuat Oleh</span>
                        <span class="fw-medium">{{ $academicCalendar->creator ? $academicCalendar->creator->name : 'System' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .event-date-icon {
        width: 80px;
        height: 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = new Date('{{ $academicCalendar->start_date->toIso8601String() }}');
        const countdownElement = document.getElementById('countdown');
        
        if (countdownElement) {
            // Update the count down every 1 second
            const x = setInterval(function() {
                // Get current date and time
                const now = new Date().getTime();
                
                // Find the distance between now and the event date
                const distance = startDate.getTime() - now;
                
                // Time calculations for days, hours, minutes and seconds
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Display the result
                countdownElement.innerHTML = days + " hari " + hours + " jam " + minutes + " menit";
                
                // If the count down is finished, write some text
                if (distance < 0) {
                    clearInterval(x);
                    countdownElement.innerHTML = "Agenda sedang berlangsung";
                }
            }, 1000);
        }
    });
</script>
@endsection
