@extends('layouts.dashboard')

@section('page-title', 'Tambah Mata Pelajaran')

@section('page-actions')
<a href="{{ route('subjects.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Form Tambah Mata Pelajaran</h5>
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

        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi Dasar</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="code" class="form-label">Kode Mata Pelajaran</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}">
                                    <small class="text-muted">Contoh: MTK, FIS, BIO, dll.</small>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Tingkat dan Kurikulum</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="class_level" class="form-label">Tingkat Kelas</label>
                                    <select class="form-select @error('class_level') is-invalid @enderror" id="class_level" name="class_level">
                                        <option value="">-- Semua Tingkat --</option>
                                        <option value="X" {{ old('class_level') == 'X' ? 'selected' : '' }}>Kelas X</option>
                                        <option value="XI" {{ old('class_level') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                        <option value="XII" {{ old('class_level') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                                    </select>
                                    @error('class_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester">
                                        <option value="">-- Semua Semester --</option>
                                        <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                        <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                                    </select>
                                    @error('semester')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="credits" class="form-label">Jumlah SKS</label>
                                    <input type="number" class="form-control @error('credits') is-invalid @enderror" id="credits" name="credits" value="{{ old('credits', 0) }}" min="0">
                                    @error('credits')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="curriculum" class="form-label">Kurikulum</label>
                                    <input type="text" class="form-control @error('curriculum') is-invalid @enderror" id="curriculum" name="curriculum" value="{{ old('curriculum') }}">
                                    <small class="text-muted">Contoh: Kurikulum Merdeka, K13, dll.</small>
                                    @error('curriculum')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="subject_type" class="form-label">Jenis Mata Pelajaran</label>
                                    <select class="form-select @error('subject_type') is-invalid @enderror" id="subject_type" name="subject_type">
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="Wajib" {{ old('subject_type') == 'Wajib' ? 'selected' : '' }}>Mata Pelajaran Wajib</option>
                                        <option value="Muatan Lokal" {{ old('subject_type') == 'Muatan Lokal' ? 'selected' : '' }}>Muatan Lokal</option>
                                        <option value="Peminatan" {{ old('subject_type') == 'Peminatan' ? 'selected' : '' }}>Peminatan</option>
                                        <option value="Lintas Minat" {{ old('subject_type') == 'Lintas Minat' ? 'selected' : '' }}>Lintas Minat</option>
                                    </select>
                                    @error('subject_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Guru Pengampu</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Pilih Guru Pengampu</label>
                                <div class="teacher-selection border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                                    @if($teachers->count() > 0)
                                        @foreach($teachers as $teacher)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="teacher_{{ $teacher->id }}" name="teacher_ids[]" value="{{ $teacher->id }}" {{ in_array($teacher->id, old('teacher_ids', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="teacher_{{ $teacher->id }}">
                                                    {{ $teacher->name }}
                                                    @if($teacher->subject)
                                                        <span class="text-muted">({{ $teacher->subject }})</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted mb-0">Belum ada data guru. Silakan tambahkan guru terlebih dahulu.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Status & Publikasi</h5>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                                <small class="text-muted">Mata pelajaran yang tidak aktif tidak akan muncul di jadwal.</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Mata Pelajaran
                        </button>
                        <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
