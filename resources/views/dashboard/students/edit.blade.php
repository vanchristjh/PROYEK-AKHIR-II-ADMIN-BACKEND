@extends('layouts.dashboard')

@section('page-title', 'Edit Akun Siswa')

@section('page-actions')
<a href="{{ route('students.index') }}" class="btn btn-sm btn-secondary">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Form Edit Akun Siswa: {{ $student->name }}</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data" id="studentForm">
            @csrf
            @method('PUT')
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Informasi Akun</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $student->email) }}" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="profile_photo" class="form-label">Foto Profil</label>
                                    <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*" onchange="previewImage(this)">
                                    <small class="text-muted">Format: JPG, PNG, GIF. Maks: 1MB</small>
                                    @if($student->profile_photo)
                                        <div class="mt-2" id="imagePreview">
                                            <img src="{{ asset('storage/'.$student->profile_photo) }}" alt="{{ $student->name }}" class="rounded" width="100">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Data Siswa</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis', $student->nis) }}" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="nisn" class="form-label">NISN</label>
                                    <input type="text" class="form-control" id="nisn" name="nisn" value="{{ old('nisn', $student->nisn) }}">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="class_id" name="class_id" required>
                                        <option value="" disabled>Pilih Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }} ({{ $class->level }} - {{ $class->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $student->name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control datepicker" id="birth_date" name="birth_date" value="{{ old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="academic_year" class="form-label">Tahun Akademik</label>
                                    <input type="text" class="form-control" id="academic_year" name="academic_year" value="{{ old('academic_year', $student->academic_year) }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}">
                                </div>

                                <div class="col-md-12">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $student->address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Data Orang Tua/Wali</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="parent_name" class="form-label">Nama Orang Tua/Wali</label>
                                    <input type="text" class="form-control" id="parent_name" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="parent_phone" class="form-label">Nomor Telepon Orang Tua/Wali</label>
                                    <input type="text" class="form-control" id="parent_phone" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bx bx-x me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2 if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
        
        // Initialize Flatpickr for date pickers if available
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                allowInput: true,
                altInput: true,
                altFormat: "d M Y",
                maxDate: "today"
            });
        }
        
        // Password toggle visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        if (togglePassword && password) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('bx-hide');
                this.querySelector('i').classList.toggle('bx-show');
            });
        }
        
        // Enable Bootstrap form validation
        const form = document.getElementById('studentForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                // Check if passwords match when both are filled
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('password_confirmation');
                if (password && confirmPassword && password.value && confirmPassword.value && password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords must match');
                    event.preventDefault();
                } else if (confirmPassword) {
                    confirmPassword.setCustomValidity('');
                }
                
                form.classList.add('was-validated');
            }, false);
        }
    });
    
    // Preview image before upload
    function previewImage(input) {
        const maxSize = 1024 * 1024; // 1MB
        const preview = document.getElementById('imagePreview');
        const previewImg = preview.querySelector('img');
        
        if (input.files && input.files[0]) {
            if (input.files[0].size > maxSize) {
                alert('Ukuran file terlalu besar! Maksimal 1MB.');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection