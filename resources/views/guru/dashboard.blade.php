</div>
                </div>
            </div>
        </div>

        <!-- Calendar Section -->
        <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden border border-gray-100/50">
            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Kalender Jadwal</h3>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button id="prev-month" class="p-1.5 bg-white rounded-lg text-gray-600 hover:bg-gray-100 border border-gray-200">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="current-month-year" class="text-sm font-medium text-gray-700 w-32 text-center"></span>
                        <button id="next-month" class="p-1.5 bg-white rounded-lg text-gray-600 hover:bg-gray-100 border border-gray-200">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="calendar-container overflow-x-auto">
                    <div id="calendar-header" class="grid grid-cols-7 gap-1 mb-2">
                        <div class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider py-2 bg-gray-50 rounded-md">Min</div>
                        <div class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider py-2 bg-gray-50 rounded-md">Sen</div>
                        <div class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider py-2 bg-gray-50 rounded-md">Sel</div>
                        <div class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider py-2 bg-gray-50 rounded-md">Rab</div>
                        <div class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider py-2 bg-gray-50 rounded-md">Kam</div>
                        <div class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider py-2 bg-gray-50 rounded-md">Jum</div>
                        <div class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider py-2 bg-gray-50 rounded-md">Sab</div>
                    </div>
                    <div id="calendar-body" class="grid grid-cols-7 gap-1"></div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50">
            <div class="p-5 bg-gray-50 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <i class="fas fa-history text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($recentActivities ?? [] as $activity)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                <i class="fas {{ $activity->icon ?? 'fa-bell' }}"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-900">{{ $activity->title ?? 'Aktivitas' }}</div>
                                <div class="text-xs text-gray-500">{{ $activity->description ?? 'Deskripsi aktivitas' }}</div>
                            </div>
                            <div class="text-xs text-gray-400">{{ $activity->created_at ?? now()->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
                
                @if(empty($recentActivities))
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 text-gray-500 mb-4">
                            <i class="fas fa-history text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-800 mb-1">Belum ada aktivitas</h4>
                        <p class="text-gray-500">Aktivitas terbaru Anda akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calendar implementation
        const calendarBody = document.getElementById('calendar-body');
        const currentMonthYearEl = document.getElementById('current-month-year');
        const prevMonthBtn = document.getElementById('prev-month');
        const nextMonthBtn = document.getElementById('next-month');
        
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        
        // Teacher schedules data
        const schedules = @json($schedules ?? []);
        
        // Indonesian month names
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        // Indonesian day names
        const dayIndices = {
            'Minggu': 0,
            'Senin': 1,
            'Selasa': 2,
            'Rabu': 3,
            'Kamis': 4,
            'Jumat': 5,
            'Sabtu': 6
        };
        
        // Update calendar for the current month
        function updateCalendar() {
            // Update header
            currentMonthYearEl.textContent = `${monthNames[currentMonth]} ${currentYear}`;
            
            // Clear previous calendar
            calendarBody.innerHTML = '';
            
            // Get first day of the month
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            
            // Get number of days in the month
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            
            // Calculate required rows
            const totalSlots = Math.ceil((firstDay + daysInMonth) / 7) * 7;
            
            // Create calendar days
            for (let i = 0; i < totalSlots; i++) {
                const dayCell = document.createElement('div');
                dayCell.classList.add('calendar-cell', 'min-h-[80px]', 'border', 'rounded-md', 'relative');
                
                const dayIndex = i - firstDay + 1;
                
                if (i >= firstDay && i < firstDay + daysInMonth) {
                    // Valid days in current month
                    const isToday = dayIndex === currentDate.getDate() && 
                                   currentMonth === currentDate.getMonth() && 
                                   currentYear === currentDate.getFullYear();
                    
                    // Add cell content
                    dayCell.innerHTML = `
                        <div class="p-1">
                            <div class="flex justify-between items-center">
                                <span class="text-sm ${isToday ? 'font-bold text-blue-600' : 'font-medium text-gray-700'}">${dayIndex}</span>
                                ${isToday ? '<span class="h-2 w-2 bg-blue-500 rounded-full"></span>' : ''}
                            </div>
                            <div class="schedule-container mt-1 space-y-1"></div>
                        </div>
                    `;
                    
                    // If today, highlight the cell
                    if (isToday) {
                        dayCell.classList.add('bg-blue-50', 'border-blue-200');
                    } else {
                        dayCell.classList.add('bg-white', 'border-gray-200', 'hover:bg-gray-50');
                    }
                    
                    // Add day of week for calculating schedules
                    dayCell.dataset.date = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(dayIndex).padStart(2, '0')}`;
                    dayCell.dataset.day = new Date(currentYear, currentMonth, dayIndex).getDay();
                } else {
                    // Days from previous/next month
                    dayCell.classList.add('bg-gray-50', 'border-gray-100', 'text-gray-300');
                }
                
                calendarBody.appendChild(dayCell);
            }
            
            // Add schedules to calendar
            addSchedulesToCalendar();
        }
        
        // Add teacher schedules to the calendar
        function addSchedulesToCalendar() {
            // Map day name to day index
            const dayNameToIndex = {
                'Senin': 1, 'Selasa': 2, 'Rabu': 3, 'Kamis': 4, 'Jumat': 5, 'Sabtu': 6, 'Minggu': 0
            };
            
            // Group schedules by day
            const schedulesByDay = {};
            schedules.forEach(schedule => {
                if (!schedulesByDay[schedule.day]) {
                    schedulesByDay[schedule.day] = [];
                }
                schedulesByDay[schedule.day].push(schedule);
            });
            
            // Add schedules to calendar cells
            document.querySelectorAll('.calendar-cell').forEach(cell => {
                if (!cell.dataset.day) return;
                
                const cellDayIndex = parseInt(cell.dataset.day);
                
                // Find matching schedules for this day
                for (const [day, daySchedules] of Object.entries(schedulesByDay)) {
                    if (dayNameToIndex[day] === cellDayIndex) {
                        const scheduleContainer = cell.querySelector('.schedule-container');
                        
                        // Limit to 3 visible schedules to avoid overflow
                        const visibleSchedules = daySchedules.slice(0, 2);
                        const remainingCount = Math.max(0, daySchedules.length - 2);
                        
                        visibleSchedules.forEach(schedule => {
                            const scheduleEl = document.createElement('div');
                            scheduleEl.className = `text-xs p-1 rounded truncate ${getScheduleColorClass(schedule.subject_id)}`;
                            scheduleEl.title = `${schedule.subject_name}: ${schedule.start_time} - ${schedule.end_time}, ${schedule.classroom_name}`;
                            scheduleEl.textContent = `${schedule.start_time} ${schedule.subject_name}`;
                            scheduleContainer.appendChild(scheduleEl);
                        });
                        
                        if (remainingCount > 0) {
                            const moreEl = document.createElement('div');
                            moreEl.className = 'text-xs text-gray-500 text-center mt-1';
                            moreEl.textContent = `+ ${remainingCount} lainnya`;
                            scheduleContainer.appendChild(moreEl);
                        }
                    }
                }
            });
        }
        
        // Get a color class based on subject ID for visual differentiation
        function getScheduleColorClass(subjectId) {
            const colorClasses = [
                'bg-blue-100 text-blue-800',
                'bg-green-100 text-green-800',
                'bg-yellow-100 text-yellow-800',
                'bg-purple-100 text-purple-800',
                'bg-pink-100 text-pink-800',
                'bg-indigo-100 text-indigo-800',
                'bg-red-100 text-red-800',
                'bg-orange-100 text-orange-800'
            ];
            
            // Use modulo to ensure we always get a valid color regardless of subject ID
            const colorIndex = (subjectId % colorClasses.length);
            return colorClasses[colorIndex];
        }
        
        // Navigate to previous month
        prevMonthBtn.addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            updateCalendar();
        });
        
        // Navigate to next month
        nextMonthBtn.addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            updateCalendar();
        });
        
        // Initialize calendar
        updateCalendar();
    });
</script>
@endpush