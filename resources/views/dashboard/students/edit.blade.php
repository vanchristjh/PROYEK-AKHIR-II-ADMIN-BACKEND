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
        <h5 class="card-title mb-0">Form Edit Akun Siswa</h5>
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

        <form action="{{ route('students.update', $student) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis', $student->nis) }}" required>
                </div>
                
                <div class="col-md-6">
                    <label for="class" class="form-label">Kelas <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="class" name="class" value="{{ old('class', $student->class) }}" required>
                </div>
                
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $student->name) }}" required>
                </div>

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

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary ms-1">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 