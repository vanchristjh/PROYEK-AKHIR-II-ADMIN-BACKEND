@extends('layouts.dashboard')

@section('title', 'Pengumuman')

@section('header', 'Pengumuman')

@section('navigation')
    <li>
        <a href="{{ route('siswa.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.material.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tasks text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-star text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Nilai</span>
        </a>
    </li>
    @if(Route::has('siswa.attendance.index'))
    <li>
        <a href="{{ route('siswa.attendance.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-clipboard-check text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kehadiran</span>
        </a>
    </li>
    @endif
    <li>
        <a href="{{ route('siswa.announcements.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-bullhorn text-lg w-6"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Enhanced Header with animation and floating elements -->
    <div class="bg-gradient-to-r from-red-600 via-red-500 to-orange-500 animate-gradient-x rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden">
        <div class="particles-container absolute inset-0 pointer-events-none"></div>
        <div class="absolute -right-5 -top-5 opacity-10 transform hover:scale-110 transition-transform duration-700">
            <i class="fas fa-bullhorn text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-orange-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10 animate-fade-in">
            <div class="flex flex-wrap md:flex-nowrap items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2 flex items-center">
                        <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3 shadow-inner backdrop-blur-sm">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        Pengumuman
                    </h2>
                    <p class="text-red-100 ml-1">Ikuti semua informasi dan pemberitahuan penting dari sekolah</p>
                </div>
                <div class="mt-4 md:mt-0 md:ml-2 flex items-center gap-3">
                    <div class="bg-white/15 rounded-lg px-3 py-2 backdrop-blur-sm flex items-center">
                        <i class="fas fa-bell text-yellow-300 mr-2"></i>
                        <span class="text-sm font-medium">
                            {{ $importantCount ?? 0 }} Pengumuman Penting
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100/80">
        <form action="{{ route('siswa.announcements.index') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex flex-1 items-center w-full">
                <div class="relative rounded-md flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengumuman..." 
                           class="block w-full pl-10 sm:text-sm rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-all duration-300">
                </div>
            </div>
            <div class="flex flex-wrap gap-3 w-full sm:w-auto">
                <select name="importance" class="rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-all duration-300">
                    <option value="">Semua Pengumuman</option>
                    <option value="important" {{ request('importance') == 'important' ? 'selected' : '' }}>Pengumuman Penting</option>
                    <option value="regular" {{ request('importance') == 'regular' ? 'selected' : '' }}>Pengumuman Reguler</option>
                </select>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-opacity-50 flex items-center">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Announcements List -->
    <div class="grid grid-cols-1 gap-6 mb-8">
        @forelse($announcements ?? [] as $announcement)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border {{ $announcement->is_important ? 'border-red-200' : 'border-gray-100/80' }} hover:shadow-md transition-all duration-300 group">
                <!-- Important Badge -->
                @if($announcement->is_important)
                    <div class="p-1 bg-red-500"></div>
                @endif
                
                <div class="p-5">
                    <div class="flex flex-col md:flex-row">
                        <!-- Announcement Icon -->
                        <div class="flex-shrink-0 mb-4 md:mb-0">
                            <div class="inline-flex items-center justify-center h-14 w-14 rounded-xl {{ $announcement->is_important ? 'bg-red-100 text-red-600 animate-pulse-slow' : 'bg-orange-100 text-orange-600' }}">
                                <i class="fas fa-{{ $announcement->is_important ? 'exclamation-circle' : 'bullhorn' }} text-2xl"></i>
                            </div>
                        </div>
                        
                        <!-- Announcement Content -->
                        <div class="md:ml-5 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                @if($announcement->is_important)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Penting
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->isNew() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $announcement->isNew() ? 'Baru' : $announcement->publish_date->diffForHumans() }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-800 group-hover:text-red-600 transition-colors mb-2">
                                {{ $announcement->title }}
                            </h3>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <div class="flex items-center mr-4">
                                    <i class="fas fa-user-circle mr-1.5 text-gray-400"></i>
                                    <span>{{ $announcement->author->name }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i>
                                    <span>{{ $announcement->publish_date->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 line-clamp-2 text-sm">{{ $announcement->excerpt() }}</p>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <a href="{{ route('siswa.announcements.show', $announcement->id) }}" class="text-red-600 hover:text-red-800 font-medium inline-flex items-center group">
                                    <span>Baca selengkapnya</span>
                                    <i class="fas fa-arrow-right ml-2 text-xs transition-transform group-hover:translate-x-1"></i>
                                </a>
                                
                                @if($announcement->hasAttachment())
                                    <div class="flex items-center text-gray-500 text-sm">
                                        <i class="fas fa-paperclip mr-1"></i>
                                        <span>{{ $announcement->getAttachmentName() }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm p-10 text-center border border-gray-100/80">
                <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-red-100 text-red-600 mb-6">
                    <i class="fas fa-bell-slash text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-800 mb-2">Belum ada pengumuman</h3>
                <p class="text-gray-500 mb-0">Saat ini tidak ada pengumuman yang ditampilkan. Periksa kembali nanti.</p>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if(isset($announcements) && $announcements->hasPages())
        <div class="flex justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                {{ $announcements->withQueryString()->links() }}
            </nav>
        </div>
    @endif
@endsection

@push('styles')
<style>
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
    
    .particles-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
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
    
    /* Ensure pagination looks nice */
    .pagination {
        display: flex;
        list-style-type: none;
    }
    
    .pagination li {
        display: inline-flex;
    }
    
    .pagination li a, .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        font-weight: 500;
        color: #4B5563;
        background-color: #ffffff;
        border: 1px solid #D1D5DB;
        transition: all 0.2s ease-in-out;
    }
    
    .pagination .active span {
        color: #ffffff;
        background-color: #EF4444;
        border-color: #EF4444;
    }
    
    .pagination a:hover {
        color: #1F2937;
        background-color: #F3F4F6;
    }
    
    .pagination .disabled span {
        color: #9CA3AF;
        pointer-events: none;
        background-color: #F3F4F6;
    }
    
    .pagination li:first-child a,
    .pagination li:first-child span {
        margin-left: 0;
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }
    
    .pagination li:last-child a,
    .pagination li:last-child span {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endpush
