@extends('layouts.dashboard')

@section('title', 'Kalender Jadwal')

@section('header', 'Kalender Jadwal')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-calendar-week text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Kalender Jadwal</h2>
            <p class="text-blue-100">Tampilan visual jadwal mingguan</p>
        </div>
    </div>

    <div class="mb-6">
        <a href="{{ route('admin.schedule.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Daftar Jadwal</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden border border-gray-100">
        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-filter text-gray-500 mr-2"></i>
                <h3 class="font-medium text-gray-700">Filter Kalender</h3>
            </div>
        </div>
        <div class="p-5">
            <form id="calendarFilterForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="filter_classroom" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select name="classroom" id="filter_classroom" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Pilih Kelas</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ request('classroom') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="filter_teacher" class="block text-sm font-medium text-gray-700 mb-1">Guru</label>
                    <select name="teacher" id="filter_teacher" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Pilih Guru</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="md:col-span-2 flex items-center">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    <button type="button" id="resetFilterBtn" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">
                        <i class="fas fa-times mr-2"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Weekly Calendar View -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                <div class="flex items-center mb-2 sm:mb-0">
                    <i class="fas fa-calendar-alt text-gray-500 mr-2"></i>
                    <h3 class="font-medium text-gray-700">Kalender Jadwal Mingguan</h3>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    <div id="calendarInfo" class="text-sm text-gray-500">
                        <!-- Will be populated dynamically -->
                    </div>
                    <div class="flex space-x-2">
                        <button id="printCalendarBtn" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center">
                            <i class="fas fa-print mr-1.5"></i> Print
                        </button>
                        <button id="exportPdfBtn" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors flex items-center">
                            <i class="fas fa-file-pdf mr-1.5"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-4">
            <div class="grid grid-cols-6 gap-2 calendar-header mb-2">
                <div class="text-center font-semibold bg-gray-100 py-2 rounded-md">Jam</div>
                <div class="text-center font-semibold bg-blue-100 py-2 rounded-md">Senin</div>
                <div class="text-center font-semibold bg-indigo-100 py-2 rounded-md">Selasa</div>
                <div class="text-center font-semibold bg-purple-100 py-2 rounded-md">Rabu</div>
                <div class="text-center font-semibold bg-pink-100 py-2 rounded-md">Kamis</div>
                <div class="text-center font-semibold bg-red-100 py-2 rounded-md">Jumat</div>
            </div>
            
            <div id="calendarContainer" class="relative min-h-[600px]">
                <div class="absolute inset-0 w-full h-full grid grid-cols-6 gap-2">
                    <!-- Time column -->
                    <div class="time-column">
                        @foreach($timeSlots as $slot)
                            <div class="time-slot h-24 border-b border-gray-100 relative text-xs text-gray-500 pl-1">
                                {{ $slot }}
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Day columns -->
                    @for($day = 1; $day <= 5; $day++)
                        <div class="day-column day-{{ $day }} relative border-l border-gray-100">
                            @foreach($timeSlots as $index => $slot)
                                <div class="time-slot h-24 border-b border-gray-100"></div>
                            @endforeach
                        </div>
                    @endfor
                </div>
                
                <div id="scheduleEvents" class="absolute inset-0">
                    <!-- Schedule events will be populated here dynamically -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .animate-fade-in {
        animation: fade-in 0.6s ease-in-out;
    }
    
    @keyframes fade-in {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .calendar-event {
        position: absolute;
        border-radius: 0.375rem;
        padding: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        transition: all 0.2s;
    }
    
    .calendar-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }
    
    .event-title {
        font-weight: 600;
        font-size: 0.75rem;
        line-height: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .event-details {
        font-size: 0.7rem;
        opacity: 0.8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarContainer = document.getElementById('calendarContainer');
        const scheduleEvents = document.getElementById('scheduleEvents');
        const calendarInfo = document.getElementById('calendarInfo');
        const filterForm = document.getElementById('calendarFilterForm');
        const resetBtn = document.getElementById('resetFilterBtn');
        const printBtn = document.getElementById('printCalendarBtn');
        const exportPdfBtn = document.getElementById('exportPdfBtn');
        
        // Store filter values
        let classroomId = '{{ request('classroom') }}';
        let teacherId = '{{ request('teacher') }}';
        
        // Initial load of schedules
        loadSchedules();
        
        // Handle filter form submission
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            classroomId = document.getElementById('filter_classroom').value;
            teacherId = document.getElementById('filter_teacher').value;
            loadSchedules();
        });
        
        // Reset filters
        resetBtn.addEventListener('click', function() {
            document.getElementById('filter_classroom').value = '';
            document.getElementById('filter_teacher').value = '';
            classroomId = '';
            teacherId = '';
            loadSchedules();
        });
        
        // Handle print button click
        printBtn.addEventListener('click', function() {
            printCalendar();
        });
        
        // Handle export button click
        exportPdfBtn.addEventListener('click', function() {
            exportToPdf();
        });
        
        function loadSchedules() {
            // Clear existing events
            scheduleEvents.innerHTML = '';
            
            // Set calendar info
            let infoText = 'Jadwal ';
            if (classroomId) {
                const classroomName = document.querySelector(`#filter_classroom option[value="${classroomId}"]`).textContent;
                infoText += `Kelas ${classroomName}`;
            } else if (teacherId) {
                const teacherName = document.querySelector(`#filter_teacher option[value="${teacherId}"]`).textContent;
                infoText += `Guru ${teacherName}`;
            } else {
                infoText = 'Semua Jadwal';
            }
            calendarInfo.textContent = infoText;
            
            // Fetch schedules
            fetch(`/admin/schedule/calendar-data?classroom=${classroomId || ''}&teacher=${teacherId || ''}`)
                .then(response => response.json())
                .then(data => {
                    renderSchedules(data.schedules);
                })
                .catch(error => {
                    console.error('Error loading schedules:', error);
                });
        }
        
        function renderSchedules(schedules) {
            // Generate schedule events
            schedules.forEach(schedule => {
                if (schedule.day > 5) return; // Only show Monday to Friday
                
                const startTime = new Date(`2000-01-01T${schedule.start_time}`);
                const endTime = new Date(`2000-01-01T${schedule.end_time}`);
                
                // Calculate position
                const startHour = startTime.getHours();
                const startMinute = startTime.getMinutes();
                const durationMinutes = (endTime - startTime) / (1000 * 60);
                
                const minutesSinceMidnight = startHour * 60 + startMinute;
                const topPosition = (minutesSinceMidnight - 7 * 60) / (12 * 60) * 100; // 7 AM to 7 PM = 12 hours
                const heightPercentage = durationMinutes / (12 * 60) * 100;
                
                // Create event element
                const eventElement = document.createElement('div');
                eventElement.className = `calendar-event event-subject-${schedule.subject_id}`;
                eventElement.style.top = `${topPosition}%`;
                eventElement.style.height = `${heightPercentage}%`;
                eventElement.style.left = `${(schedule.day / 6) * 100}%`;
                eventElement.style.width = 'calc(16.666% - 8px)';
                
                // Set background color based on subject
                const hue = (schedule.subject_id * 37) % 360; // Use prime number to distribute colors
                eventElement.style.backgroundColor = `hsl(${hue}, 85%, 90%)`;
                eventElement.style.borderLeft = `4px solid hsl(${hue}, 85%, 50%)`;
                
                // Add event content
                eventElement.innerHTML = `
                    <div class="event-title" style="color: hsl(${hue}, 85%, 30%);">${schedule.subject.name}</div>
                    <div class="event-details">${formatTime(schedule.start_time)} - ${formatTime(schedule.end_time)}</div>
                    <div class="event-details">${schedule.teacher.name}</div>
                    <div class="event-details">${schedule.classroom.name}</div>
                    <div class="event-details">${schedule.room || '-'}</div>
                `;
                
                // Add click handler to navigate to edit page
                eventElement.style.cursor = 'pointer';
                eventElement.addEventListener('click', function() {
                    window.location.href = `/admin/schedule/${schedule.id}/edit`;
                });
                
                // Add event to calendar
                scheduleEvents.appendChild(eventElement);
            });
        }
          function formatTime(timeStr) {
            const parts = timeStr.split(':');
            return `${parts[0]}:${parts[1]}`;
        }
        
        // Print calendar function
        function printCalendar() {
            // Save current page state
            const originalContent = document.body.innerHTML;
            const infoText = calendarInfo.textContent;
            
            // Create print-friendly version
            let printContent = `
                <div style="padding: 20px; font-family: Arial, sans-serif;">
                    <h1 style="text-align: center; margin-bottom: 20px;">Jadwal Sekolah - SMAN 1</h1>
                    <h2 style="text-align: center; margin-bottom: 30px;">${infoText}</h2>
                    <div style="margin-bottom: 30px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ccc; padding: 8px; background-color: #f3f4f6;">Jam</th>
                                    <th style="border: 1px solid #ccc; padding: 8px; background-color: #dbeafe;">Senin</th>
                                    <th style="border: 1px solid #ccc; padding: 8px; background-color: #e0e7ff;">Selasa</th>
                                    <th style="border: 1px solid #ccc; padding: 8px; background-color: #ede9fe;">Rabu</th>
                                    <th style="border: 1px solid #ccc; padding: 8px; background-color: #fce7f3;">Kamis</th>
                                    <th style="border: 1px solid #ccc; padding: 8px; background-color: #fee2e2;">Jumat</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            
            // Get schedules for printing
            let fetchUrl = `/admin/schedule/calendar-data?`;
            if (classroomId) fetchUrl += `classroom=${classroomId}&`;
            if (teacherId) fetchUrl += `teacher=${teacherId}&`;
            
            fetch(fetchUrl)
                .then(response => response.json())
                .then(data => {
                    const schedules = data.schedules;
                    const timeSlots = [];
                    
                    // Generate time slots from 7 AM to 7 PM
                    for (let hour = 7; hour <= 19; hour++) {
                        timeSlots.push(hour < 10 ? `0${hour}:00` : `${hour}:00`);
                    }
                    
                    // Generate schedule table rows
                    timeSlots.forEach(timeSlot => {
                        printContent += `<tr>`;
                        printContent += `<td style="border: 1px solid #ccc; padding: 8px;">${timeSlot}</td>`;
                        
                        // For each day of the week (1-5, Monday to Friday)
                        for (let day = 1; day <= 5; day++) {
                            let cellContent = '';
                            
                            // Find schedules for this day and time slot
                            const daySchedules = schedules.filter(s => {
                                if (s.day != day) return false;
                                
                                const slotTime = new Date(`2000-01-01T${timeSlot}`);
                                const startTime = new Date(`2000-01-01T${s.start_time}`);
                                const endTime = new Date(`2000-01-01T${s.end_time}`);
                                
                                // Check if this time slot falls within the schedule time
                                return slotTime >= startTime && slotTime < endTime;
                            });
                            
                            // Add schedule info to the cell
                            if (daySchedules.length > 0) {
                                daySchedules.forEach(s => {
                                    cellContent += `
                                        <div style="font-size: 12px; margin-bottom: 4px;">
                                            <strong>${s.subject.name}</strong><br>
                                            ${formatTime(s.start_time)} - ${formatTime(s.end_time)}<br>
                                            ${s.teacher.name}<br>
                                            ${s.classroom.name}
                                        </div>
                                    `;
                                });
                            }
                            
                            printContent += `<td style="border: 1px solid #ccc; padding: 8px;">${cellContent}</td>`;
                        }
                        
                        printContent += `</tr>`;
                    });
                    
                    printContent += `
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align: center; font-size: 12px; margin-top: 20px;">
                        <p>© SMAN 1 - Jadwal dicetak pada ${new Date().toLocaleString()}</p>
                    </div>
                </div>
                `;
                
                // Replace page content with print version
                document.body.innerHTML = printContent;
                
                // Print
                window.print();
                
                // Restore original content
                document.body.innerHTML = originalContent;
                
                // Reload scripts and events
                loadSchedules();
                
                // Reattach event listeners
                document.getElementById('calendarFilterForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    classroomId = document.getElementById('filter_classroom').value;
                    teacherId = document.getElementById('filter_teacher').value;
                    loadSchedules();
                });
                
                document.getElementById('resetFilterBtn').addEventListener('click', function() {
                    document.getElementById('filter_classroom').value = '';
                    document.getElementById('filter_teacher').value = '';
                    classroomId = '';
                    teacherId = '';
                    loadSchedules();
                });
                
                document.getElementById('printCalendarBtn').addEventListener('click', function() {
                    printCalendar();
                });
                
                document.getElementById('exportPdfBtn').addEventListener('click', function() {
                    exportToPdf();
                });
            })
            .catch(error => {
                console.error('Error loading schedules for print:', error);
            });
        }
        
        // Export to PDF function
        function exportToPdf() {
            // Since client-side PDF generation is complex, we'll use a simple approach:
            // Just show a message that in a real application this would generate a PDF
            alert('Di aplikasi produksi, fungsi ini akan mengekspor jadwal ke PDF. Silakan gunakan fitur print untuk saat ini.');
            
            // In a real application, you would use a library like jsPDF, 
            // or make a backend request to generate the PDF server-side
        }
    });
</script>
@endpush
