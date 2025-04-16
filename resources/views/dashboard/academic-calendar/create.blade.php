@extends('layouts.dashboard')

@php
use Illuminate\Support\Str;
@endphp

@section('page-title', 'Tambah Agenda Kalender Akademik')

@section('page-actions')
<a href="{{ route('academic-calendar.index') }}" class="btn btn-secondary btn-sm">
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

        <form action="{{ route('academic-calendar.store') }}" method="POST" id="calendarForm">
            @csrf
            
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi Agenda</h5>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Agenda <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                                <small class="text-muted">Berikan deskripsi detil tentang agenda ini.</small>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', '08:00') }}" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', now()->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', '16:00') }}" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Pengaturan Agenda</h5>
                            
                            <div class="mb-3">
                                <label for="event_type" class="form-label">Jenis Agenda <span class="text-danger">*</span></label>
                                <select class="form-select" id="event_type" name="event_type" required>
                                    <option value="">-- Pilih Jenis Agenda --</option>
                                    <option value="academic" {{ old('event_type') == 'academic' ? 'selected' : '' }}>Akademik</option>
                                    <option value="exam" {{ old('event_type') == 'exam' ? 'selected' : '' }}>Ujian</option>
                                    <option value="holiday" {{ old('event_type') == 'holiday' ? 'selected' : '' }}>Libur</option>
                                    <option value="meeting" {{ old('event_type') == 'meeting' ? 'selected' : '' }}>Rapat</option>
                                    <option value="extracurricular" {{ old('event_type') == 'extracurricular' ? 'selected' : '' }}>Ekstrakurikuler</option>
                                    <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="is_important" value="0">
                                    <input class="form-check-input" type="checkbox" id="is_important" name="is_important" value="1" {{ old('is_important') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_important">
                                        Tandai sebagai Agenda Penting
                                    </label>
                                </div>
                                <small class="text-muted">Agenda penting akan mendapat penekanan visual khusus.</small>
                            </div>
                            
                            <div class="mb-3" id="eventColorContainer">
                                <label class="form-label">Warna Agenda (Opsional)</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="color-option" data-color="#0d6efd">
                                        <span class="color-circle" style="background-color: #0d6efd;"></span>
                                    </div>
                                    <div class="color-option" data-color="#dc3545">
                                        <span class="color-circle" style="background-color: #dc3545;"></span>
                                    </div>
                                    <div class="color-option" data-color="#198754">
                                        <span class="color-circle" style="background-color: #198754;"></span>
                                    </div>
                                    <div class="color-option" data-color="#ffc107">
                                        <span class="color-circle" style="background-color: #ffc107;"></span>
                                    </div>
                                    <div class="color-option" data-color="#6f42c1">
                                        <span class="color-circle" style="background-color: #6f42c1;"></span>
                                    </div>
                                    <div class="color-option" data-color="#fd7e14">
                                        <span class="color-circle" style="background-color: #fd7e14;"></span>
                                    </div>
                                </div>
                                <input type="hidden" name="color" id="color_input" value="{{ old('color') }}">
                                <small class="text-muted d-block mt-1">Klik untuk memilih warna, biarkan kosong untuk warna default.</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi Tambahan</h5>
                            
                            <div class="mb-3">
                                <label for="academic_year" class="form-label">Tahun Akademik</label>
                                <select class="form-select" id="academic_year" name="academic_year">
                                    <option value="">-- Pilih Tahun Akademik --</option>
                                    <option value="2023/2024" {{ old('academic_year') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                                    <option value="2024/2025" {{ old('academic_year') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                <select class="form-select" id="semester" name="semester">
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="target_audience" class="form-label">Target Peserta <span class="text-danger">*</span></label>
                                <select class="form-select" id="target_audience" name="target_audience" required>
                                    <option value="">-- Pilih Target Peserta --</option>
                                    <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>Semua</option>
                                    <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>Siswa</option>
                                    <option value="teachers" {{ old('target_audience') == 'teachers' ? 'selected' : '' }}>Guru</option>
                                    <option value="staff" {{ old('target_audience') == 'staff' ? 'selected' : '' }}>Staf</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary" id="saveButton">
                            <i class="bx bx-save me-1"></i> Simpan Agenda
                        </button>
                        <a href="{{ route('academic-calendar.index') }}" class="btn btn-outline-secondary">
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
<style>
    .color-option {
        cursor: pointer;
        border: 2px solid transparent;
        border-radius: 50%;
        padding: 2px;
        transition: all 0.2s;
    }
    
    .color-option.selected {
        border-color: #212529;
    }
    
    .color-circle {
        display: block;
        width: 25px;
        height: 25px;
        border-radius: 50%;
    }
    
    #event_type {
        padding-left: 30px;
        background-position: 10px center;
        background-repeat: no-repeat;
        background-size: 16px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-update end date when start date changes
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        startDateInput.addEventListener('change', function() {
            // Only update end date if it's before start date
            if (endDateInput.value < startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        });
        
        // Set minimum date for end date based on start date
        startDateInput.addEventListener('input', function() {
            endDateInput.min = startDateInput.value;
        });
        
        // Initialize with the current start date
        endDateInput.min = startDateInput.value;

        // Event type icons and colors
        const eventTypeSelect = document.getElementById('event_type');
        const colorOptions = document.querySelectorAll('#eventColorContainer .color-option');
        const colorInput = document.getElementById('color_input');
        
        // Update event type icon
        eventTypeSelect.addEventListener('change', function() {
            updateEventTypeIcon(this.value);
        });
        
        function updateEventTypeIcon(type) {
            let iconUrl = '';
            
            switch(type) {
                case 'academic':
                    iconUrl = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%230d6efd" class="bi bi-book" viewBox="0 0 16 16"%3E%3Cpath d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/3E%3C/svg%3E';
                    break;
                case 'exam':
                    iconUrl = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23dc3545" class="bi bi-pencil-square" viewBox="0 0 16 16"%3E%3Cpath d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/%3E%3Cpath fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/%3E%3C/svg%3E';
                    break;
                case 'holiday':
                    iconUrl = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23198754" class="bi bi-cup-hot" viewBox="0 0 16 16"%3E%3Cpath fill-rule="evenodd" d="M.5 6a.5.5 0 0 0-.488.608l1.652 7.434A2.5 2.5 0 0 0 4.104 16h5.792a2.5 2.5 0 0 0 2.44-1.958l.131-.59a3 3 0 0 0 1.3-5.854l.221-.99A.5.5 0 0 0 13.5 6zM13 12.5a2 2 0 0 1-.316-.025l.867-3.898A2.001 2.001 0 0 1 13 12.5M2.64 13.825 1.123 7h11.754l-1.517 6.825A1.5 1.5 0 0 1 9.896 15H4.104a1.5 1.5 0 0 1-1.464-1.175Z"/%3E%3Cpath d="m4.4.8-.003.004-.014.019a4 4 0 0 0-.204.31 2 2 0 0 0-.141.267c-.026.06-.034.092-.037.103v.004a.593.593 0 0 0 .091.248c.075.133.178.272.308.445l.01.012c.118.158.26.347.37.543.112.2.22.455.22.745 0 .188-.065.368-.119.494a3 3 0 0 1-.202.388 5 5 0 0 1-.253.382l-.018.025-.005.008-.002.002A.5.5 0 0 1 3.6 4.2l.003-.004.014-.019a4 4 0 0 0 .204-.31 2 2 0 0 0 .141-.267c.026-.06.034-.092.037-.103a.593.593 0 0 0-.09-.252A4 4 0 0 0 3.6 2.8l-.01-.012a5 5 0 0 1-.37-.543A1.53 1.53 0 0 1 3 1.5c0-.188.065-.368.119-.494.059-.138.134-.274.202-.388a5 5 0 0 1 .253-.382l.018-.025.005-.008.002-.002A.5.5 0 0 1 4.4.8m3 0-.003.004-.014.019a4 4 0 0 0-.204.31 2 2 0 0 0-.141.267c-.026.06-.034.092-.037.103v.004a.593.593 0 0 0 .091.248c.075.133.178.272.308.445l.01.012c.118.158.26.347.37.543.112.2.22.455.22.745 0 .188-.065.368-.119.494a3 3 0 0 1-.202.388 5 5 0 0 1-.253.382l-.018.025-.005.008-.002.002A.5.5 0 0 1 6.6 4.2l.003-.004.014-.019a4 4 0 0 0 .204-.31 2 2 0 0 0 .141-.267c.026-.06.034-.092.037-.103a.593.593 0 0 0-.09-.252A4 4 0 0 0 6.6 2.8l-.01-.012a5 5 0 0 1-.37-.543A1.53 1.53 0 0 1 6 1.5c0-.188.065-.368.119-.494.059-.138.134-.274.202-.388a5 5 0 0 1 .253-.382l.018-.025.005-.008.002-.002A.5.5 0 0 1 7.4.8"/%3E%3C/svg%3E';
                    break;
                case 'meeting':
                    iconUrl = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%236f42c1" class="bi bi-people" viewBox="0 0 16 16"%3E%3Cpath d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/%3E%3C/svg%3E';
                    break;
                case 'extracurricular':
                    iconUrl = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23fd7e14" class="bi bi-dribbble" viewBox="0 0 16 16"%3E%3Cpath fill-rule="evenodd" d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m5.7 9.71a6.96 6.96 0 0 0-2.842-4.464 7.114 7.114 0 0 1-2.679 4.811 11.019 11.019 0 0 1-.76-1.435c1.73-.8 2.572-1.748 3.156-2.777.53-.945.833-2.255.893-3.896a7.1 7.1 0 0 1 2.308 6.736c-.052.46-.127.92-.224 1.025M8 15c-.97 0-1.89-.19-2.733-.544a9.469 9.469 0 0 0 .044-.046c.433-.466.99-1.142 1.538-1.996.88.238 1.774.423 2.671.483a7.04 7.04 0 0 1-1.52 2.103m-3.568-3.337a14.603 14.603 0 0 0-2.974-1.449c.132-1.76.313-2.82.725-3.729a15.726 15.726 0 0 1 2.43 1.414 8.03 8.03 0 0 0-.181 3.764M8 1c1.463 0 2.813.458 3.922 1.235-.1.26-.203.48-.356.643-.434.456-1.202.928-2.456 1.555a9.9 9.9 0 0 0-2.386-3.112A7.05 7.05 0 0 1 8 1m-6.862 2.38C1.718 4.354 1.326 5.413 1.172 6.74c-.087.753-.1 1.514-.086 2.22a10.804 10.804 0 0 1-.921.064c.075-.97.097-1.85.097-2.582A7.058 7.058 0 0 1 1.138 3.38m6.358 2.795c.104-.223.225-.434.358-.646.881.227 1.858.225 2.914.038a6.14 6.14 0 0 1 1.28 2.713c-.445.222-.913.419-1.394.586a5.602 5.602 0 0 0-3.158-2.69Z"/%3E%3C/svg%3E';
                    break;
                default:
                    iconUrl = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%236c757d" class="bi bi-calendar-event" viewBox="0 0 16 16"%3E%3Cpath d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/%3E%3Cpath d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/%3E%3C/svg%3E';
            }
            
            eventTypeSelect.style.backgroundImage = `url('${iconUrl}')`;
        }
        
        // Initialize selected type if exists
        if (eventTypeSelect.value) {
            updateEventTypeIcon(eventTypeSelect.value);
        }
        
        // Handle color selection
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                colorOptions.forEach(o => o.classList.remove('selected'));
                
                // Add selected class to current option
                this.classList.add('selected');
                
                // Set the color value to hidden input
                colorInput.value = this.getAttribute('data-color');
            });
            
            // Initialize selected color if exists
            if (colorInput.value && colorInput.value === option.getAttribute('data-color')) {
                option.classList.add('selected');
            }
        });

        // Form submission with animation
        const calendarForm = document.getElementById('calendarForm');
        const saveButton = document.getElementById('saveButton');
        
        if (calendarForm) {
            calendarForm.addEventListener('submit', function(e) {
                // Check date range validity
                const startDate = new Date(`${startDateInput.value}T${document.getElementById('start_time').value}`);
                const endDate = new Date(`${endDateInput.value}T${document.getElementById('end_time').value}`);
                
                if (endDate <= startDate) {
                    e.preventDefault();
                    alert('Waktu selesai harus setelah waktu mulai');
                    return false;
                }
                
                if (saveButton) {
                    // Change button text and add spinner
                    saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                    saveButton.disabled = true;
                }
            });
        }
    });
</script>
@endsection
