@php
    // Set default values if variables aren't passed
    $timeSlots = $timeSlots ?? array_map(function($hour) {
        return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
    }, range(7, 17));
    
    $days = $days ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    
    $weeklySchedule = $weeklySchedule ?? [];
@endphp

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
            @foreach($timeSlots as $timeSlot)
                <tr>
                    <td class="bg-light text-center align-middle fw-bold">{{ $timeSlot }}</td>
                    
                    @foreach($days as $day)
                        <td class="schedule-cell">
                            @if(isset($weeklySchedule[$day][$timeSlot]) && count($weeklySchedule[$day][$timeSlot]) > 0)
                                @foreach($weeklySchedule[$day][$timeSlot] as $schedule)
                                    <div class="schedule-item mb-2 p-2 border rounded {{ $schedule->is_active ? 'bg-white' : 'bg-light text-muted' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-primary">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</span>
                                            <a href="{{ route('schedules.show', $schedule) }}" class="text-primary">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        </div>
                                        <h6 class="mb-0 text-truncate">{{ $schedule->subject }}</h6>
                                        <div class="small">
                                            @if($schedule->teacher)
                                                <div class="text-truncate fw-bold">{{ $schedule->teacher->name }}</div>
                                            @endif
                                            @if($schedule->class)
                                                <div class="text-truncate text-muted"><i class="bx bx-home-alt"></i> {{ $schedule->class->name }}</div>
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
