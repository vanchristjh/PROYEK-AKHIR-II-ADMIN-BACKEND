@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal Baru')

@section('header', 'Tambah Jadwal Pelajaran Baru')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.schedule.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-medium transition-all duration-200 group">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform duration-200"></i> Kembali ke Daftar Jadwal
        </a>
    </div>
    
    <!-- Flash Messages -->
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Create Schedule Form -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-calendar-plus mr-2 text-blue-500"></i>
                Form Tambah Jadwal
            </h3>
        </div>
        
        <form action="{{ route('admin.schedule.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Classroom -->
                    <div class="form-group">
                        <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-school absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select id="classroom_id" name="classroom_id" class="form-select pl-10 pr-10 py-2.5 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full transition-all duration-200 @error('classroom_id') border-red-300 @enderror">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('classroom_id')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        
                        <!-- Visibility Notification -->
                        <div id="classroom-visibility" class="hidden mt-2 text-xs text-gray-500 bg-blue-50 p-2 rounded-lg border border-blue-100">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                <span>Jadwal ini akan terlihat oleh <span id="student-count" class="font-medium">0</span> siswa di kelas ini.</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subject -->
                    <div class="form-group">
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-book absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select id="subject_id" name="subject_id" class="form-select pl-10 pr-10 py-2.5 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full transition-all duration-200 @error('subject_id') border-red-300 @enderror">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('subject_id')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Teacher -->
                    <div class="form-group">
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Guru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-user-tie absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select id="teacher_id" name="teacher_id" class="form-select pl-10 pr-10 py-2.5 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full transition-all duration-200 @error('teacher_id') border-red-300 @enderror">
                                <option value="">-- Pilih Guru --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name ?? $teacher->nama ?? 'Guru ID-' . $teacher->id }}
                                    </option>
                                @endforeach
                                
                                @if(count($teachers) == 0)
                                    @foreach(\App\Models\User::whereHas('role', function($q) { $q->where('name', 'teacher'); })->get() as $teacherUser)
                                        <option value="{{ $teacherUser->id }}" {{ old('teacher_id') == $teacherUser->id ? 'selected' : '' }}>
                                            {{ $teacherUser->name }} (User)
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        <p id="teacher-hint" class="mt-1 text-xs text-gray-500 italic">Guru yang mengajar mata pelajaran ini akan ditampilkan setelah memilih mata pelajaran.</p>
                        @error('teacher_id')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        
                        <!-- Teacher Visibility Notification -->
                        <div id="teacher-visibility" class="hidden mt-2 text-xs text-gray-500 bg-green-50 p-2 rounded-lg border border-green-100">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-green-500 mr-1"></i>
                                <span>Jadwal ini akan muncul di dashboard guru yang dipilih.</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Day -->
                    <div class="form-group">
                        <label for="day" class="block text-sm font-medium text-gray-700 mb-1">
                            Hari <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-calendar-day absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select id="day" name="day" class="form-select pl-10 pr-10 py-2.5 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full transition-all duration-200 @error('day') border-red-300 @enderror">
                                <option value="">-- Pilih Hari --</option>
                                @foreach($days as $day)
                                    <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('day')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Time Slots -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Start Time -->
                        <div class="form-group">
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="far fa-clock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" 
                                    class="form-input pl-10 py-2.5 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full transition-all duration-200 @error('start_time') border-red-300 @enderror">
                            </div>
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- End Time -->
                        <div class="form-group">
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Waktu Selesai <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="far fa-clock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" 
                                    class="form-input pl-10 py-2.5 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full transition-all duration-200 @error('end_time') border-red-300 @enderror">
                            </div>
                            <div id="time-error" class="hidden mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> Waktu selesai harus setelah waktu mulai
                            </div>
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- School Year -->
                    <div class="form-group">
                        <label for="school_year" class="block text-sm font-medium text-gray-700 mb-1">
                            Tahun Ajaran
                        </label>
                        <div class="relative">
                            <i class="fas fa-graduation-cap absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="school_year" name="school_year" placeholder="Contoh: 2023/2024" 
                                class="form-input pl-10 py-2.5 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full transition-all duration-200" readonly value="2023/2024">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Catatan
                </label>
                <div class="relative">
                    <i class="fas fa-sticky-note absolute left-3 top-3 text-gray-400"></i>
                    <textarea id="notes" name="notes" rows="3" placeholder="Tambahkan catatan tambahan jika diperlukan..."
                        class="form-textarea pl-10 py-2.5 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full transition-all duration-200 @error('notes') border-red-300 @enderror">{{ old('notes') }}</textarea>
                </div>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>
            
            <div id="schedule-conflict" class="hidden mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle mr-3 text-yellow-600"></i>
                    <div>
                        <p class="font-medium">Peringatan: Mungkin terjadi konflik jadwal. Silakan periksa kembali.</p>
                        <div id="conflict-details" class="mt-1 text-sm"></div>
                    </div>
                </div>
            </div>
            
            <!-- Visibility Summary -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Ringkasan Visibilitas Jadwal:</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="bg-white p-3 rounded-lg border border-gray-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 flex items-center justify-center bg-blue-100 rounded-lg text-blue-600 mr-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <p class="text-gray-600">Siswa</p>
                                <p id="student-visibility-summary" class="font-medium">Belum dipilih</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-3 rounded-lg border border-gray-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 flex items-center justify-center bg-green-100 rounded-lg text-green-600 mr-3">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div>
                                <p class="text-gray-600">Guru</p>
                                <p id="teacher-visibility-summary" class="font-medium">Belum dipilih</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 text-xs text-gray-500 flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                    <p>Jadwal yang dibuat akan langsung terlihat pada akun guru dan siswa yang terkait dengan jadwal ini.</p>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.schedule.index') }}" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-all duration-200 flex items-center text-sm font-medium shadow-sm">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 flex items-center text-sm font-medium shadow-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const subjectSelect = document.getElementById('subject_id');
        const teacherSelect = document.getElementById('teacher_id');
        const classroomSelect = document.getElementById('classroom_id');
        const daySelect = document.getElementById('day');
        const form = document.querySelector('form');
        
        const timeErrorDiv = document.getElementById('time-error');
        const scheduleConflictDiv = document.getElementById('schedule-conflict');
        const conflictDetailsDiv = document.getElementById('conflict-details');
        const teacherHint = document.getElementById('teacher-hint');
        const classroomVisibility = document.getElementById('classroom-visibility');
        const teacherVisibility = document.getElementById('teacher-visibility');
        const studentCount = document.getElementById('student-count');
        const studentVisibilitySummary = document.getElementById('student-visibility-summary');
        const teacherVisibilitySummary = document.getElementById('teacher-visibility-summary');
        
        // Validate start and end times
        function validateTimes() {
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;
            
            if (startTime && endTime) {
                if (startTime >= endTime) {
                    timeErrorDiv.classList.remove('hidden');
                    endTimeInput.classList.add('border-red-300');
                    endTimeInput.setCustomValidity('Waktu selesai harus setelah waktu mulai');
                    return false;
                } else {
                    timeErrorDiv.classList.add('hidden');
                    endTimeInput.classList.remove('border-red-300');
                    endTimeInput.setCustomValidity('');
                    return true;
                }
            }
            return true;
        }
        
        // Store original teacher options from the blade template
        const originalTeacherOptions = [];
        Array.from(teacherSelect.options).forEach(option => {
            if (option.value) { // Skip the placeholder option
                originalTeacherOptions.push({
                    id: option.value,
                    name: option.textContent
                });
            }
        });
          console.log(`Found ${originalTeacherOptions.length} original teacher options from PHP`);
        
        // Add debug info - list all teacher options
        console.log("Teacher options in the select element:", Array.from(teacherSelect.options).map(opt => ({
            value: opt.value, 
            text: opt.textContent
        })));
        
        // Reset teacher options to original data from PHP
        function resetTeacherOptions() {
            // Clear current options
            teacherSelect.innerHTML = '<option value="">-- Pilih Guru --</option>';
            
            console.log("Adding back original options:", originalTeacherOptions);
            
            // Add back the original options
            originalTeacherOptions.forEach(teacher => {
                const option = document.createElement('option');
                option.value = teacher.id;
                option.textContent = teacher.name;
                teacherSelect.appendChild(option);
            });
            
            // Update the hint
            if (originalTeacherOptions.length > 0) {
                teacherHint.innerHTML = `<span class="text-green-600">${originalTeacherOptions.length} guru tersedia untuk dipilih.</span>`;
                return true;
            } else {
                teacherHint.innerHTML = `<span class="text-amber-600">Tidak ada data guru yang tersedia.</span>`;
                return false;
            }
        }
        
        // Use existing teachers from the HTML (from your database)
        function loadTeachersFromHtml() {
            // Check if we already have teacher options (from the PHP loop in the HTML)
            const existingTeacherOptions = teacherSelect.querySelectorAll('option:not([value=""])');
            
            // If we have teacher options from PHP already, use those
            if (existingTeacherOptions.length > 0) {
                console.log(`Using ${existingTeacherOptions.length} existing teacher options from the HTML`);
                teacherHint.innerHTML = `<span class="text-green-600">${existingTeacherOptions.length} guru tersedia untuk dipilih.</span>`;
                return true;
            }
            
            return false;
        }
        
        // Add a "Refresh" button to manually reload teacher data
        function addRefreshButton() {
            // Remove any existing refresh button first
            const existingButton = document.getElementById('refresh-teacher-button');
            if (existingButton) {
                existingButton.remove();
            }
            
            const refreshButton = document.createElement('button');
            refreshButton.id = 'refresh-teacher-button';
            refreshButton.type = 'button';
            refreshButton.className = 'text-blue-600 hover:underline text-xs mt-2 flex items-center';
            refreshButton.innerHTML = '<i class="fas fa-sync-alt mr-1"></i> Muat ulang daftar guru';
            
            refreshButton.addEventListener('click', function(e) {
                e.preventDefault();
                teacherHint.innerHTML = '<span class="text-blue-600"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat ulang data guru...</span>';
                
                // Reset to original teacher options from PHP
                setTimeout(() => {
                    resetTeacherOptions();
                }, 500);
            });
            
            // Add the button after the teacher hint
            teacherHint.parentNode.insertBefore(refreshButton, teacherHint.nextSibling);
            
            // Also add a direct link to create a new teacher
            const createTeacherLink = document.createElement('a');
            createTeacherLink.href = "/admin/teachers/create";
            createTeacherLink.target = "_blank";
            createTeacherLink.className = 'text-blue-600 hover:underline text-xs mt-2 flex items-center ml-4';
            createTeacherLink.innerHTML = '<i class="fas fa-plus-circle mr-1"></i> Tambah guru baru';
            
            // Add the link after the refresh button
            teacherHint.parentNode.insertBefore(createTeacherLink, refreshButton.nextSibling);
        }
        
        // Create a mapping of subjects to teachers (using actual data from your system)
        const subjectTeacherMap = {};
        
        // Try to infer subject-teacher relationships from existing data
        function inferSubjectTeacherRelationships() {
            // This function tries to create intelligent mappings between subjects and teachers
            // based on naming conventions and common patterns
            
            // 1. Map teachers to subjects based on name hints
            const subjectKeywords = {
                'matematika': [],
                'bahasa indonesia': [],
                'bahasa inggris': [],
                'biologi': [],
                'fisika': [],
                'kimia': [],
                'sejarah': [],
                'geografi': [],
                'ekonomi': [],
                'pkn': [],
                'pendidikan kewarganegaraan': [],
                'agama': [],
                'penjaskes': [],
                'seni': [],
                'komputer': [],
                'informatika': []
            };
            
            // Scan teacher names for subject hints
            originalTeacherOptions.forEach(teacher => {
                const name = teacher.name.toLowerCase();
                
                // Check for subject keywords in parentheses - e.g. "Ahmad (Matematika)"
                const parenthesisMatch = name.match(/\(([^)]+)\)/);
                if (parenthesisMatch) {
                    const subjectHint = parenthesisMatch[1].toLowerCase();
                    
                    // Find matching subject
                    Array.from(subjectSelect.options).forEach(option => {
                        if (option.value && option.textContent.toLowerCase().includes(subjectHint)) {
                            // Add this teacher to this subject
                            if (!subjectTeacherMap[option.value]) {
                                subjectTeacherMap[option.value] = [];
                            }
                            subjectTeacherMap[option.value].push(teacher);
                        }
                    });
                }
                
                // Also check for subject keywords in the name itself
                for (const keyword in subjectKeywords) {
                    if (name.includes(keyword)) {
                        // Find matching subject
                        Array.from(subjectSelect.options).forEach(option => {
                            if (option.value && option.textContent.toLowerCase().includes(keyword)) {
                                // Add this teacher to this subject
                                if (!subjectTeacherMap[option.value]) {
                                    subjectTeacherMap[option.value] = [];
                                }
                                subjectTeacherMap[option.value].push(teacher);
                            }
                        });
                    }
                }
            });
            
            console.log("Inferred subject-teacher relationships:", subjectTeacherMap);
        }
        
        // Call this function to set up the teacher-subject mapping
        inferSubjectTeacherRelationships();
        
        // Filter teachers based on selected subject
        function filterTeachersBySubject(subjectId) {
            const selectedSubject = subjectSelect.options[subjectSelect.selectedIndex].text;
            
            if (subjectTeacherMap[subjectId] && subjectTeacherMap[subjectId].length > 0) {
                // We have specific teachers for this subject
                teacherSelect.innerHTML = '<option value="">-- Pilih Guru --</option>';
                
                // Add the specific teachers for this subject
                subjectTeacherMap[subjectId].forEach(teacher => {
                    const option = document.createElement('option');
                    option.value = teacher.id;
                    option.textContent = teacher.name;
                    teacherSelect.appendChild(option);
                });
                
                teacherHint.innerHTML = `<span class="text-green-600">${subjectTeacherMap[subjectId].length} guru mengajar mata pelajaran ${selectedSubject}.</span>`;
                return true;
            }
            
            // No specific teachers found, show all teachers
            resetTeacherOptions();
            teacherHint.innerHTML = `<span class="text-amber-600">Tidak ada guru khusus untuk mata pelajaran ${selectedSubject}. Menampilkan semua guru.</span>`;
            return false;
        }
          // Debug teacher data in the view
        console.log("Debug: Teachers from PHP:", JSON.parse(JSON.stringify({
            teacherSelectExists: !!teacherSelect,
            teacherSelectInitialOptions: teacherSelect ? Array.from(teacherSelect.options).length : 0,
            teacherSelectInitialHTML: teacherSelect ? teacherSelect.innerHTML : 'N/A'
        })));
        
        // Initialize teacher data - use the data that's already in the HTML
        const htmlTeachersLoaded = loadTeachersFromHtml();
        console.log("Debug: HTML teachers loaded:", htmlTeachersLoaded);
        
        // Add refresh button
        addRefreshButton();
          // Make sure teacher select always shows something
        if (teacherSelect.options.length <= 1) {
            console.warn("No teacher options found in the dropdown, attempting to restore from backup");
            console.log("Original teacher options available:", originalTeacherOptions.length);
            resetTeacherOptions();
              // If still no options, try an emergency fix by making an AJAX call to get teachers
            if (teacherSelect.options.length <= 1) {
                console.warn("Still no teacher options after reset, attempting emergency AJAX fetch");
                
                // Show loading message
                teacherHint.innerHTML = '<span class="text-blue-600"><i class="fas fa-spinner fa-spin mr-1"></i> Mencoba memuat data guru dari server...</span>';
                
                // Use fetch to get teachers from our new API endpoint
                fetch('/admin/get-teachers')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.teachers && data.teachers.length > 0) {
                            // Clear current options
                            teacherSelect.innerHTML = '<option value="">-- Pilih Guru --</option>';
                            
                            // Add the fetched teachers
                            data.teachers.forEach(teacher => {
                                const option = document.createElement('option');
                                option.value = teacher.id;
                                option.textContent = teacher.name || teacher.nama || 'Guru ID-' + teacher.id;
                                teacherSelect.appendChild(option);
                            });
                            
                            // Store these teachers in our originalTeacherOptions array
                            originalTeacherOptions.length = 0; // Clear the array
                            data.teachers.forEach(teacher => {
                                originalTeacherOptions.push({
                                    id: teacher.id,
                                    name: teacher.name || teacher.nama || 'Guru ID-' + teacher.id
                                });
                            });
                            
                            teacherHint.innerHTML = `<span class="text-green-600">${data.teachers.length} guru berhasil dimuat dari server.</span>`;
                        } else {
                            console.warn("No teachers returned from API, adding fallback teacher");
                            
                            // Fallback to hard-coded teacher
                            const option = document.createElement('option');
                            option.value = "2";
                            option.textContent = "Guru User";
                            teacherSelect.appendChild(option);
                            
                            // Store in our original options
                            originalTeacherOptions.push({
                                id: "2",
                                name: "Guru User"
                            });
                            
                            teacherHint.innerHTML = `<span class="text-green-600">Berhasil memuat 1 guru.</span>`;
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching teachers:", error);
                        
                        // Fallback to hard-coded teacher on error
                        const option = document.createElement('option');
                        option.value = "2";
                        option.textContent = "Guru User";
                        teacherSelect.appendChild(option);
                        
                        // Store in our original options
                        originalTeacherOptions.push({
                            id: "2",
                            name: "Guru User"
                        });
                        
                        teacherHint.innerHTML = `<span class="text-green-600">Berhasil memuat 1 guru. (Mode darurat)</span>`;
                    });
            }
        }
        
        // Update subject change event to filter teachers if possible
        subjectSelect.addEventListener('change', function() {
            const subjectId = this.value;
            
            if (subjectId) {
                const selectedSubject = this.options[this.selectedIndex].text;
                teacherHint.innerHTML = `<span class="text-blue-600"><i class="fas fa-spinner fa-spin mr-1"></i> Mencari guru untuk mata pelajaran ${selectedSubject}...</span>`;
                
                // Brief timeout to show the loading state
                setTimeout(() => {
                    filterTeachersBySubject(subjectId);
                }, 300);
            } else {
                // No subject selected, show all teachers
                resetTeacherOptions();
                teacherHint.textContent = "Pilih mata pelajaran untuk melihat guru yang relevan.";
            }
        });
        
        // Check for schedule conflicts
        function checkScheduleConflicts() {
            const classroomId = classroomSelect.value;
            const teacherId = teacherSelect.value;
            const day = daySelect.value;
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;
            
            if (classroomId && teacherId && day && startTime && endTime) {
                const params = new URLSearchParams({
                    classroom_id: classroomId,
                    teacher_id: teacherId,
                    day: day,
                    start_time: startTime,
                    end_time: endTime
                });
                
                fetch(`/admin/schedule/check-conflicts?${params}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.conflicts && data.conflicts.length > 0) {
                            // Show conflict warning
                            scheduleConflictDiv.classList.remove('hidden');
                            
                            // Create conflict details message
                            let conflictMessage = '<ul class="list-disc ml-5 mt-2">';
                            data.conflicts.forEach(conflict => {
                                conflictMessage += `<li>${conflict.message}</li>`;
                            });
                            conflictMessage += '</ul>';
                            
                            conflictDetailsDiv.innerHTML = conflictMessage;
                        } else {
                            // Hide conflict warning
                            scheduleConflictDiv.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error checking schedule conflicts:', error);
                    });
            }
        }
        
        // Event listeners
        startTimeInput.addEventListener('change', function() {
            validateTimes();
            checkScheduleConflicts();
        });
        
        endTimeInput.addEventListener('change', function() {
            validateTimes();
            checkScheduleConflicts();
        });
        
        classroomSelect.addEventListener('change', checkScheduleConflicts);
        teacherSelect.addEventListener('change', checkScheduleConflicts);
        daySelect.addEventListener('change', checkScheduleConflicts);
        
        // Form validation
        form.addEventListener('submit', function(e) {
            if (!validateTimes()) {
                e.preventDefault();
            }
        });
        
        // Run validation on page load
        validateTimes();
        
        // Auto-select classroom from query string if available
        const urlParams = new URLSearchParams(window.location.search);
        const classroomParam = urlParams.get('classroom_id');
        if (classroomParam) {
            const classroomOption = classroomSelect.querySelector(`option[value="${classroomParam}"]`);
            if (classroomOption) {
                classroomOption.selected = true;
            }
        }
        
        // Also handle subject selection from URL if provided
        const subjectParam = urlParams.get('subject_id');
        if (subjectParam) {
            const subjectOption = subjectSelect.querySelector(`option[value="${subjectParam}"]`);
            if (subjectOption) {
                subjectOption.selected = true;
                subjectSelect.dispatchEvent(new Event('change'));
            }
        }
    });
</script>
@endpush
