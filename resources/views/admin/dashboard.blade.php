@extends('layouts.app')

@section('content')
<div class="dashboard-admin">
    <div class="welcome-card">
        <div class="icon-container">
            <i class="fas fa-chart-line fa-2x"></i>
        </div>
        <h2>Selamat datang, Admin User!</h2>
        <p>Panel administrasi untuk mengelola data sekolah, pengguna, dan aktivitas akademik.</p>
        <div class="quick-actions">
            <a href="#" class="btn btn-primary">
                <i class="fas fa-bolt"></i> Aksi Cepat
            </a>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                <i class="fas fa-user-cog"></i> Kelola Pengguna
            </a>
        </div>
    </div>

    <div class="stats-section">
        <h3><i class="fas fa-chart-pie"></i> Ringkasan Sistem <span class="last-update">Terakhir diperbarui: {{ now()->format('H:i') }}</span></h3>
        <div class="stats-cards">
            <div class="stat-card student-card">
                <div class="icon-bg">
                    <i class="fas fa-user-graduate fa-2x"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Siswa</h4>
                    <div class="stat-count">{{ $totalStudents ?? 1 }}</div>
                    <a href="#" class="view-all">Lihat semua</a>
                </div>
            </div>
            
            <div class="stat-card teacher-card">
                <div class="icon-bg">
                    <i class="fas fa-chalkboard-teacher fa-2x"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Guru</h4>
                    <div class="stat-count">{{ $totalTeachers ?? 1 }}</div>
                    <a href="#" class="view-all">Lihat semua</a>
                </div>
            </div>
            
            <div class="stat-card class-card">
                <div class="icon-bg">
                    <i class="fas fa-school fa-2x"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Kelas</h4>
                    <div class="stat-count">{{ $totalClasses ?? 0 }}</div>
                    <a href="#" class="view-all">Lihat semua</a>
                </div>
            </div>
            
            <div class="stat-card subject-card">
                <div class="icon-bg">
                    <i class="fas fa-book fa-2x"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Mata Pelajaran</h4>
                    <div class="stat-count">{{ $totalSubjects ?? 1 }}</div>
                    <a href="#" class="view-all">Lihat semua</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rest of your dashboard content with fixed icons -->
</div>

<script>
    // Make sure Font Awesome is loaded properly
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Font Awesome is loaded
        const faLoaded = !!document.querySelector('link[href*="font-awesome"]') || 
                         !!document.querySelector('i.fas') || 
                         !!document.querySelector('i.fab');
        
        if (!faLoaded) {
            // If not loaded, add it manually
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
            document.head.appendChild(link);
        }
    });
</script>
@endsection