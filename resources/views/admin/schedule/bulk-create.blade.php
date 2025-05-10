@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal Massal')

@section('header', 'Tambah Jadwal Massal')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-calendar-plus text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Tambah Jadwal Massal</h2>
            <p class="text-blue-100">Tambahkan beberapa jadwal sekaligus untuk efisiensi</p>
        </div>
    </div>

    <div class="mb-6">
        <a href="{{ route('admin.schedule.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Daftar Jadwal</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-6">
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('admin.schedule.bulk-store') }}" method="POST" class="animate-fade-in" id="bulkScheduleForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="form-group">
                        <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-users text-gray-400"></i>
                            </div>
                            <select name="classroom_id" id="classroom_id" 
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow duration-300" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('classroom_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div id="scheduleContainer">
                    <!-- Schedule Entry 1 -->
                    <div class="schedule-entry bg-gray-50 p-5 rounded-lg mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-medium text-gray-700">Jadwal 1</h3>
                            <button type="button" class="text-red-500 hover:text-red-700 focus:outline-none delete-entry hidden">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label for="entries[0][subject_id]" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                                <select name="entries[0][subject_id]" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="entries[0][teacher_id]" class="block text-sm font-medium text-gray-700 mb-1">Guru</label>
                                <select name="entries[0][teacher_id]" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <option value="">Pilih Guru</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="entries[0][day]" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                                <select name="entries[0][day]" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="1">Senin</option>
                                    <option value="2">Selasa</option>
                                    <option value="3">Rabu</option>
                                    <option value="4">Kamis</option>
                                    <option value="5">Jumat</option>
                                    <option value="6">Sabtu</option>
                                    <option value="7">Minggu</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="entries[0][start_time]" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                                <input type="time" name="entries[0][start_time]" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="entries[0][end_time]" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                                <input type="time" name="entries[0][end_time]" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="entries[0][room]" class="block text-sm font-medium text-gray-700 mb-1">Ruangan (Opsional)</label>
                                <input type="text" name="entries[0][room]" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                    placeholder="Contoh: R. 101, Lab Komputer">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 mb-6">
                    <button type="button" id="addScheduleBtn" class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i> Tambah Jadwal Lain
                    </button>
                </div>
                
                <div class="border-t border-gray-200 mt-8 pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('admin.schedule.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" class="ml-3 px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan Semua Jadwal
                        </button>
                    </div>
                </div>
            </form>
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
    
    .form-group:focus-within label {
        color: #3B82F6;
    }
    
    .schedule-entry {
        transition: all 0.3s ease;
    }
    
    .schedule-entry:hover {
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    
    .delete-entry {
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .schedule-entry:hover .delete-entry {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scheduleContainer = document.getElementById('scheduleContainer');
        const addScheduleBtn = document.getElementById('addScheduleBtn');
        let scheduleCount = 1;
        
        addScheduleBtn.addEventListener('click', function() {
            scheduleCount++;
            
            // Create a new schedule entry
            const newSchedule = document.createElement('div');
            newSchedule.className = 'schedule-entry bg-gray-50 p-5 rounded-lg mb-4';
            
            // Create heading and delete button
            const header = document.createElement('div');
            header.className = 'flex justify-between items-center mb-3';
            header.innerHTML = `
                <h3 class="text-lg font-medium text-gray-700">Jadwal ${scheduleCount}</h3>
                <button type="button" class="text-red-500 hover:text-red-700 focus:outline-none delete-entry">
                    <i class="fas fa-trash-alt"></i> Hapus
                </button>
            `;
            
            // Create form fields
            const formFields = document.createElement('div');
            formFields.className = 'grid grid-cols-1 md:grid-cols-3 gap-4';
            
            // Subject
            const subjectField = `
                <div class="form-group">
                    <label for="entries[${scheduleCount - 1}][subject_id]" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select name="entries[${scheduleCount - 1}][subject_id]" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">Pilih Mata Pelajaran</option>
                        ${Array.from(document.querySelectorAll('select[name="entries[0][subject_id]"] option')).map(opt => 
                            `<option value="${opt.value}">${opt.textContent}</option>`
                        ).join('')}
                    </select>
                </div>
            `;
            
            // Teacher
            const teacherField = `
                <div class="form-group">
                    <label for="entries[${scheduleCount - 1}][teacher_id]" class="block text-sm font-medium text-gray-700 mb-1">Guru</label>
                    <select name="entries[${scheduleCount - 1}][teacher_id]" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">Pilih Guru</option>
                        ${Array.from(document.querySelectorAll('select[name="entries[0][teacher_id]"] option')).map(opt => 
                            `<option value="${opt.value}">${opt.textContent}</option>`
                        ).join('')}
                    </select>
                </div>
            `;
            
            // Day
            const dayField = `
                <div class="form-group">
                    <label for="entries[${scheduleCount - 1}][day]" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select name="entries[${scheduleCount - 1}][day]" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">Pilih Hari</option>
                        <option value="1">Senin</option>
                        <option value="2">Selasa</option>
                        <option value="3">Rabu</option>
                        <option value="4">Kamis</option>
                        <option value="5">Jumat</option>
                        <option value="6">Sabtu</option>
                        <option value="7">Minggu</option>
                    </select>
                </div>
            `;
            
            // Start Time
            const startTimeField = `
                <div class="form-group">
                    <label for="entries[${scheduleCount - 1}][start_time]" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                    <input type="time" name="entries[${scheduleCount - 1}][start_time]" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                </div>
            `;
            
            // End Time
            const endTimeField = `
                <div class="form-group">
                    <label for="entries[${scheduleCount - 1}][end_time]" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                    <input type="time" name="entries[${scheduleCount - 1}][end_time]" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                </div>
            `;
            
            // Room
            const roomField = `
                <div class="form-group">
                    <label for="entries[${scheduleCount - 1}][room]" class="block text-sm font-medium text-gray-700 mb-1">Ruangan (Opsional)</label>
                    <input type="text" name="entries[${scheduleCount - 1}][room]" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                        placeholder="Contoh: R. 101, Lab Komputer">
                </div>
            `;
            
            formFields.innerHTML = subjectField + teacherField + dayField + startTimeField + endTimeField + roomField;
            
            // Add elements to the new schedule entry
            newSchedule.appendChild(header);
            newSchedule.appendChild(formFields);
            
            // Add the new schedule entry to the container
            scheduleContainer.appendChild(newSchedule);
            
            // Add event listener to delete button
            newSchedule.querySelector('.delete-entry').addEventListener('click', function() {
                newSchedule.remove();
                
                // Update schedule numbers
                const entries = document.querySelectorAll('.schedule-entry');
                entries.forEach((entry, index) => {
                    entry.querySelector('h3').textContent = `Jadwal ${index + 1}`;
                });
                
                scheduleCount = entries.length;
                
                // Show/hide delete buttons if there is only one schedule left
                if (scheduleCount === 1) {
                    document.querySelector('.delete-entry').classList.add('hidden');
                } else {
                    document.querySelectorAll('.delete-entry').forEach(btn => btn.classList.remove('hidden'));
                }
            });
            
            // Show all delete buttons when we have more than one schedule
            document.querySelectorAll('.delete-entry').forEach(btn => btn.classList.remove('hidden'));
            
            // Animate the new entry
            setTimeout(() => {
                newSchedule.classList.add('animate-fade-in');
            }, 10);
        });
        
        // Validate time fields (end time must be after start time)
        document.getElementById('bulkScheduleForm').addEventListener('submit', function(event) {
            const scheduleEntries = document.querySelectorAll('.schedule-entry');
            let isValid = true;
            
            scheduleEntries.forEach((entry, index) => {
                const startTime = entry.querySelector(`input[name="entries[${index}][start_time]"]`).value;
                const endTime = entry.querySelector(`input[name="entries[${index}][end_time]"]`).value;
                
                if (startTime && endTime && startTime >= endTime) {
                    alert(`Jadwal ${index + 1}: Waktu selesai harus setelah waktu mulai.`);
                    isValid = false;
                }
            });
            
            if (!isValid) {
                event.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush
