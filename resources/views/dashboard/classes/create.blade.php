@extends('layouts.dashboard')

@section('page-title', 'Tambah Kelas Baru')

@section('page-actions')
<a href="{{ route('classes.index') }}" class="btn btn-sm btn-secondary">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Form Tambah Kelas Baru</h5>
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

        <form action="{{ route('classes.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Informasi Kelas</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        <button class="btn btn-outline-secondary" type="button" id="regenerateButton" title="Regenerasi nama kelas">
                                            <i class="bx bx-refresh"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Contoh: X-IPA 1, XI-IPS 2, dll.</small>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="level" class="form-label">Tingkat <span class="text-danger">*</span></label>
                                    <select class="form-select @error('level') is-invalid @enderror" id="level" name="level" required>
                                        <option value="" selected disabled>Pilih Tingkat Kelas</option>
                                        <option value="X" {{ old('level') == 'X' ? 'selected' : '' }}>Kelas X</option>
                                        <option value="XI" {{ old('level') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                        <option value="XII" {{ old('level') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                                    </select>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Jurusan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="" selected disabled>Pilih Jurusan</option>
                                        <option value="IPA" {{ old('type') == 'IPA' ? 'selected' : '' }}>IPA</option>
                                        <option value="IPS" {{ old('type') == 'IPS' ? 'selected' : '' }}>IPS</option>
                                        <option value="Bahasa" {{ old('type') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="capacity" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', 30) }}" min="1" max="40" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="room" class="form-label">Ruang Kelas</label>
                                    <input type="text" class="form-control @error('room') is-invalid @enderror" id="room" name="room" value="{{ old('room') }}" placeholder="Contoh: R101">
                                    @error('room')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="academic_year" class="form-label">Tahun Akademik</label>
                                    <select class="form-select @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year">
                                        <option value="" selected disabled>Pilih Tahun Akademik</option>
                                        <option value="2023/2024" {{ old('academic_year') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                                        <option value="2024/2025" {{ old('academic_year') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                    </select>
                                    @error('academic_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="teacher_id" class="form-label">Wali Kelas</label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id">
                                        <option value="">-- Pilih Wali Kelas --</option>
                                        @if(isset($teachers) && count($teachers) > 0)
                                            @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} {{ $teacher->nip ? '(NIP: '.$teacher->nip.')' : '' }}
                                            </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>Tidak ada guru tersedia</option>
                                        @endif
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if(!isset($teachers) || count($teachers) == 0)
                                        <div class="mt-2">
                                            <small class="text-danger">
                                                Tidak ada data guru tersedia. Anda masih dapat membuat kelas tanpa wali kelas dan menambahkannya nanti.
                                                @if(Route::has('teachers.create'))
                                                    <a href="{{ route('teachers.create') }}" class="text-primary">Tambah guru baru</a>
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Deskripsi singkat tentang kelas ini (opsional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="autoGenerateClass" checked>
                                        <label class="form-check-label" for="autoGenerateClass">
                                            Otomatis perbarui nama kelas saat memilih tingkat dan jurusan
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
                    <i class="bx bx-save me-1"></i> Simpan Data
                </button>
            </div>
            
            <div class="mt-4 text-center">
                <small class="text-muted">
                    Setelah membuat kelas, Anda dapat menambahkan siswa ke kelas ini melalui 
                    <a href="{{ route('students.create') }}">halaman tambah siswa</a> atau 
                    melihat daftar kelas di <a href="{{ route('classes.index') }}">halaman data kelas</a>.
                </small>
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
        const regenerateButton = document.getElementById('regenerateButton');
        const autoGenerateCheckbox = document.getElementById('autoGenerateClass');
        
        let userEdited = false;
        let classCounter = 1;
        
        // Track if user manually edits the class name
        nameInput.addEventListener('input', function() {
            userEdited = true;
        });
        
        // Function to generate class name based on level and type with incremented number
        function generateClassName() {
            const level = levelSelect.value;
            const type = typeSelect.value;
            
            if (level && type) {
                // Try to extract the existing number pattern if present
                const currentName = nameInput.value;
                const numberMatch = currentName.match(/(\d+)$/);
                
                // If name has this format "X-IPA 1", extract the number, otherwise use our counter
                if (currentName.includes(`${level}-${type}`) && numberMatch) {
                    return `${level}-${type} ${numberMatch[1]}`;
                } else {
                    return `${level}-${type} ${classCounter}`;
                }
            }
            return null;
        }
        
        // Function to update class name
        function updateClassName() {
            if (!userEdited || autoGenerateCheckbox.checked) {
                const newClassName = generateClassName();
                if (newClassName) {
                    nameInput.value = newClassName;
                    userEdited = false;
                }
            }
        }
        
        // Event handlers
        levelSelect.addEventListener('change', updateClassName);
        typeSelect.addEventListener('change', updateClassName);
        
        // Regenerate button handler
        regenerateButton.addEventListener('click', function() {
            // Increment counter for a new class number suggestion
            classCounter++;
            const newClassName = generateClassName();
            if (newClassName) {
                nameInput.value = newClassName;
                userEdited = false;
            }
        });
        
        // Auto-generate toggle handler
        autoGenerateCheckbox.addEventListener('change', function() {
            if (this.checked) {
                updateClassName();
            }
        });
        
        // Initialize class name if level and type are already selected
        if (levelSelect.value && typeSelect.value) {
            updateClassName();
        }
    });
</script>
@endsection
