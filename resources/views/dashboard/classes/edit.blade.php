@extends('layouts.dashboard')

@section('page-title', 'Edit Kelas')

@section('page-actions')
<a href="{{ route('classes.index') }}" class="btn btn-sm btn-secondary">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Form Edit Kelas: {{ $class->name }}</h5>
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

        <form action="{{ route('classes.update', $class) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Informasi Kelas</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $class->name) }}" required>
                                    <small class="text-muted">Contoh: X-IPA 1, XI-IPS 2, dll.</small>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="level" class="form-label">Tingkat <span class="text-danger">*</span></label>
                                    <select class="form-select @error('level') is-invalid @enderror" id="level" name="level" required>
                                        <option value="" disabled>Pilih Tingkat Kelas</option>
                                        <option value="X" {{ old('level', $class->level) == 'X' ? 'selected' : '' }}>Kelas X</option>
                                        <option value="XI" {{ old('level', $class->level) == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                        <option value="XII" {{ old('level', $class->level) == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                                    </select>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Jurusan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="" disabled>Pilih Jurusan</option>
                                        <option value="IPA" {{ old('type', $class->type) == 'IPA' ? 'selected' : '' }}>IPA</option>
                                        <option value="IPS" {{ old('type', $class->type) == 'IPS' ? 'selected' : '' }}>IPS</option>
                                        <option value="Bahasa" {{ old('type', $class->type) == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="capacity" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $class->capacity) }}" min="1" max="40" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="room" class="form-label">Ruang Kelas</label>
                                    <input type="text" class="form-control @error('room') is-invalid @enderror" id="room" name="room" value="{{ old('room', $class->room) }}">
                                    @error('room')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="academic_year" class="form-label">Tahun Akademik</label>
                                    <select class="form-select @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year">
                                        <option value="">Pilih Tahun Akademik</option>
                                        <option value="2023/2024" {{ old('academic_year', $class->academic_year) == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                                        <option value="2024/2025" {{ old('academic_year', $class->academic_year) == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                    </select>
                                    @error('academic_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="teacher_id" class="form-label">Wali Kelas</label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id">
                                        <option value="">-- Pilih Wali Kelas --</option>
                                        @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }} {{ $teacher->nip ? '(NIP: '.$teacher->nip.')' : '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $class->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="regenerateClassName" name="regenerate_class_name">
                                        <label class="form-check-label" for="regenerateClassName">
                                            Regenerasi nama kelas berdasarkan tingkat dan jurusan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary me-2">
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
        // References to form elements
        const levelSelect = document.getElementById('level');
        const typeSelect = document.getElementById('type');
        const nameInput = document.getElementById('name');
        const regenerateCheckbox = document.getElementById('regenerateClassName');
        let originalClassName = nameInput.value;
        let userEdited = false;
        
        // Track if user manually edits the class name
        nameInput.addEventListener('input', function() {
            userEdited = true;
        });
        
        // Function to generate class name based on level and type
        function regenerateClassName() {
            const level = levelSelect.value;
            const type = typeSelect.value;
            
            if (level && type) {
                // Extract class number from current name or use 1
                let classNumber = 1;
                const currentName = nameInput.value;
                const matchResult = currentName.match(/(\d+)$/);
                if (matchResult) {
                    classNumber = parseInt(matchResult[1], 10);
                }
                
                // Generate the new class name
                return `${level}-${type} ${classNumber}`;
            }
            return null;
        }
        
        // Handle checkbox change for regeneration
        regenerateCheckbox.addEventListener('change', function() {
            if (this.checked) {
                const newClassName = regenerateClassName();
                if (newClassName) {
                    nameInput.value = newClassName;
                }
            }
        });
        
        // Handle level and type changes
        function handleSelectChange() {
            if (regenerateCheckbox.checked || (!userEdited && originalClassName === nameInput.value)) {
                const newClassName = regenerateClassName();
                if (newClassName) {
                    nameInput.value = newClassName;
                }
            }
        }
        
        levelSelect.addEventListener('change', handleSelectChange);
        typeSelect.addEventListener('change', handleSelectChange);
    });
</script>
@endsection
