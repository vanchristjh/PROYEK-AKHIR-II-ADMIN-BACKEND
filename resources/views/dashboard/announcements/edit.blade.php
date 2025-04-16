@extends('layouts.dashboard')

@section('page-title', 'Edit Pengumuman')

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

        <form action="{{ route('announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Konten Pengumuman</h5>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="content" name="content" rows="10" required>{{ old('content', $announcement->content) }}</textarea>
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
                                    <option value="draft" {{ old('status', $announcement->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $announcement->status) == 'published' ? 'selected' : '' }}>Publikasikan</option>
                                    <option value="archived" {{ old('status', $announcement->status) == 'archived' ? 'selected' : '' }}>Arsip</option>
                                </select>
                                <small class="text-muted">Pengumuman draft tidak akan ditampilkan kepada pengguna.</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="published_at" class="form-label">Tanggal Publikasi</label>
                                <input type="datetime-local" class="form-control" id="published_at" name="published_at" 
                                    value="{{ old('published_at', $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}">
                                <small class="text-muted">Kosongkan untuk publikasi segera jika status "Publikasikan".</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="expired_at" class="form-label">Tanggal Kadaluarsa</label>
                                <input type="datetime-local" class="form-control" id="expired_at" name="expired_at" 
                                    value="{{ old('expired_at', $announcement->expired_at ? $announcement->expired_at->format('Y-m-d\TH:i') : '') }}">
                                <small class="text-muted">Kosongkan jika pengumuman tidak memiliki batas waktu.</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Lampiran (Opsional)</label>
                                <input type="file" class="form-control" id="attachment" name="attachment">
                                @if($announcement->attachment_path)
                                <div class="mt-2">
                                    <span class="badge bg-info"><i class="bx bx-paperclip me-1"></i> File terlampir</span>
                                    <a href="{{ asset('storage/'.$announcement->attachment_path) }}" target="_blank" class="ms-2 small">
                                        Lihat lampiran
                                    </a>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" id="remove_attachment" name="remove_attachment" value="1">
                                        <label class="form-check-label small text-danger" for="remove_attachment">
                                            Hapus lampiran saat ini
                                        </label>
                                    </div>
                                </div>
                                @endif
                                <small class="text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Maks. 5MB)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Distribusi</h5>
                            
                            <div class="mb-3">
                                <label for="target_audience" class="form-label">Target Penerima <span class="text-danger">*</span></label>
                                <select class="form-select" id="target_audience" name="target_audience" required>
                                    <option value="all" {{ old('target_audience', $announcement->target_audience) == 'all' ? 'selected' : '' }}>Semua</option>
                                    <option value="students" {{ old('target_audience', $announcement->target_audience) == 'students' ? 'selected' : '' }}>Siswa</option>
                                    <option value="teachers" {{ old('target_audience', $announcement->target_audience) == 'teachers' ? 'selected' : '' }}>Guru</option>
                                    <option value="staff" {{ old('target_audience', $announcement->target_audience) == 'staff' ? 'selected' : '' }}>Staf</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low" {{ old('priority', $announcement->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                                    <option value="medium" {{ old('priority', $announcement->priority) == 'medium' ? 'selected' : '' }}>Sedang</option>
                                    <option value="high" {{ old('priority', $announcement->priority) == 'high' ? 'selected' : '' }}>Penting</option>
                                </select>
                            </div>
                            
                            <!-- Meta Information -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="text-muted mb-2">Informasi Tambahan</h6>
                                <ul class="list-unstyled small text-muted">
                                    <li>Dibuat: {{ $announcement->created_at->format('d M Y H:i') }}</li>
                                    <li>Terakhir diubah: {{ $announcement->updated_at->format('d M Y H:i') }}</li>
                                    <li>Dibuat oleh: {{ $announcement->creator ? $announcement->creator->name : 'System' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Perubahan
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
