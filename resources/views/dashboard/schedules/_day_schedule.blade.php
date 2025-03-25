@php
    // This component expects to receive:
    // $schedules - collection of schedules for a specific day
    // $displayClass - boolean to display class column (default true)
    // $displayTeacher - boolean to display teacher column (default true)
    
    $displayClass = $displayClass ?? true;
    $displayTeacher = $displayTeacher ?? true;
    
    // Define day names mapping if not already defined
    $dayNames = $dayNames ?? [
        'monday' => 'Senin',
        'tuesday' => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday' => 'Kamis',
        'friday' => 'Jumat',
        'saturday' => 'Sabtu',
        'sunday' => 'Minggu',
    ];
@endphp

@if(isset($schedules) && count($schedules) > 0)
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped table-hover">   
            <thead class="table-light">
                <tr>
                    <th class="align-middle">Waktu</th>
                    <th class="align-middle">Mata Pelajaran</th>
                    @if($displayClass)
                    <th class="align-middle">Kelas</th>
                    @endif
                    @if($displayTeacher)
                    <th class="align-middle">Guru</th>
                    @endif
                    <th class="align-middle">Ruangan</th>
                    <th class="align-middle text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules->sortBy('start_time') as $schedule)
                    <tr>
                        <td class="align-middle"><span class="badge bg-light text-dark">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</span></td>
                        <td class="align-middle fw-medium">{{ $schedule->subject }}</td>
                        @if($displayClass)
                        <td class="align-middle">{{ $schedule->class ? $schedule->class->name : '-' }}</td>
                        @endif
                        @if($displayTeacher)
                        <td class="align-middle">{{ $schedule->teacher ? $schedule->teacher->name : '-' }}</td>
                        @endif
                        <td class="align-middle"><span class="badge bg-light-subtle border border-light-subtle text-dark px-2 py-1">{{ $schedule->room ?: '-' }}</span></td>
                        <td class="align-middle text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-outline-primary" title="Detail">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger delete-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal" 
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
    <div class="text-center py-5 my-4 bg-light rounded shadow-sm">
        <i class="bx bx-calendar-x text-secondary mb-2" style="font-size: 2.5rem;"></i>
        <p class="text-muted mb-0">Tidak ada jadwal untuk hari ini.</p>
    </div>
@endif
