@extends('layouts.dashboard')

@section('page-title', 'Tambah Item Nilai')

@section('page-actions')
<a href="{{ route('grade-items.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('grade-items.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Item Nilai <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        <div class="form-text">Contoh: Tugas 1, Quiz 2, UTS, UAS, dll.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="grade_category_id" class="form-label">Kategori Nilai <span class="text-danger">*</span></label>
                        <select class="form-select" id="grade_category_id" name="grade_category_id" required>
                            <option value="">-- Pilih Kategori Nilai --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('grade_category_id', $selectedCategoryId) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} - {{ $category->subject->name ?? 'N/A' }} ({{ $category->class->name ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="max_score" class="form-label">Nilai Maksimal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="max_score" name="max_score" value="{{ old('max_score', 100) }}" min="0" step="0.01" required>
                        <div class="form-text">Skala nilai yang digunakan (default: 100)</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                        <div class="form-text">Informasi tambahan tentang item nilai ini (opsional)</div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                <button type="reset" class="btn btn-outline-secondary me-2">
                    <i class="bx bx-reset me-1"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Simpan Item
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
            $('#grade_category_id').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
    });
</script>
@endsection
