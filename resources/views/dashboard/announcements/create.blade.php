@extends('layouts.dashboard')

@section('page-title', 'Buat Pengumuman Baru')

@section('page-actions')
<a href="{{ route('announcements.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
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

        <form action="{{ route('announcements.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Konten Pengumuman</h5>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                                <small class="text-muted">Anda dapat menggunakan format HTML dasar untuk menambahkan format ke teks Anda.</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Status & Pengaturan</h5>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publikasikan</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Arsip</option>
                                </select>
                                <small class="text-muted">Pengumuman draft tidak akan ditampilkan kepada pengguna.</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="published_at" class="form-label">Tanggal Publikasi</label>
                                <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{ old('published_at') }}">
                                <small class="text-muted">Kosongkan untuk publikasi segera jika status "Publikasikan".</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="expired_at" class="form-label">Tanggal Kadaluarsa</label>
                                <input type="datetime-local" class="form-control" id="expired_at" name="expired_at" value="{{ old('expired_at') }}">
                                <small class="text-muted">Kosongkan jika pengumuman tidak memiliki batas waktu.</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Distribusi</h5>
                            
                            <div class="mb-3">
                                <label for="target_audience" class="form-label">Target Penerima <span class="text-danger">*</span></label>
                                <select class="form-select" id="target_audience" name="target_audience" required>
                                    <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>Semua</option>
                                    <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>Siswa</option>
                                    <option value="teachers" {{ old('target_audience') == 'teachers' ? 'selected' : '' }}>Guru</option>
                                    <option value="staff" {{ old('target_audience') == 'staff' ? 'selected' : '' }}>Staf</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Penting</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Pengumuman
                        </button>
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize WYSIWYG editor
        $('#content').summernote({
            placeholder: 'Tulis isi pengumuman di sini...',
            tabsize: 2,
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        // Handle status change to show/hide publish date
        const statusSelect = document.getElementById('status');
        const publishDateInput = document.getElementById('published_at');
        
        statusSelect.addEventListener('change', function() {
            if (this.value === 'published') {
                publishDateInput.parentElement.style.display = '';
            } else {
                publishDateInput.parentElement.style.display = 'none';
            }
        });
        
        // Trigger on page load
        if (statusSelect.value !== 'published') {
            publishDateInput.parentElement.style.display = 'none';
        }
    });
</script>
@endsection
