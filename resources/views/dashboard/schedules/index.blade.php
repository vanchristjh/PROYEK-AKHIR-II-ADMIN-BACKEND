@extends('layouts.dashboard')

@section('page-title', 'Jadwal Pelajaran')

@php
    // Define day names mapping for translations
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

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('schedules.weekly') }}" class="btn btn-light btn-sm me-2 hover-shadow">
        <i class="bx bx-calendar-week me-1"></i> Tampilan Mingguan
    </a>
    <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-sm hover-shadow">
        <i class="bx bx-plus me-1"></i> Tambah Jadwal
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4 border-0 overflow-hidden">
    <div class="card-body">
        <!-- Success Message -->
        @if(session('success'))
        <div class="alert custom-alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon me-3">
                    <i class="bx bx-check-circle fs-4"></i>
                </div>
                <div>
                    <h6 class="alert-heading mb-1">Berhasil!</h6>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0 filter-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title mb-0 d-flex align-items-center">
                                <i class="bx bx-filter text-primary me-2"></i> Filter Jadwal
                            </h6>
                            <button class="btn btn-sm btn-link text-muted p-0" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true">
                                <i class="bx bx-chevron-up toggle-icon"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="filterCollapse">
                            <form action="{{ route('schedules.index') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="class_id" class="form-label">
                                        <i class="bx bx-buildings me-1 text-primary"></i> Kelas
                                    </label>
                                    <select class="form-select custom-select shadow-none" id="class_id" name="class_id">
                                        <option value="">Semua Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="teacher_id" class="form-label">
                                        <i class="bx bx-user me-1 text-primary"></i> Guru
                                    </label>
                                    <select class="form-select custom-select shadow-none" id="teacher_id" name="teacher_id">
                                        <option value="">Semua Guru</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="day" class="form-label">
                                        <i class="bx bx-calendar me-1 text-primary"></i> Hari
                                    </label>
                                    <select class="form-select custom-select shadow-none" id="day" name="day">
                                        <option value="">Semua Hari</option>
                                        <option value="monday" {{ request('day') == 'monday' ? 'selected' : '' }}>Senin</option>
                                        <option value="tuesday" {{ request('day') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                                        <option value="wednesday" {{ request('day') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                                        <option value="thursday" {{ request('day') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                                        <option value="friday" {{ request('day') == 'friday' ? 'selected' : '' }}>Jumat</option>
                                        <option value="saturday" {{ request('day') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                                        <option value="sunday" {{ request('day') == 'sunday' ? 'selected' : '' }}>Minggu</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="subject" class="form-label">
                                        <i class="bx bx-book me-1 text-primary"></i> Mata Pelajaran
                                    </label>
                                    <input type="text" class="form-control custom-input shadow-none" id="subject" name="subject" value="{{ request('subject') }}" placeholder="Nama mata pelajaran...">
                                </div>
                                <div class="col-12 text-end">
                                    <div class="filter-actions">
                                        <button type="submit" class="btn btn-primary hover-shadow">
                                            <i class="bx bx-filter-alt me-1"></i> Filter
                                        </button>
                                        <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary hover-shadow ms-2">
                                            <i class="bx bx-reset me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule List -->
        @if($schedules->isEmpty())
            <div class="text-center py-5 empty-state">
                <div class="empty-state-icon mb-4">
                    <i class="bx bx-calendar-x"></i>
                </div>
                <h5 class="text-muted">Tidak ada jadwal yang ditemukan</h5>
                <p class="text-muted">Tidak ada jadwal yang sesuai dengan filter yang dipilih atau belum ada jadwal yang ditambahkan.</p>
                <a href="{{ route('schedules.create') }}" class="btn btn-primary mt-2 hover-shadow">
                    <i class="bx bx-plus me-1"></i> Tambah Jadwal Baru
                </a>
            </div>
        @else
            <ul class="nav nav-tabs custom-tabs mb-4" id="scheduleTabs" role="tablist">
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $index => $day)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                id="{{ $day }}-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#{{ $day }}-tab-pane" 
                                type="button" 
                                role="tab" 
                                aria-controls="{{ $day }}-tab-pane" 
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                            {{ $dayNames[$day] }}
                            @if(isset($schedulesByDay[$day]))
                                <span class="badge bg-primary ms-1 badge-pill">{{ count($schedulesByDay[$day]) }}</span>
                            @endif
                        </button>
                    </li>
                @endforeach
            </ul>
            
            <div class="tab-content" id="scheduleTabContent">
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $index => $day)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                         id="{{ $day }}-tab-pane" 
                         role="tabpanel" 
                         aria-labelledby="{{ $day }}-tab" 
                         tabindex="0">
                        
                        @if(isset($schedulesByDay[$day]) && count($schedulesByDay[$day]) > 0)
                            <div class="table-responsive">
                                <table class="table custom-table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="15%">Waktu</th>
                                            <th width="20%">Mata Pelajaran</th>
                                            <th width="15%">Kelas</th>
                                            <th width="20%">Guru</th>
                                            <th width="10%">Ruangan</th>
                                            <th width="10%">Status</th>
                                            <th width="10%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($schedulesByDay[$day]->sortBy('start_time') as $schedule)
                                            <tr class="schedule-row {{ $schedule->is_active ? '' : 'inactive' }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="schedule-time-indicator me-2"></div>
                                                        <span>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</span>
                                                    </div>
                                                </td>
                                                <td class="subject-cell">
                                                    <div class="subject-name">{{ $schedule->subject }}</div>
                                                </td>
                                                <td>
                                                    @if($schedule->class)
                                                        <span class="class-badge">{{ $schedule->class->name }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($schedule->teacher)
                                                        <div class="d-flex align-items-center">
                                                            <div class="teacher-avatar me-2">
                                                                <i class="bx bx-user-circle"></i>
                                                            </div>
                                                            <span>{{ $schedule->teacher->name }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($schedule->room)
                                                        <span class="room-label">{{ $schedule->room }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($schedule->is_active)
                                                        <span class="status-badge active">
                                                            <i class="bx bx-check-circle me-1"></i> Aktif
                                                        </span>
                                                    @else
                                                        <span class="status-badge inactive">
                                                            <i class="bx bx-x-circle me-1"></i> Tidak Aktif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="action-buttons text-center">
                                                        <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-sm btn-action" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm btn-action" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-action delete-btn" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal" 
                                                                data-id="{{ $schedule->id }}"
                                                                data-subject="{{ $schedule->subject }}"
                                                                data-bs-toggle="tooltip" title="Hapus">
                                                            <i class="bx bx-trash text-danger"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 day-empty-state">
                                <i class="bx bx-calendar-x day-empty-icon"></i>
                                <p class="text-muted mb-0">Tidak ada jadwal untuk hari {{ $dayNames[$day] }}.</p>
                                <a href="{{ route('schedules.create') }}?day={{ $day }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="bx bx-plus-circle me-1"></i> Tambah Jadwal untuk {{ $dayNames[$day] }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade custom-modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="deleteModalLabel">Hapus Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pb-0">
                <div class="modal-icon mb-4 text-danger">
                    <i class="bx bx-error-circle"></i>
                </div>
                <h5>Apakah Anda yakin?</h5>
                <p>Anda akan menghapus jadwal mata pelajaran <span id="scheduleSubject" class="fw-bold"></span>.</p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan dan akan menghapus jadwal secara permanen.</p>
            </div>
            <div class="modal-footer border-top-0 justify-content-center gap-2 pt-0 pb-4">
                <button type="button" class="btn btn-outline-secondary modal-btn-cancel" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Batal
                </button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger modal-btn-confirm">
                        <i class="bx bx-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Filter Card Styling */
    .filter-card {
        border-radius: var(--border-radius);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
    }
    
    .filter-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    /* Custom Form Controls */
    .custom-select,
    .custom-input {
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }
    
    .custom-select:focus,
    .custom-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(0, 102, 179, 0.15);
    }
    
    .filter-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    
    .filter-actions .btn {
        padding: 0.5rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    /* Custom Tabs Styling */
    .custom-tabs {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0;
        gap: 0.5rem;
    }
    
    .custom-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        border-radius: 0.5rem 0.5rem 0 0;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .custom-tabs .nav-link:hover {
        color: var(--primary-color);
        background-color: rgba(0, 102, 179, 0.05);
    }
    
    .custom-tabs .nav-link.active {
        color: var(--primary-color);
        background-color: #fff;
        font-weight: 600;
    }
    
    .custom-tabs .nav-link.active:after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: var(--primary-color);
        border-radius: 2px 2px 0 0;
    }
    
    .custom-tabs .badge {
        font-size: 0.65rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: 30px;
    }
    
    /* Custom Table Styling */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .custom-table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 1rem;
        border-top: none;
        border-bottom: 2px solid #e9ecef;
        font-size: 0.875rem;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }
    
    .custom-table tbody tr {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
    }
    
    .custom-table tbody tr:hover {
        background-color: rgba(0, 102, 179, 0.03);
    }
    
    .custom-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }
    
    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Schedule Time Indicator */
    .schedule-time-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: var(--primary-color);
    }
    
    /* Subject Cell Styling */
    .subject-cell {
        font-weight: 600;
        color: var(--dark-color);
    }
    
    /* Class Badge Styling */
    .class-badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.375rem;
        background-color: var(--primary-color);
    }
    
    /* Teacher Cell Styling */
    .teacher-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background-color: var(--primary-light);
        color: var(--primary-color);
        border-radius: 50%;
        font-size: 1rem;
    }
    
    /* Room Label Styling */
    .room-label {
        display: inline-block;
        padding: 0.25em 0.65em;
        font-size: 0.75rem;
        font-weight: 500;
        background-color: #f0f0f0;
        color: #495057;
        border-radius: 0.25rem;
    }
    
    /* Status Badge Styling */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35em 0.65em;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1;
        white-space: nowrap;
        border-radius: 0.375rem;
    }
    
    .status-badge.active {
        background-color: rgba(16, 185, 129, 0.15);
        color: var(--success-color);
    }
    
    .status-badge.inactive {
        background-color: rgba(156, 163, 175, 0.15);
        color: #6c757d;
    }
    
    /* Action Buttons Styling */
    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }
    
    .btn-action {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #fff;
        border: 1px solid #e9ecef;
        color: #6c757d;
        transition: all 0.3s ease;
        padding: 0;
    }
    
    .btn-action:hover {
        background-color: var(--primary-light);
        color: var(--primary-color);
        transform: translateY(-2px);
    }
    
    .btn-action i {
        font-size: 1rem;
    }
    
    /* Inactive Schedule Row */
    .schedule-row.inactive {
        opacity: 0.7;
        background-color: #f9f9f9;
    }
    
    /* Empty State Styling */
    .empty-state {
        padding: 3rem 1rem;
    }
    
    .empty-state-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: var(--primary-light);
        color: var(--primary-color);
        font-size: 3rem;
    }
    
    .day-empty-state {
        padding: 2.5rem 1rem;
    }
    
    .day-empty-icon {
        display: block;
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
    
    /* Alert Styling */
    .custom-alert-success {
        background-color: rgba(16, 185, 129, 0.1);
        border: none;
        border-radius: 0.5rem;
        color: var(--success-color);
        padding: 1rem;
    }
    
    .custom-alert-success .alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--success-color);
        color: #fff;
    }
    
    /* Modal Styling */
    .custom-modal .modal-content {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .modal-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        font-size: 3rem;
    }
    
    .modal-btn-confirm, .modal-btn-cancel {
        padding: 0.6rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .modal-btn-confirm:hover, .modal-btn-cancel:hover {
        transform: translateY(-2px);
    }
    
    /* Hover Shadow Utility */
    .hover-shadow {
        transition: all 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Animation for Collapsible Filter */
    .toggle-icon {
        transition: transform 0.3s ease;
    }
    
    [aria-expanded="false"] .toggle-icon {
        transform: rotate(180deg);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .custom-tabs {
            overflow-x: auto;
            flex-wrap: nowrap;
            white-space: nowrap;
            padding-bottom: 5px;
        }
        
        .custom-tabs .nav-item {
            flex: 0 0 auto;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltipTriggerList.length > 0) {
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }
        
        // Handle delete modal
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            const deleteForm = document.getElementById('deleteForm');
            const scheduleSubject = document.getElementById('scheduleSubject');
            
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const scheduleId = this.getAttribute('data-id');
                    const subject = this.getAttribute('data-subject');
                    
                    deleteForm.action = `/schedules/${scheduleId}`;
                    scheduleSubject.textContent = subject;
                });
            });
        }
        
        // Auto select the day tab from URL if available
        const urlParams = new URLSearchParams(window.location.search);
        const dayParam = urlParams.get('day');
        
        if (dayParam) {
            const dayTab = document.getElementById(`${dayParam}-tab`);
            if (dayTab) {
                dayTab.click();
            }
        }
        
        // Smooth animation for success alert dismissal
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }, 5000);
        }
        
        // Custom select enhancement
        document.querySelectorAll('.custom-select').forEach(select => {
            select.addEventListener('change', function() {
                if (this.value) {
                    this.classList.add('border-primary');
                } else {
                    this.classList.remove('border-primary');
                }
            });
            
            // Initialize state
            if (select.value) {
                select.classList.add('border-primary');
            }
        });
    });
</script>
@endsection
