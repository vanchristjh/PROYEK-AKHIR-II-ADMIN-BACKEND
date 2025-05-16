@extends('layouts.dashboard')

@section('title', 'Siswa Dashboard')

@section('header', 'Dashboard Siswa')

@section('content')
    <!-- Welcome Banner with enhanced animated gradient and floating particles -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 animate-gradient-x rounded-xl shadow-xl p-6 mb-8 text-white relative overflow-hidden">
        <div class="particles-container absolute inset-0 pointer-events-none"></div>
        <div class="absolute right-0 top-0 opacity-10 transform hover:scale-110 transition-transform duration-700">
            <i class="fas fa-user-graduate text-9xl -mt-4 -mr-4"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-indigo-300/20 rounded-full blur-3xl"></div>
        <div class="relative animate-fade-in z-10">
            <h2 class="text-2xl font-bold mb-2">Selamat datang, {{ auth()->user()->name }}!</h2>
            <p class="text-indigo-100">
                {{ auth()->user()->classroom ? 'Kelas: ' . auth()->user()->classroom->name : 'Anda belum ditetapkan ke kelas manapun.' }}
            </p>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('siswa.schedule.index') }}" class="btn-glass flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <i class="fas fa-calendar-alt mr-2"></i> Jadwal Pelajaran
                </a>
                <a href="{{ route('siswa.assignments.index') }}" class="bg-indigo-700/80 text-white hover:bg-indigo-800 px-4 py-2 rounded-lg inline-flex items-center text-sm font-medium transition-all duration-300 shadow-md shadow-indigo-900/30 backdrop-blur-sm hover:shadow-xl hover:-translate-y-1">
                    <i class="fas fa-tasks mr-2"></i> Lihat Tugas
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards with enhanced visuals -->
    <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
            <i class="fas fa-chart-bar text-indigo-600"></i>
        </div>
        <span>Ringkasan Akademik</span>
        <div class="ml-auto text-sm text-gray-500 flex items-center bg-white py-1 px-3 rounded-lg shadow-sm">
            <i class="fas fa-sync-alt mr-1 hover:rotate-180 transition-transform cursor-pointer" id="refresh-data-btn" title="Refresh data"></i>
            <span>Terakhir diperbarui: {{ now()->format('H:i') }}</span>
        </div>
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Subjects Card -->
        <div class="dashboard-card bg-white rounded-xl shadow-sm p-6 transform transition-all hover:scale-105 relative overflow-hidden">
            <div class="absolute right-0 bottom-0 w-20 h-20 bg-indigo-50 rounded-full -mr-10 -mb-10"></div>
            <div class="flex items-start relative">
                <div class="p-3 rounded-lg bg-indigo-100 text-indigo-600 shadow-inner">
                    <i class="fas fa-book text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Mata Pelajaran</h3>
                    <p class="card-number floating-element" data-type="subjects">{{ $stats['subjects'] ?? 0 }}</p>
                    <div class="mt-2">
                        <a href="{{ route('siswa.schedule.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 inline-flex items-center group">
                            <span>Lihat jadwal</span>
                            <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Assignments Card -->
        <div class="dashboard-card bg-white rounded-xl shadow-sm p-6 transform transition-all hover:scale-105 relative overflow-hidden">
            <div class="absolute right-0 bottom-0 w-20 h-20 bg-blue-50 rounded-full -mr-10 -mb-10"></div>
            <div class="flex items-start relative">
                <div class="p-3 rounded-lg bg-blue-100 text-blue-600 shadow-inner">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Tugas</h3>
                    <p class="card-number floating-element" data-type="assignments">{{ $stats['assignments'] ?? 0 }}</p>
                    <div class="mt-2">
                        <a href="{{ route('siswa.assignments.index') }}" class="text-sm text-blue-600 hover:text-blue-800 inline-flex items-center group">
                            <span>Lihat tugas</span>
                            <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Completed Assignments Card -->
        <div class="dashboard-card bg-white rounded-xl shadow-sm p-6 transform transition-all hover:scale-105 relative overflow-hidden">
            <div class="absolute right-0 bottom-0 w-20 h-20 bg-green-50 rounded-full -mr-10 -mb-10"></div>
            <div class="flex items-start relative">
                <div class="p-3 rounded-lg bg-green-100 text-green-600 shadow-inner">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Tugas Selesai</h3>
                    <p class="card-number floating-element" data-type="completedAssignments">{{ $stats['completedAssignments'] ?? 0 }}</p>
                    <div class="mt-2">
                        <a href="{{ route('siswa.submissions.index') }}" class="text-sm text-green-600 hover:text-green-800 inline-flex items-center group">
                            <span>Lihat pengumpulan</span>
                            <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Materials Card -->
        <div class="dashboard-card bg-white rounded-xl shadow-sm p-6 transform transition-all hover:scale-105 relative overflow-hidden">
            <div class="absolute right-0 bottom-0 w-20 h-20 bg-orange-50 rounded-full -mr-10 -mb-10"></div>
            <div class="flex items-start relative">
                <div class="p-3 rounded-lg bg-orange-100 text-orange-600 shadow-inner">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Materi</h3>
                    <p class="card-number floating-element" data-type="materials">{{ $stats['materials'] ?? 0 }}</p>
                    <div class="mt-2">
                        <a href="{{ route('siswa.materials.index') }}" class="text-sm text-orange-600 hover:text-orange-800 inline-flex items-center group">
                            <span>Lihat materi</span>
                            <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Upcoming Tasks & Recent Materials with enhanced styling -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-6">
        <!-- Upcoming Tasks with better card styling -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden transform transition hover:shadow-lg border border-gray-100/60">
            <div class="card-header flex items-center justify-between p-6 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                        <i class="fas fa-clipboard-list text-indigo-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Tugas Mendatang</h3>
                </div>
                <a href="{{ route('siswa.assignments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium hover:underline flex items-center group">
                    <span>Lihat semua</span>
                    <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-4" id="upcoming-assignments-list">
                    @forelse($upcomingAssignments ?? [] as $assignment)
                    <div class="border border-gray-200 {{ $assignment->isExpired() ? 'bg-red-50 border-red-200' : ($assignment->deadline->diffInDays(now()) <= 3 ? 'bg-yellow-50 border-yellow-200' : 'bg-white') }} rounded-lg p-4 transition-all hover:shadow-md hover:-translate-y-1 duration-300">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-12 w-12 rounded-xl 
                                    {{ $assignment->isExpired() ? 'bg-red-100 text-red-600' : 
                                      ($assignment->deadline->diffInDays(now()) <= 3 ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600') }}">
                                    <i class="fas fa-{{ $assignment->isExpired() ? 'exclamation-circle' : 
                                                     ($assignment->deadline->diffInDays(now()) <= 3 ? 'clock' : 'book') }} text-xl"></i>
                                </span>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex flex-wrap justify-between mb-1">
                                    <h4 class="text-base font-medium text-gray-800 mb-1">{{ $assignment->title }}</h4>
                                    <span class="text-sm {{ $assignment->isExpired() ? 'text-red-600 font-medium' : 
                                                          ($assignment->deadline->diffInDays(now()) <= 3 ? 'text-yellow-600 font-medium' : 'text-gray-500') }}">
                                        {{ $assignment->isExpired() ? 'Deadline telah lewat' : 'Deadline: ' . $assignment->deadline->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <i class="fas fa-book-open mr-2 text-gray-400"></i>
                                    <span>{{ $assignment->subject->name }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $assignment->description }}</p>
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('siswa.assignments.show', $assignment->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center group">
                                        <span>Detail tugas</span>
                                        <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                                    </a>
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2.5 py-1 rounded-full">
                                        {{ $assignment->isExpired() ? 'Terlewat' : $assignment->remainingTime }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                            <i class="fas fa-clipboard-check text-2xl"></i>
                        </div>
                        <h4 class="text-base font-medium text-gray-800 mb-1">Belum ada tugas</h4>
                        <p class="text-gray-500 mb-0">Saat ini tidak ada tugas yang perlu dikerjakan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Recent Materials with better card styling -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/60 transform transition hover:shadow-lg">
            <div class="card-header flex items-center justify-between p-6 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg mr-3">
                        <i class="fas fa-book text-purple-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Materi Terbaru</h3>
                </div>
                <a href="{{ route('siswa.materials.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium hover:underline flex items-center group">
                    <span>Lihat semua</span>
                    <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentMaterials ?? [] as $material)
                    <div class="border border-gray-200 rounded-lg p-4 transition-all hover:shadow-md hover:-translate-y-1 duration-300 {{ $loop->first ? 'bg-purple-50 border-purple-200' : 'bg-white' }}">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-12 w-12 rounded-xl 
                                    {{ $material->getFileType() === 'PDF Document' ? 'bg-red-100 text-red-600' : 
                                    ($material->getFileType() === 'PowerPoint Presentation' ? 'bg-orange-100 text-orange-600' : 
                                    ($material->getFileType() === 'Word Document' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600')) }}">
                                    <i class="fas fa-{{ $material->getFileType() === 'PDF Document' ? 'file-pdf' : 
                                                      ($material->getFileType() === 'PowerPoint Presentation' ? 'file-powerpoint' : 
                                                      ($material->getFileType() === 'Word Document' ? 'file-word' : 'file-alt')) }} text-xl"></i>
                                </span>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex flex-wrap items-center mb-1 gap-2">
                                    <h4 class="text-base font-medium text-gray-800">{{ $material->title }}</h4>
                                    @if($material->isNew())
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full">Baru</span>
                                    @endif
                                </div>
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <i class="fas fa-book-open mr-2 text-gray-400"></i>
                                    <span>{{ $material->subject->name }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $material->description }}</p>
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('siswa.materials.show', $material->id) }}" class="text-sm font-medium text-purple-600 hover:text-purple-800 inline-flex items-center group">
                                        <i class="fas fa-download mr-1"></i>
                                        <span>Download</span>
                                        <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                                    </a>
                                    <span class="text-sm text-gray-500">{{ $material->publish_date->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 text-purple-600 mb-4">
                            <i class="fas fa-book text-2xl"></i>
                        </div>
                        <h4 class="text-base font-medium text-gray-800 mb-1">Belum ada materi</h4>
                        <p class="text-gray-500 mb-0">Materi pembelajaran akan ditampilkan di sini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Announcements -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <div class="p-2 bg-red-100 rounded-lg mr-3">
                    <i class="fas fa-bullhorn text-red-600"></i>
                </div>
                <span>Pengumuman Terbaru</span>
            </h3>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/60">
            <div id="announcements-container">
                @forelse($recentAnnouncements ?? [] as $announcement)
                    <div class="p-4 border-b last:border-b-0 {{ $announcement->is_important ? 'bg-red-50' : '' }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full 
                                bg-{{ $announcement->is_important ? 'red' : 'indigo' }}-100 
                                text-{{ $announcement->is_important ? 'red' : 'indigo' }}-600
                                flex items-center justify-center {{ $announcement->is_important ? 'animate-pulse-slow' : '' }}">
                                <i class="fas fa-{{ $announcement->is_important ? 'exclamation-circle' : 'bullhorn' }}"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-base font-medium text-gray-800">
                                    @if($announcement->is_important)
                                        <span class="text-red-600">PENTING:</span>
                                    @endif
                                    {{ $announcement->title }}
                                </h4>
                                <div class="flex justify-between mt-1">
                                    <p class="text-sm text-gray-500">{{ $announcement->author->name }} · {{ $announcement->publish_date->diffForHumans() }}</p>
                                    <a href="{{ route('siswa.announcements.show', $announcement->id) }}" 
                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center group">
                                        <span>Baca selengkapnya</span>
                                        <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 mb-4">
                            <i class="fas fa-bell-slash text-indigo-500"></i>
                        </div>
                        <h3 class="text-base font-medium text-gray-900">Tidak ada pengumuman terbaru</h3>
                        <p class="mt-1 text-sm text-gray-500">Pengumuman baru akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="bg-gray-50 px-4 py-3 text-center">
                <a href="{{ route('siswa.announcements.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    Lihat semua pengumuman <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Schedule Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <i class="fas fa-calendar-alt text-blue-600"></i>
                </div>
                <span>Jadwal Hari Ini</span>
            </h3>
            <a href="{{ route('siswa.schedule.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline flex items-center group">
                <span>Lihat jadwal lengkap</span>
                <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/60">
            <div class="p-6">
                <div class="space-y-4" id="today-schedule">
                    @if(isset($todaySchedule) && count($todaySchedule) > 0)
                        @foreach($todaySchedule as $schedule)
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors duration-300">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-800">{{ $schedule->subject->name }}</p>
                                    <div class="flex items-center text-sm text-gray-500 mt-1">
                                        <i class="fas fa-clock mr-1.5 text-gray-400"></i>
                                        <span>{{ $schedule->formatted_time }}</span>
                                        <span class="mx-1.5">•</span>
                                        <i class="fas fa-user mr-1.5 text-gray-400"></i>
                                        <span>{{ $schedule->teacher->name }}</span>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $schedule->isOngoing ? 'bg-green-100 text-green-800' : 
                                        ($schedule->isUpcoming ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $schedule->isOngoing ? 'Sedang berlangsung' : 
                                        ($schedule->isUpcoming ? 'Akan datang' : 'Selesai') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                                <i class="fas fa-calendar-day text-blue-500"></i>
                            </div>
                            <h3 class="text-base font-medium text-gray-800">Tidak ada jadwal hari ini</h3>
                            <p class="mt-1 text-sm text-gray-500">Anda dapat melihat jadwal lengkap di halaman jadwal pelajaran.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .btn-glass {
        background-color: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-glass:hover {
        background-color: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .animate-gradient-x {
        background-size: 300% 300%;
        animation: gradient-x 15s ease infinite;
    }
    
    .animate-fade-in {
        animation: fade-in 0.6s ease-in-out;
    }
    
    .animate-pulse-slow {
        animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .floating-element {
        animation: floating 3s ease-in-out infinite alternate;
    }
    
    .particles-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    
    .card-number {
        font-size: 1.75rem;
        font-weight: 600;
        color: #4B5563;
        line-height: 1.2;
        margin: 0.5rem 0;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
    }
    
    @keyframes gradient-x {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
    
    @keyframes fade-in {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes floating {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(-5px);
        }
    }
    
    @keyframes pulse-slow {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.05);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Create floating particles effect
        const particlesContainer = document.querySelector('.particles-container');
        if (particlesContainer) {
            for (let i = 0; i < 30; i++) {
                createParticle(particlesContainer);
            }
        }
        
        function createParticle(container) {
            const particle = document.createElement('div');
            
            // Style the particle
            particle.style.position = 'absolute';
            particle.style.width = Math.random() * 5 + 2 + 'px';
            particle.style.height = particle.style.width;
            particle.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
            particle.style.borderRadius = '50%';
            particle.style.pointerEvents = 'none';
            
            // Position the particle randomly
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            
            // Set animation properties
            particle.style.opacity = Math.random() * 0.5 + 0.1;
            const animationDuration = Math.random() * 15 + 10 + 's';
            const animationDelay = Math.random() * 5 + 's';
            
            // Apply animation
            particle.style.animation = `floatingParticle ${animationDuration} ease-in-out ${animationDelay} infinite alternate`;
            
            // Add particle to container
            container.appendChild(particle);
        }
        
        // Add animation keyframes 
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes floatingParticle {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                }
                25% {
                    transform: translate(${Math.random() * 30 - 15}px, ${Math.random() * 30 - 15}px) rotate(${Math.random() * 360}deg);
                }
                50% {
                    transform: translate(${Math.random() * 30 - 15}px, ${Math.random() * 30 - 15}px) rotate(${Math.random() * 360}deg);
                }
                75% {
                    transform: translate(${Math.random() * 30 - 15}px, ${Math.random() * 30 - 15}px) rotate(${Math.random() * 360}deg);
                }
                100% {
                    transform: translate(0, 0) rotate(0deg);
                }
            }
        `;
        document.head.appendChild(style);
        
        // Auto refresh data every 5 minutes (300000ms)
        setInterval(fetchUpdatedData, 300000);
        
        // Refresh button click handler
        document.getElementById('refresh-data-btn').addEventListener('click', function() {
            this.classList.add('animate-spin');
            fetchUpdatedData().then(() => {
                setTimeout(() => {
                    this.classList.remove('animate-spin');
                }, 1000);
            });
        });
        
        // Initialize counters with animation
        animateCounters();
    });
    
    function animateCounters() {
        document.querySelectorAll('.card-number').forEach(counter => {
            const value = parseInt(counter.textContent, 10);
            counter.textContent = '0';
            
            setTimeout(() => {
                const duration = 1500;
                const steps = 20;
                const stepValue = value / steps;
                const stepTime = duration / steps;
                let currentStep = 0;
                
                const interval = setInterval(() => {
                    currentStep++;
                    counter.textContent = Math.ceil(Math.min(stepValue * currentStep, value)).toString();
                    
                    if (currentStep >= steps) {
                        clearInterval(interval);
                    }
                }, stepTime);
            }, 300);
        });
    }
    
    // Function to fetch updated data
    function fetchUpdatedData() {
        return fetch('{{ route("siswa.dashboard.refresh") }}')
            .then(response => response.json())
            .then(data => {
                // Update statistics counters
                if (data.stats) {
                    updateStatCounters(data.stats);
                }
                
                // Update upcoming assignments
                if (data.upcomingAssignments) {
                    updateUpcomingAssignments(data.upcomingAssignments);
                }
                
                // Update recent materials
                if (data.recentMaterials) {
                    updateRecentMaterials(data.recentMaterials);
                }
                
                // Update announcements
                if (data.recentAnnouncements) {
                    updateRecentAnnouncements(data.recentAnnouncements);
                }
                
                // Update today's schedule
                if (data.todaySchedule) {
                    updateTodaySchedule(data.todaySchedule);
                }
                
                // Update last refreshed time
                document.querySelector('#refresh-data-btn').nextElementSibling.textContent = 
                    'Terakhir diperbarui: ' + new Date().getHours() + ':' + 
                    (new Date().getMinutes() < 10 ? '0' : '') + new Date().getMinutes();
            })
            .catch(error => console.error('Error fetching updated dashboard data:', error));
    }
    
    function updateStatCounters(stats) {
        if (stats.subjects) {
            document.querySelector('[data-type="subjects"]').textContent = stats.subjects;
        }
        if (stats.assignments) {
            document.querySelector('[data-type="assignments"]').textContent = stats.assignments;
        }
        if (stats.completedAssignments) {
            document.querySelector('[data-type="completedAssignments"]').textContent = stats.completedAssignments;
        }
        if (stats.materials) {
            document.querySelector('[data-type="materials"]').textContent = stats.materials;
        }
    }
    
    // Other update functions would be defined here to handle the AJAX response data
    // These would update the respective sections of the dashboard
</script>
@endpush