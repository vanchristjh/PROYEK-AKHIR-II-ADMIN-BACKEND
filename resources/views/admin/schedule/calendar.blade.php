@extends('layouts.dashboard')

@section('title', 'Kalender Jadwal')

@section('header', 'Kalender Jadwal Pelajaran')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.schedule.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Jadwal
        </a>
    </div>
    
    <!-- Classroom Selector -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 mb-8 hover:shadow-lg transition-shadow duration-300">
        <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-school mr-2 text-blue-700"></i>
                Pilih Kelas
            </h3>
        </div>
        
        <div class="p-5">
            <form action="{{ route('admin.schedule.calendar') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex-grow">
                    <select name="classroom_id" id="classroom_id" class="form-select rounded-lg border-gray-300 w-full focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-all duration-300 flex items-center shadow-sm hover:shadow font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i> Tampilkan Jadwal
                </button>
            </form>
        </div>
    </div>
    
    @if($selectedClassroom)
        <!-- Schedule Calendar -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
            <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-calendar-week text-indigo-600 mr-2"></i>
                        Jadwal Kelas {{ $selectedClassroom->name }}
                    </h3>
                    <a href="{{ route('admin.schedule.create') }}?classroom_id={{ $selectedClassroom->id }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-sm hover:shadow flex items-center font-medium">
                        <i class="fas fa-plus mr-2"></i> Tambah Jadwal
                    </a>
                </div>
            </div>
            
            <div class="p-5">
                @if(count($schedules) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-100 to-gray-50">
                                    <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200 border-r">
                                        <div class="flex items-center">
                                            <i class="far fa-clock mr-1 text-gray-500"></i> Waktu
                                        </div>
                                    </th>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $index => $day)
                                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200 {{ $index < 5 ? 'border-r' : '' }}">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-day mr-1 text-blue-500"></i> {{ $day }}
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $timeSlots = [];
                                    foreach ($schedules as $day => $daySchedules) {
                                        foreach ($daySchedules as $schedule) {
                                            $timeSlots[$schedule->start_time . '-' . $schedule->end_time] = true;
                                        }
                                    }
                                    ksort($timeSlots);
                                @endphp
                                
                                @foreach (array_keys($timeSlots) as $index => $timeSlot)
                                    @php
                                        list($start, $end) = explode('-', $timeSlot);
                                        $displayTime = substr($start, 0, 5) . ' - ' . substr($end, 0, 5);
                                    @endphp
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors duration-150">
                                        <td class="py-3 px-4 border-b border-r border-gray-200 text-xs font-medium text-gray-800">
                                            <div class="flex items-center">
                                                <i class="far fa-clock mr-1.5 text-indigo-500"></i>
                                                {{ $displayTime }}
                                            </div>
                                        </td>
                                        
                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $index => $day)
                                            <td class="py-3 px-4 border-b {{ $index < 5 ? 'border-r' : '' }} border-gray-200">
                                                @if(isset($schedules[$day]))
                                                    @php
                                                        $found = false;
                                                        foreach($schedules[$day] as $schedule) {
                                                            if($schedule->start_time . '-' . $schedule->end_time == $timeSlot) {
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    @if($found)
                                                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-3 text-xs shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5">
                                                            <div class="font-medium text-blue-800 flex items-center">
                                                                <i class="fas fa-book-reader mr-1.5 text-indigo-600"></i>
                                                                {{ $schedule->subject->name ?? 'N/A' }}
                                                            </div>
                                                            <div class="text-blue-600 mt-1.5 flex items-center">
                                                                <i class="fas fa-user-tie mr-1 text-blue-500"></i>
                                                                {{ $schedule->teacher->name ?? 'N/A' }}
                                                            </div>
                                                            <div class="flex justify-between items-center mt-3 pt-2 border-t border-blue-100">
                                                                <a href="{{ route('admin.schedule.show', $schedule) }}" 
                                                                   class="text-blue-700 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-1.5 rounded-md transition-colors duration-200"
                                                                   title="Lihat Detail">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <div class="flex space-x-2">
                                                                    <a href="{{ route('admin.schedule.edit', $schedule) }}" 
                                                                       class="text-yellow-600 hover:text-yellow-800 bg-yellow-50 hover:bg-yellow-100 p-1.5 rounded-md transition-colors duration-200"
                                                                       title="Edit Jadwal">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('admin.schedule.destroy', $schedule) }}" method="POST" class="inline-block delete-form">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" 
                                                                                class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition-colors duration-200"
                                                                                title="Hapus Jadwal">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 text-sm bg-blue-50 border border-blue-100 rounded-lg p-3 text-gray-600 flex items-start">
                        <i class="fas fa-info-circle mr-2 text-blue-500 mt-0.5"></i>
                        <p>Klik ikon mata untuk melihat detail, ikon pensil untuk mengedit, dan ikon sampah untuk menghapus jadwal.</p>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-600 mb-1">Belum ada jadwal untuk kelas ini</h3>
                        <p class="text-gray-400 mb-5">Silahkan tambahkan jadwal terlebih dahulu</p>
                        <a href="{{ route('admin.schedule.create') }}?classroom_id={{ $selectedClassroom->id }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm hover:shadow transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i> Tambah Jadwal
                        </a>
                    </div>
                @endif
            </div>
            
            @if(count($schedules) > 0)
                <div class="p-5 bg-gray-50 border-t border-gray-200 flex flex-wrap items-center justify-between gap-3">
                    <div class="text-sm text-gray-600 flex items-center">
                        <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>
                        <p>Total jadwal: <span class="font-medium">{{ $schedules->flatten()->count() }}</span></p>
                    </div>
                    <div>
                        <a href="{{ route('admin.schedule.export', ['classroom_id' => $selectedClassroom->id]) }}" 
                           class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-all duration-300 flex items-center inline-flex shadow-sm hover:shadow font-medium">
                            <i class="fas fa-file-export mr-2"></i> Export Jadwal
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 p-10 text-center hover:shadow-lg transition-shadow duration-300">
            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mb-5 shadow-inner">
                <i class="fas fa-calendar-alt text-blue-500 text-4xl"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-700 mb-3">Pilih Kelas Untuk Melihat Jadwal</h3>
            <p class="text-gray-500 mb-4 max-w-md mx-auto">Silahkan pilih kelas terlebih dahulu pada form di atas untuk menampilkan jadwal pelajaran</p>
        </div>
    @endif
    
    <!-- Schedule Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
            <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                <h3 class="text-md font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-book-open mr-2 text-blue-600"></i>
                    Informasi Mata Pelajaran
                </h3>
            </div>
            <div class="p-5">
                @if($selectedClassroom)
                    @php
                        $subjectCount = $schedules->flatten()->pluck('subject_id')->unique()->count();
                        $subjects = $schedules->flatten()->pluck('subject.name', 'subject_id')->unique();
                    @endphp
                    
                    <p class="text-sm text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-1.5 text-blue-500"></i>
                        Kelas {{ $selectedClassroom->name }} memiliki {{ $subjectCount }} mata pelajaran
                    </p>
                    
                    @if($subjectCount > 0)
                        <div class="space-y-2.5">
                            @foreach($subjects as $subjectId => $subjectName)
                                <div class="text-sm bg-gray-50 p-2.5 rounded-md border-l-3 border-blue-400 hover:bg-blue-50 transition-colors duration-200">
                                    {{ $subjectName }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="text-sm text-gray-500 text-center py-8 flex flex-col items-center">
                        <i class="fas fa-books text-gray-300 text-3xl mb-2"></i>
                        Pilih kelas untuk melihat informasi mata pelajaran
                    </div>
                @endif
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
            <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                <h3 class="text-md font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-chalkboard-teacher mr-2 text-green-600"></i>
                    Informasi Guru
                </h3>
            </div>
            <div class="p-5">
                @if($selectedClassroom)
                    @php
                        $teacherCount = $schedules->flatten()->pluck('teacher_id')->unique()->count();
                        $teachers = $schedules->flatten()->pluck('teacher.name', 'teacher_id')->unique();
                    @endphp
                    
                    <p class="text-sm text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-1.5 text-green-500"></i>
                        Kelas {{ $selectedClassroom->name }} diajar oleh {{ $teacherCount }} guru
                    </p>
                    
                    @if($teacherCount > 0)
                        <div class="space-y-2.5">
                            @foreach($teachers as $teacherId => $teacherName)
                                <div class="text-sm bg-gray-50 p-2.5 rounded-md border-l-3 border-green-400 hover:bg-green-50 transition-colors duration-200">
                                    {{ $teacherName }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="text-sm text-gray-500 text-center py-8 flex flex-col items-center">
                        <i class="fas fa-user-tie text-gray-300 text-3xl mb-2"></i>
                        Pilih kelas untuk melihat informasi guru
                    </div>
                @endif
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
            <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                <h3 class="text-md font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-clock mr-2 text-purple-600"></i>
                    Ringkasan Jadwal
                </h3>
            </div>
            <div class="p-5">
                @if($selectedClassroom)
                    @php
                        $dayCount = $schedules->count();
                        $totalHours = 0;
                        
                        foreach ($schedules as $day => $daySchedules) {
                            foreach ($daySchedules as $schedule) {
                                $start = \Carbon\Carbon::parse($schedule->start_time);
                                $end = \Carbon\Carbon::parse($schedule->end_time);
                                $totalHours += $end->diffInMinutes($start) / 60;
                            }
                        }
                    @endphp
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm p-2.5 bg-gray-50 rounded-lg hover:bg-purple-50 transition-colors duration-200">
                            <span class="text-gray-600 flex items-center"><i class="fas fa-calendar-week text-purple-500 mr-2"></i> Total Hari:</span>
                            <span class="font-medium text-gray-800 bg-white py-1 px-2.5 rounded-md shadow-sm">{{ $dayCount }} hari</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-sm p-2.5 bg-gray-50 rounded-lg hover:bg-purple-50 transition-colors duration-200">
                            <span class="text-gray-600 flex items-center"><i class="fas fa-calendar-check text-purple-500 mr-2"></i> Total Jadwal:</span>
                            <span class="font-medium text-gray-800 bg-white py-1 px-2.5 rounded-md shadow-sm">{{ $schedules->flatten()->count() }} sesi</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-sm p-2.5 bg-gray-50 rounded-lg hover:bg-purple-50 transition-colors duration-200">
                            <span class="text-gray-600 flex items-center"><i class="fas fa-clock text-purple-500 mr-2"></i> Total Jam:</span>
                            <span class="font-medium text-gray-800 bg-white py-1 px-2.5 rounded-md shadow-sm">{{ number_format($totalHours, 1) }} jam</span>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-gray-500 text-center py-8 flex flex-col items-center">
                        <i class="fas fa-chart-pie text-gray-300 text-3xl mb-2"></i>
                        Pilih kelas untuk melihat ringkasan jadwal
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Confirm delete
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Extract schedule information
            const scheduleCard = this.closest('.bg-gradient-to-br');
            const subjectEl = scheduleCard.querySelector('.font-medium');
            const teacherEl = scheduleCard.querySelector('.text-blue-600');
            
            const subject = subjectEl ? subjectEl.textContent.trim() : 'N/A';
            const teacher = teacherEl ? teacherEl.textContent.trim() : 'N/A';
            const classroom = "{{ $selectedClassroom ? $selectedClassroom->name : 'N/A' }}";
            
            const confirmation = confirm(
                `Apakah Anda yakin ingin menghapus jadwal ini?\n\n` +
                `Mata Pelajaran: ${subject}\n` +
                `Guru: ${teacher}\n` +
                `Kelas: ${classroom}\n\n` +
                `Tindakan ini tidak dapat dibatalkan.`
            );
            
            if (confirmation) {
                // Show loading state
                const deleteBtn = this.querySelector('button');
                if (deleteBtn) {
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    deleteBtn.disabled = true;
                }
                
                // Visual feedback
                scheduleCard.style.opacity = "0.5";
                scheduleCard.style.transition = "all 0.3s";
                scheduleCard.style.transform = "scale(0.95)";
                
                // Create a fade-out animation
                const fadeOut = scheduleCard.animate(
                    [
                        { opacity: 0.5, transform: 'scale(0.95)' },
                        { opacity: 0, transform: 'scale(0.9)' }
                    ],
                    {
                        duration: 500,
                        fill: 'forwards',
                        easing: 'ease-out'
                    }
                );
                
                // Show notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-3 rounded-lg shadow-lg z-50 flex items-center';
                notification.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus jadwal...';
                document.body.appendChild(notification);
                
                // Handle submission
                try {
                    this.submit();
                } catch (error) {
                    console.error('Error during delete:', error);
                    fadeOut.cancel();
                    scheduleCard.style.opacity = "1";
                    scheduleCard.style.transform = "scale(1)";
                    
                    if (deleteBtn) {
                        deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
                        deleteBtn.disabled = false;
                    }
                    
                    notification.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> Gagal menghapus jadwal';
                    notification.className = 'fixed top-4 right-4 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg z-50 flex items-center';
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                }
            }
        });
    });
    
    // Auto-submit form when select changes
    document.getElementById('classroom_id')?.addEventListener('change', function() {
        if (this.value) {
            // Show loading indicator
            const submitBtn = this.form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memuat...';
                submitBtn.disabled = true;
            }
            
            // Add loading overlay
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'fixed inset-0 bg-white bg-opacity-70 flex items-center justify-center z-40';
            loadingOverlay.innerHTML = `
                <div class="bg-white p-5 rounded-lg shadow-lg text-center">
                    <i class="fas fa-spinner fa-spin text-blue-600 text-3xl mb-2"></i>
                    <p class="text-gray-700">Memuat jadwal kelas...</p>
                </div>
            `;
            document.body.appendChild(loadingOverlay);
            
            this.form.submit();
        }
    });
    
    // Add highlight effect for just-added schedules
    document.addEventListener('DOMContentLoaded', function() {
        // Check if there's a new schedule flag in URL or session storage
        const urlParams = new URLSearchParams(window.location.search);
        const hasNewSchedule = urlParams.get('new_schedule') || sessionStorage.getItem('newScheduleAdded');
        
        if (hasNewSchedule) {
            const scheduleCards = document.querySelectorAll('.bg-gradient-to-br');
            // Highlight the newest card (assuming it's the last one)
            if (scheduleCards.length > 0) {
                const lastCard = scheduleCards[scheduleCards.length - 1];
                lastCard.classList.add('animate-pulse');
                lastCard.style.boxShadow = '0 0 0 3px rgba(99, 102, 241, 0.5)';
                
                // Remove highlight after 3 seconds
                setTimeout(() => {
                    lastCard.classList.remove('animate-pulse');
                    lastCard.style.boxShadow = '';
                    // Add a nice transition out
                    lastCard.style.transition = 'all 0.5s ease-out';
                }, 3000);
            }
            
            // Clear the flag
            sessionStorage.removeItem('newScheduleAdded');
        }
        
        // Handle overlapping schedules - highlighting conflicts
        const checkForConflicts = () => {
            const timeSlots = document.querySelectorAll('tr');
            
            timeSlots.forEach(row => {
                const cells = row.querySelectorAll('td:not(:first-child)');
                
                cells.forEach(cell => {
                    // Check if cell has more than one schedule
                    const scheduleCards = cell.querySelectorAll('.bg-gradient-to-br');
                    
                    if (scheduleCards.length > 1) {
                        // Mark conflicts
                        scheduleCards.forEach(card => {
                            card.classList.remove('bg-gradient-to-br', 'from-blue-50', 'to-indigo-50', 'border-blue-200');
                            card.classList.add('bg-gradient-to-br', 'from-red-50', 'to-red-100', 'border-red-300');
                            
                            // Add conflict indicator if not already present
                            if (!card.querySelector('.conflict-indicator')) {
                                const indicator = document.createElement('div');
                                indicator.className = 'text-xs text-red-700 mt-2 pt-1.5 border-t border-red-200 conflict-indicator flex items-center';
                                indicator.innerHTML = '<i class="fas fa-exclamation-triangle mr-1.5"></i> Konflik jadwal';
                                card.appendChild(indicator);
                            }
                        });
                    }
                });
            });
        };
        
        // Run conflict check
        setTimeout(checkForConflicts, 500);
    });
</script>

<!-- Add custom CSS for additional styling -->
<style>
    .border-l-3 {
        border-left-width: 3px;
    }
    
    /* Add some smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Custom animation for table hover */
    tr:hover td {
        background-color: rgba(243, 244, 246, 0.5);
    }
    
    /* Day column highlight on hover */
    th:hover {
        background-color: rgba(219, 234, 254, 0.3);
    }
    
    /* Delete button animation */
    .delete-form button {
        transition: all 0.2s;
    }
    
    .delete-form button:hover {
        transform: scale(1.1);
    }
    
    /* Visual feedback for actions */
    @keyframes fadeInOut {
        0% { opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { opacity: 0; }
    }
</style>
@endpush
