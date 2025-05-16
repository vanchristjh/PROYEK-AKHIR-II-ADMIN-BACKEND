@extends('layouts.dashboard')

@section('title', 'Tambah Pengguna Baru')

@section('header', 'Tambah Pengguna Baru')

@section('navigation')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-users text-lg w-6"></i>
            <span class="ml-3">Pengguna</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.subjects.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Mata Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.classrooms.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-school text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kelas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-bullhorn text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-100">
        <div class="mb-6 pb-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-800">Tambah Pengguna Baru</h2>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Ada beberapa kesalahan pada inputan Anda:</p>
                        <ul class="mt-2 text-sm list-disc list-inside text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Basic Information -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Dasar</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200">
                        </div>
                        
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                                Username <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="username" id="username" value="{{ old('username') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200">
                            <p class="text-xs text-gray-500 mt-1 italic">Username harus unik dan tidak mengandung spasi</p>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full pl-10 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200">
                            </div>
                        </div>                        <div>
                            <label for="id_number" class="block text-sm font-medium text-gray-700 mb-1">
                                <span id="id_number_label">NIP/NIS</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-card text-gray-400"></i>
                                </div>
                                <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}"
                                    class="w-full pl-10 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200">
                                <p class="text-xs text-gray-500 mt-1 italic">
                                    <span id="id_number_help">Nomor identitas pengguna (opsional)</span>
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Role <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <select name="role_id" id="role_id" required
                                        class="w-full pl-10 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200">
                                    <option value="">Pilih Role</option>
                                    @foreach($roles ?? [] as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id', request('role') == strtolower($role->name) ? $role->id : '') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Tambahan</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                                Avatar (Opsional)
                            </label>
                            <div class="mt-2">
                                <label for="avatar" class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 inline-flex items-center">
                                    <i class="fas fa-upload mr-2 text-gray-500"></i>
                                    <span>Pilih File</span>
                                    <input type="file" name="avatar" id="avatar" class="hidden">
                                </label>
                                <span class="text-xs ml-2 file-name"></span>
                            </div>
                            <div class="flex items-center space-x-1 mt-2">
                                <i class="fas fa-info-circle text-blue-500"></i>
                                <span class="text-xs text-gray-500">Format file: JPG, PNG. Max: 2MB</span>
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" name="password" id="password" required
                                    class="w-full pl-10 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200">
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full pl-10 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200">
                            </div>
                        </div>

                        <div id="student_fields" style="display: none;">
                            <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Kelas
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-school text-gray-400"></i>
                                </div>
                                <select name="classroom_id" id="classroom_id"
                                        class="w-full pl-10 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200">
                                    <option value="">Tidak ada kelas</option>
                                    @foreach($classrooms ?? [] as $classroom)
                                        <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                            {{ $classroom->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="teacher_fields" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran yang Diajar
                            </label>
                            <div class="bg-white p-4 rounded-lg border border-gray-200 max-h-52 overflow-y-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @forelse($subjects ?? [] as $subject)
                                        <div class="flex items-center p-2 hover:bg-gray-50 rounded-md">
                                            <input type="checkbox" name="subjects[]" id="subject_{{ $subject->id }}" value="{{ $subject->id }}"
                                                {{ (old('subjects') && in_array($subject->id, old('subjects'))) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <label for="subject_{{ $subject->id }}" class="ml-2 text-sm text-gray-700 cursor-pointer">{{ $subject->name }}</label>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500 py-2">Tidak ada mata pelajaran yang tersedia</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 border-t border-gray-200 pt-5">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 mr-3 hover:bg-gray-50 transition-all duration-300 flex items-center">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 flex items-center shadow-md">
                    <i class="fas fa-save mr-2"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>

    <script>
        // Display file name when selected
        document.getElementById('avatar').addEventListener('change', function(e) {
            const fileName = e.target.files[0].name;
            document.querySelector('.file-name').textContent = fileName;
        });
    </script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_id');
        const studentFields = document.getElementById('student_fields');
        const teacherFields = document.getElementById('teacher_fields');
        const idNumberLabel = document.getElementById('id_number_label');
        const nameInput = document.getElementById('name');
        const usernameInput = document.getElementById('username');
        const submitBtn = document.querySelector('button[type="submit"]');
        const form = document.querySelector('form');
        
        // Initial check
        updateFieldsVisibility(roleSelect.value);
        
        // Handle role change
        roleSelect.addEventListener('change', function() {
            updateFieldsVisibility(this.value);
        });
          function updateFieldsVisibility(roleId) {
            // Hide all role-specific fields
            studentFields.style.display = 'none';
            teacherFields.style.display = 'none';
            
            // Get the help text element if it exists
            const idNumberHelp = document.getElementById('id_number_help');
            
            // Show fields based on selected role
            // Assuming role_id: 1 = admin, 2 = guru, 3 = siswa
            if (roleId == '3') { // Siswa
                studentFields.style.display = 'block';
                idNumberLabel.textContent = 'NIS';
                if (idNumberHelp) idNumberHelp.textContent = 'Nomor Induk Siswa';
            } else if (roleId == '2') { // Guru
                teacherFields.style.display = 'block';
                idNumberLabel.textContent = 'NIP';
                if (idNumberHelp) idNumberHelp.textContent = 'Nomor Induk Pegawai';
            } else if (roleId == '1') { // Admin
                idNumberLabel.textContent = 'ID Pengguna';
                if (idNumberHelp) idNumberHelp.textContent = 'ID Pengguna Admin (opsional)';
            } else {
                idNumberLabel.textContent = 'NIP/NIS';
                if (idNumberHelp) idNumberHelp.textContent = 'Nomor identitas pengguna (opsional)';
            }
        }

        // Generate username suggestion from name
        nameInput.addEventListener('blur', function() {
            if (!usernameInput.value) {
                // Create a username from the name: lowercase, no spaces, no special chars
                let suggestedUsername = this.value.toLowerCase()
                                            .replace(/\s+/g, '')
                                            .replace(/[^a-z0-9]/g, '');
                
                usernameInput.value = suggestedUsername;
            }
        });

        // Form submission handling with loading state
        form.addEventListener('submit', function() {
            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    
                    // Add error message if not exists
                    let errorMsg = field.nextElementSibling;
                    if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                        errorMsg = document.createElement('p');
                        errorMsg.classList.add('error-message', 'text-xs', 'text-red-500', 'mt-1');
                        errorMsg.textContent = 'Bidang ini wajib diisi';
                        field.parentNode.insertBefore(errorMsg, field.nextSibling);
                    }
                } else {
                    field.classList.remove('border-red-500');
                    
                    // Remove error message if exists
                    const errorMsg = field.nextElementSibling;
                    if (errorMsg && errorMsg.classList.contains('error-message')) {
                        errorMsg.remove();
                    }
                }
            });

            if (isValid) {
                // Show loading state on button
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                submitBtn.disabled = true;
            } else {
                event.preventDefault();
            }
        });

        // Remove red border on input when user types
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                
                // Remove error message if exists
                const errorMsg = this.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('error-message')) {
                    errorMsg.remove();
                }
            });
        });
    });
</script>
@endpush
