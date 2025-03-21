@extends('layouts.dashboard')

@section('page-title', 'Tambah Akun Guru')

@section('page-actions')
<a href="{{ route('teachers.index') }}" class="btn btn-sm btn-secondary">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Form Tambah Akun Guru</h5>
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

        <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Informasi Akun</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="profile_photo" class="form-label">Foto Profil</label>
                                    <input type="file" class="form-control" id="profile_photo" name="profile_photo">
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
                            <h6 class="card-title mb-3">Data Diri Guru</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip') }}" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="nuptk" class="form-label">NUPTK</label>
                                    <input type="text" class="form-control" id="nuptk" name="nuptk" value="{{ old('nuptk') }}">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                    <input type="text" class="form-control datepicker" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address') }}</textarea>
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
                            <h6 class="card-title mb-3">Informasi Akademik</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="subject" name="subject" required>
                                        <option value="" selected disabled>Pilih Mata Pelajaran</option>
                                        <option value="Matematika" {{ old('subject') == 'Matematika' ? 'selected' : '' }}>Matematika</option>
                                        <option value="Fisika" {{ old('subject') == 'Fisika' ? 'selected' : '' }}>Fisika</option>
                                        <option value="Kimia" {{ old('subject') == 'Kimia' ? 'selected' : '' }}>Kimia</option>
                                        <option value="Biologi" {{ old('subject') == 'Biologi' ? 'selected' : '' }}>Biologi</option>
                                        <option value="Bahasa Indonesia" {{ old('subject') == 'Bahasa Indonesia' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                        <option value="Bahasa Inggris" {{ old('subject') == 'Bahasa Inggris' ? 'selected' : '' }}>Bahasa Inggris</option>
                                        <option value="Sejarah" {{ old('subject') == 'Sejarah' ? 'selected' : '' }}>Sejarah</option>
                                        <option value="Geografi" {{ old('subject') == 'Geografi' ? 'selected' : '' }}>Geografi</option>
                                        <option value="Ekonomi" {{ old('subject') == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                                        <option value="Sosiologi" {{ old('subject') == 'Sosiologi' ? 'selected' : '' }}>Sosiologi</option>
                                        <option value="Pendidikan Kewarganegaraan" {{ old('subject') == 'Pendidikan Kewarganegaraan' ? 'selected' : '' }}>Pendidikan Kewarganegaraan</option>
                                        <option value="Pendidikan Agama" {{ old('subject') == 'Pendidikan Agama' ? 'selected' : '' }}>Pendidikan Agama</option>
                                        <option value="Seni Budaya" {{ old('subject') == 'Seni Budaya' ? 'selected' : '' }}>Seni Budaya</option>
                                        <option value="Penjas" {{ old('subject') == 'Penjas' ? 'selected' : '' }}>Penjas</option>
                                        <option value="TIK" {{ old('subject') == 'TIK' ? 'selected' : '' }}>TIK</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="position" class="form-label">Jabatan</label>
                                    <select class="form-select" id="position" name="position">
                                        <option value="" selected disabled>Pilih Jabatan</option>
                                        <option value="Kepala Sekolah" {{ old('position') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                        <option value="Wakil Kepala Sekolah" {{ old('position') == 'Wakil Kepala Sekolah' ? 'selected' : '' }}>Wakil Kepala Sekolah</option>
                                        <option value="Guru Mata Pelajaran" {{ old('position') == 'Guru Mata Pelajaran' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                                        <option value="Wali Kelas" {{ old('position') == 'Wali Kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                        <option value="Guru BK" {{ old('position') == 'Guru BK' ? 'selected' : '' }}>Guru BK</option>
                                        <option value="Staff Administrasi" {{ old('position') == 'Staff Administrasi' ? 'selected' : '' }}>Staff Administrasi</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="join_date" class="form-label">Tanggal Bergabung</label>
                                    <input type="text" class="form-control datepicker" id="join_date" name="join_date" value="{{ old('join_date') }}">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="education_level" class="form-label">Pendidikan Terakhir</label>
                                    <select class="form-select" id="education_level" name="education_level">
                                        <option value="" selected disabled>Pilih Pendidikan Terakhir</option>
                                        <option value="D3" {{ old('education_level') == 'D3' ? 'selected' : '' }}>D3</option>
                                        <option value="S1" {{ old('education_level') == 'S1' ? 'selected' : '' }}>S1</option>
                                        <option value="S2" {{ old('education_level') == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('education_level') == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="education_institution" class="form-label">Institusi Pendidikan</label>
                                    <input type="text" class="form-control" id="education_institution" name="education_institution" value="{{ old('education_institution') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bx bx-x me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        }
        
        // Initialize Flatpickr for date pickers
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                allowInput: true,
                altInput: true,
                altFormat: "d M Y"
            });
        }
    });
</script>
@endsection 