@extends('layouts.dashboard')

@section('page-title', 'Kalender Akademik')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('academic-calendar.create') }}" class="btn btn-primary btn-sm me-2">
        <i class="bx bx-plus me-1"></i> Tambah Agenda
    </a>
    <a href="{{ route('academic-calendar.index') }}" class="btn btn-light btn-sm">
        <i class="bx bx-list-ul me-1"></i> Tampilan Daftar
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-md-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Kalender Akademik</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" id="today-btn" class="btn btn-outline-secondary">Hari Ini</button>
                    <button type="button" id="prev-btn" class="btn btn-outline-secondary"><i class="bx bx-chevron-left"></i></button>
                    <button type="button" id="next-btn" class="btn btn-outline-secondary"><i class="bx bx-chevron-right"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div id="calendar-header" class="d-flex justify-content-between align-items-center mb-4">
                    <h4 id="calendar-title" class="m-0"></h4>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" id="month-view">Bulan</button>
                        <button type="button" class="btn btn-outline-primary" id="week-view">Minggu</button>
                        <button type="button" class="btn btn-outline-primary" id="day-view">Hari</button>
                    </div>
                </div>
                
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Filter Kalender</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Agenda</label>
                    <div class="form-check">
                        <input class="form-check-input event-type-filter" type="checkbox" value="academic" id="filter-academic" checked>
                        <label class="form-check-label" for="filter-academic">
                            <i class="bx bx-book text-primary me-1"></i> Akademik
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input event-type-filter" type="checkbox" value="exam" id="filter-exam" checked>
                        <label class="form-check-label" for="filter-exam">
                            <i class="bx bx-pencil text-danger me-1"></i> Ujian
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input event-type-filter" type="checkbox" value="holiday" id="filter-holiday" checked>
                        <label class="form-check-label" for="filter-holiday">
                            <i class="bx bx-cup-hot text-success me-1"></i> Libur
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input event-type-filter" type="checkbox" value="meeting" id="filter-meeting" checked>
                        <label class="form-check-label" for="filter-meeting">
                            <i class="bx bx-people text-purple me-1"></i> Rapat
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input event-type-filter" type="checkbox" value="extracurricular" id="filter-extracurricular" checked>
                        <label class="form-check-label" for="filter-extracurricular">
                            <i class="bx bx-dribbble text-orange me-1"></i> Ekstrakurikuler
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input event-type-filter" type="checkbox" value="other" id="filter-other" checked>
                        <label class="form-check-label" for="filter-other">
                            <i class="bx bx-calendar-event text-secondary me-1"></i> Lainnya
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tahun Akademik</label>
                    <select class="form-select form-select-sm" id="academic-year-filter">
                        <option value="">Semua Tahun Akademik</option>
                        <option value="2023/2024">2023/2024</option>
                        <option value="2024/2025">2024/2025</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Semester</label>
                    <select class="form-select form-select-sm" id="semester-filter">
                        <option value="">Semua Semester</option>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Target Peserta</label>
                    <select class="form-select form-select-sm" id="audience-filter">
                        <option value="">Semua</option>
                        <option value="students">Siswa</option>
                        <option value="teachers">Guru</option>
                        <option value="staff">Staf</option>
                    </select>
                </div>
                
                <button type="button" id="apply-filters" class="btn btn-primary btn-sm w-100">
                    <i class="bx bx-filter-alt me-1"></i> Terapkan Filter
                </button>
                <button type="button" id="reset-filters" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                    <i class="bx bx-reset me-1"></i> Reset Filter
                </button>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Agenda Mendatang</h5>
            </div>
            <div class="card-body p-0">
                <div id="upcoming-events" class="list-group list-group-flush">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted small">Memuat agenda...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailModalLabel">Detail Agenda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center" id="event-detail-loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail agenda...</p>
                </div>
                <div id="event-detail-content" style="display:none;">
                    <div class="mb-3 d-flex align-items-center">
                        <span id="event-type-badge" class="badge me-2"></span>
                        <h5 id="event-title" class="mb-0"></h5>
                        <span id="event-important" class="ms-2 text-danger" style="display:none;">
                            <i class="bx bxs-star"></i>
                        </span>
                    </div>
                    
                    <p id="event-description" class="text-muted"></p>
                    
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span><i class="bx bx-calendar me-2"></i> Tanggal</span>
                            <span id="event-date"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span><i class="bx bx-time me-2"></i> Waktu</span>
                            <span id="event-time"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0" id="event-location-item" style="display:none;">
                            <span><i class="bx bx-map me-2"></i> Lokasi</span>
                            <span id="event-location"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0" id="event-academic-year-item" style="display:none;">
                            <span><i class="bx bx-book me-2"></i> Tahun Akademik</span>
                            <span id="event-academic-year"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0" id="event-semester-item" style="display:none;">
                            <span><i class="bx bx-calendar-check me-2"></i> Semester</span>
                            <span id="event-semester"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span><i class="bx bx-user me-2"></i> Target</span>
                            <span id="event-target"></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-outline-primary" id="event-edit-link">
                    <i class="bx bx-edit me-1"></i> Edit
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<style>
    .fc-day-today {
        background-color: rgba(var(--bs-primary-rgb), 0.05) !important;
    }
    
    .fc-event {
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .fc-event:hover {
        transform: scale(1.02);
    }
    
    .fc-button-primary {
        background-color: var(--bs-primary) !important;
        border-color: var(--bs-primary) !important;
    }
    
    .fc-toolbar-title {
        font-size: 1.25rem !important;
    }
    
    .event-type-pill {
        width: 10px;
        height: 10px;
        display: inline-block;
        border-radius: 50%;
        margin-right: 5px;
    }
    
    .event-type-academic { background-color: #0d6efd; }
    .event-type-exam { background-color: #dc3545; }
    .event-type-holiday { background-color: #198754; }
    .event-type-meeting { background-color: #6f42c1; }
    .event-type-extracurricular { background-color: #fd7e14; }
    .event-type-other { background-color: #6c757d; }
    
    .event-important {
        position: relative;
    }
    
    .event-important::before {
        content: "★";
        position: absolute;
        top: -4px;
        right: -4px;
        font-size: 10px;
        color: #dc3545;
        background: #fff;
        border-radius: 50%;
        width: 14px;
        height: 14px;
        line-height: 14px;
        text-align: center;
    }
    
    .text-purple {
        color: #6f42c1;
    }
    
    .text-orange {
        color: #fd7e14;
    }
    
    #upcoming-events .list-group-item:last-child {
        border-bottom: 0;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize FullCalendar
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            headerToolbar: false,
            locale: 'id',
            firstDay: 1, // Monday
            dayMaxEventRows: true,
            dayMaxEvents: 4,
            views: {
                dayGrid: {
                    dayMaxEventRows: 4
                }
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false
            },
            eventDisplay: 'block',
            eventClick: function(info) {
                showEventDetail(info.event);
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                fetchEvents(fetchInfo, successCallback, failureCallback);
            },
            eventContent: function(arg) {
                let eventType = arg.event.extendedProps.event_type || 'other';
                let isImportant = arg.event.extendedProps.is_important;
                
                // Create event wrapper with custom styling
                const wrapper = document.createElement('div');
                wrapper.classList.add('d-flex', 'align-items-center', 'px-2', 'py-1');
                
                if (isImportant) {
                    wrapper.classList.add('event-important');
                }
                
                // Event indicator dot
                const dot = document.createElement('span');
                dot.classList.add('event-type-pill', `event-type-${eventType}`);
                wrapper.appendChild(dot);
                
                // Event title
                const titleEl = document.createElement('span');
                titleEl.innerText = arg.event.title;
                titleEl.classList.add('ms-1', 'text-truncate');
                wrapper.appendChild(titleEl);
                
                return { domNodes: [wrapper] };
            }
        });
        calendar.render();

        // Initialize navigation buttons
        document.getElementById('prev-btn').addEventListener('click', function() {
            calendar.prev();
            updateTitle();
        });
        
        document.getElementById('next-btn').addEventListener('click', function() {
            calendar.next();
            updateTitle();
        });
        
        document.getElementById('today-btn').addEventListener('click', function() {
            calendar.today();
            updateTitle();
        });
        
        // Initialize view buttons
        document.getElementById('month-view').addEventListener('click', function() {
            calendar.changeView('dayGridMonth');
            setActiveViewButton(this);
        });
        
        document.getElementById('week-view').addEventListener('click', function() {
            calendar.changeView('timeGridWeek');
            setActiveViewButton(this);
        });
        
        document.getElementById('day-view').addEventListener('click', function() {
            calendar.changeView('timeGridDay');
            setActiveViewButton(this);
        });
        
        // Set initial active view
        setActiveViewButton(document.getElementById('month-view'));
        
        // Update calendar title
        function updateTitle() {
            const calendarTitle = document.getElementById('calendar-title');
            calendarTitle.innerText = calendar.view.title;
        }
        
        // Set active view button
        function setActiveViewButton(button) {
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-primary');
            updateTitle();
        }
        
        // Initial title update
        updateTitle();
        
        // Filter events
        const filterInputs = document.querySelectorAll('.event-type-filter');
        const academicYearFilter = document.getElementById('academic-year-filter');
        const semesterFilter = document.getElementById('semester-filter');
        const audienceFilter = document.getElementById('audience-filter');
        const applyFiltersButton = document.getElementById('apply-filters');
        const resetFiltersButton = document.getElementById('reset-filters');
        
        applyFiltersButton.addEventListener('click', function() {
            calendar.refetchEvents();
            fetchUpcomingEvents();
        });
        
        resetFiltersButton.addEventListener('click', function() {
            // Reset all filters
            filterInputs.forEach(input => {
                input.checked = true;
            });
            academicYearFilter.value = '';
            semesterFilter.value = '';
            audienceFilter.value = '';
            
            // Refetch events with reset filters
            calendar.refetchEvents();
            fetchUpcomingEvents();
        });
        
        // Fetch calendar events with filters
        function fetchEvents(info, successCallback, failureCallback) {
            const eventTypes = getSelectedEventTypes();
            
            // Build the URL with query parameters
            let url = '/api/academic-calendar/events';
            const params = new URLSearchParams();
            params.append('start', info.startStr);
            params.append('end', info.endStr);
            
            if (eventTypes.length > 0) {
                params.append('event_types', eventTypes.join(','));
            }
            
            if (academicYearFilter.value) {
                params.append('academic_year', academicYearFilter.value);
            }
            
            if (semesterFilter.value) {
                params.append('semester', semesterFilter.value);
            }
            
            if (audienceFilter.value) {
                params.append('audience', audienceFilter.value);
            }
            
            // Show loading state
            calendarEl.classList.add('fc-loading');
            
            // Fetch events from API
            fetch(`${url}?${params.toString()}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch events');
                    }
                    return response.json();
                })
                .then(data => {
                    // Transform API response to FullCalendar event format
                    const events = data.map(event => {
                        return {
                            id: event.id,
                            title: event.title,
                            start: event.start_date,
                            end: event.end_date,
                            allDay: isAllDayEvent(event),
                            backgroundColor: getEventColor(event.event_type, event.color),
                            borderColor: getEventColor(event.event_type, event.color),
                            extendedProps: {
                                event_type: event.event_type,
                                is_important: event.is_important,
                                location: event.location,
                                description: event.description,
                                academic_year: event.academic_year,
                                semester: event.semester,
                                target_audience: event.target_audience
                            }
                        };
                    });
                    
                    successCallback(events);
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                })
                .finally(() => {
                    // Remove loading state
                    calendarEl.classList.remove('fc-loading');
                });
        }
        
        // Get selected event types for filtering
        function getSelectedEventTypes() {
            const selectedTypes = [];
            filterInputs.forEach(input => {
                if (input.checked) {
                    selectedTypes.push(input.value);
                }
            });
            return selectedTypes;
        }
        
        // Determine if an event is all day based on time
        function isAllDayEvent(event) {
            const startTime = new Date(event.start_date).toTimeString();
            const endTime = new Date(event.end_date).toTimeString();
            return startTime.startsWith('00:00:00') && endTime.startsWith('23:59:59');
        }
        
        // Get event color based on type or custom color
        function getEventColor(eventType, customColor) {
            if (customColor) return customColor;
            
            switch(eventType) {
                case 'academic': return '#0d6efd';
                case 'exam': return '#dc3545';
                case 'holiday': return '#198754';
                case 'meeting': return '#6f42c1';
                case 'extracurricular': return '#fd7e14';
                default: return '#6c757d';
            }
        }
        
        // Event detail modal handling
        function showEventDetail(event) {
            const modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
            
            // Show loading state
            document.getElementById('event-detail-loading').style.display = 'block';
            document.getElementById('event-detail-content').style.display = 'none';
            
            // Update event edit link
            const editLink = document.getElementById('event-edit-link');
            editLink.href = `/dashboard/academic-calendar/${event.id}/edit`;
            
            // Fetch event details from API
            fetch(`/api/academic-calendar/events/${event.id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch event details');
                    }
                    return response.json();
                })
                .then(eventData => {
                    // Update modal content
                    document.getElementById('event-title').innerText = eventData.title;
                    document.getElementById('event-description').innerText = eventData.description || 'Tidak ada deskripsi';
                    
                    // Format dates
                    const startDate = new Date(eventData.start_date);
                    const endDate = new Date(eventData.end_date);
                    
                    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    
                    // Check if multi-day event
                    if (startDate.toDateString() === endDate.toDateString()) {
                        document.getElementById('event-date').innerText = startDate.toLocaleDateString('id-ID', dateOptions);
                    } else {
                        document.getElementById('event-date').innerText = 
                            `${startDate.toLocaleDateString('id-ID', dateOptions)} - ${endDate.toLocaleDateString('id-ID', dateOptions)}`;
                    }
                    
                    // Format time
                    const timeOptions = { hour: '2-digit', minute: '2-digit' };
                    document.getElementById('event-time').innerText = 
                        `${startDate.toLocaleTimeString('id-ID', timeOptions)} - ${endDate.toLocaleTimeString('id-ID', timeOptions)}`;
                    
                    // Set location if available
                    if (eventData.location) {
                        document.getElementById('event-location').innerText = eventData.location;
                        document.getElementById('event-location-item').style.display = '';
                    } else {
                        document.getElementById('event-location-item').style.display = 'none';
                    }
                    
                    // Set academic year if available
                    if (eventData.academic_year) {
                        document.getElementById('event-academic-year').innerText = eventData.academic_year;
                        document.getElementById('event-academic-year-item').style.display = '';
                    } else {
                        document.getElementById('event-academic-year-item').style.display = 'none';
                    }
                    
                    // Set semester if available
                    if (eventData.semester) {
                        document.getElementById('event-semester').innerText = `Semester ${eventData.semester}`;
                        document.getElementById('event-semester-item').style.display = '';
                    } else {
                        document.getElementById('event-semester-item').style.display = 'none';
                    }
                    
                    // Set audience target
                    let audience = 'Semua';
                    switch (eventData.target_audience) {
                        case 'students': audience = 'Siswa'; break;
                        case 'teachers': audience = 'Guru'; break;
                        case 'staff': audience = 'Staf'; break;
                    }
                    document.getElementById('event-target').innerText = audience;
                    
                    // Set event type badge
                    const badgeEl = document.getElementById('event-type-badge');
                    badgeEl.className = 'badge me-2';
                    
                    switch (eventData.event_type) {
                        case 'academic': 
                            badgeEl.classList.add('bg-primary');
                            badgeEl.innerText = 'Akademik';
                            break;
                        case 'exam': 
                            badgeEl.classList.add('bg-danger');
                            badgeEl.innerText = 'Ujian';
                            break;
                        case 'holiday': 
                            badgeEl.classList.add('bg-success');
                            badgeEl.innerText = 'Libur';
                            break;
                        case 'meeting': 
                            badgeEl.classList.add('bg-purple', 'text-white');
                            badgeEl.innerText = 'Rapat';
                            break;
                        case 'extracurricular': 
                            badgeEl.classList.add('bg-warning', 'text-dark');
                            badgeEl.innerText = 'Ekstrakurikuler';
                            break;
                        default:
                            badgeEl.classList.add('bg-secondary');
                            badgeEl.innerText = 'Lainnya';
                    }
                    
                    // Set important indicator
                    if (eventData.is_important) {
                        document.getElementById('event-important').style.display = '';
                    } else {
                        document.getElementById('event-important').style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching event details:', error);
                })
                .finally(() => {
                    // Hide loading state
                    document.getElementById('event-detail-loading').style.display = 'none';
                    document.getElementById('event-detail-content').style.display = 'block';
                });
            
            modal.show();
        }
        
        // Fetch upcoming events for sidebar
        function fetchUpcomingEvents() {
            const upcomingEventsContainer = document.getElementById('upcoming-events');
            
            // Show loading state
            upcomingEventsContainer.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted small">Memuat agenda...</p>
                </div>
            `;
            
            // Build the URL with query parameters
            const eventTypes = getSelectedEventTypes();
            let url = '/api/academic-calendar/upcoming';
            const params = new URLSearchParams();
            
            if (eventTypes.length > 0) {
                params.append('event_types', eventTypes.join(','));
            }
            
            if (academicYearFilter.value) {
                params.append('academic_year', academicYearFilter.value);
            }
            
            if (semesterFilter.value) {
                params.append('semester', semesterFilter.value);
            }
            
            if (audienceFilter.value) {
                params.append('audience', audienceFilter.value);
            }
            
            // Fetch upcoming events
            fetch(`${url}?${params.toString()}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch upcoming events');
                    }
                    return response.json();
                })
                .then(events => {
                    if (events.length === 0) {
                        upcomingEventsContainer.innerHTML = `
                            <div class="text-center py-4">
                                <i class="bx bx-calendar-x fs-1 text-muted"></i>
                                <p class="mt-2 text-muted">Tidak ada agenda mendatang</p>
                            </div>
                        `;
                        return;
                    }
                    
                    // Create HTML for upcoming events
                    let html = '';
                    events.forEach(event => {
                        const startDate = new Date(event.start_date);
                        const eventColor = getEventColor(event.event_type, event.color);
                        
                        html += `
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action p-3" 
                               data-event-id="${event.id}" onclick="showEventDetailById(${event.id})">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 text-truncate" style="max-width: 180px;">
                                            <span class="event-type-pill event-type-${event.event_type}"></span>
                                            ${event.title}
                                            ${event.is_important ? '<i class="bx bxs-star text-danger ms-1"></i>' : ''}
                                        </h6>
                                        <p class="mb-0 small text-muted">
                                            <i class="bx bx-calendar me-1"></i> 
                                            ${startDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                    
                    upcomingEventsContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching upcoming events:', error);
                    upcomingEventsContainer.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bx bx-error-circle fs-1 text-danger"></i>
                            <p class="mt-2 text-muted">Gagal memuat agenda</p>
                        </div>
                    `;
                });
        }
        
        // Initial fetch of upcoming events
        fetchUpcomingEvents();
        
        // Expose function to global scope for the onclick handler
        window.showEventDetailById = function(eventId) {
            // Find event in calendar
            const event = calendar.getEventById(eventId);
            if (event) {
                showEventDetail(event);
            } else {
                // Create a temporary event object and fetch details
                const tempEvent = { id: eventId };
                showEventDetail(tempEvent);
            }
        };
    });
</script>
@endsection
