@extends('layouts.dashboard')

@section('title', 'Daftar Tugas')

@section('header', 'Daftar Tugas')

@section('navigation')
    <li>
        <a href="{{ route('siswa.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-indigo-700 transition-all duration-200">
                <i class="fas fa-tachometer-alt text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.schedule.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-blue-700/50 transition-all duration-200">
                <i class="fas fa-calendar-alt text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Jadwal Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.assignments.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-2.5 group relative text-white">
            <div class="p-1.5 rounded-lg bg-blue-800 transition-all duration-200">
                <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center text-white"></i>
            </div>
            <span class="ml-3">Tugas</span>
            <span class="absolute inset-y-0 left-0 w-1 bg-blue-400 rounded-tr-md rounded-br-md"></span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-blue-700/50 transition-all duration-200">
                <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-blue-700/50 transition-all duration-200">
                <i class="fas fa-star text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Nilai</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-red-700/50 transition-all duration-200">
                <i class="fas fa-bullhorn text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Enhanced Header with animation and floating elements -->
    <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 animate-gradient-x rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden">
        <div class="particles-container absolute inset-0 pointer-events-none"></div>
        <div class="absolute -right-5 -top-5 opacity-10 transform hover:scale-110 transition-transform duration-700">
            <i class="fas fa-tasks text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-blue-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10 animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2 flex items-center">
                        <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3 shadow-inner backdrop-blur-sm">
                            <i class="fas fa-tasks"></i>
                        </div>
                        Daftar Tugas
                    </h2>
                    <p class="text-blue-100 ml-1">Kelola semua tugas dari berbagai mata pelajaran Anda dengan mudah</p>
                </div>
                <div class="hidden md:flex items-center space-x-2">
                    <div class="bg-white/15 rounded-lg px-3 py-2 backdrop-blur-sm flex items-center">
                        <span class="text-sm font-medium">Tugas selesai: </span>
                        <span class="ml-2 bg-green-500 text-white text-sm px-2 py-0.5 rounded-md">{{ $completedCount ?? 0 }}</span>
                    </div>
                    <div class="bg-white/15 rounded-lg px-3 py-2 backdrop-blur-sm flex items-center">
                        <span class="text-sm font-medium">Tugas tertunda: </span>
                        <span class="ml-2 bg-yellow-500 text-white text-sm px-2 py-0.5 rounded-md">{{ $pendingCount ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100/80">
        <form action="{{ route('siswa.assignments.index') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex flex-1 items-center w-full">
                <div class="relative rounded-md flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tugas..." 
                           class="block w-full pl-10 sm:text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-300">
                </div>
            </div>
            <div class="flex flex-wrap md:flex-nowrap gap-3 w-full sm:w-auto">
                <select name="subject" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-300">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects ?? [] as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-300">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Selesai</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-opacity-50 flex items-center">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Assignments List -->
    <div class="grid grid-cols-1 gap-6 mb-6">
        @forelse($assignments ?? [] as $assignment)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/80 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 group">
                <!-- Assignment Status Badge -->
                <div class="p-1 {{ $assignment->isExpired() && $assignment->submissions->isEmpty() ? 'bg-red-500' : ($assignment->submissions->isNotEmpty() ? 'bg-green-500' : 'bg-yellow-500') }}"></div>
                
                <!-- Assignment Content -->
                <div class="p-5">
                    <div class="flex flex-col md:flex-row justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg 
                                    {{ $assignment->isExpired() && $assignment->submissions->isEmpty() ? 'bg-red-100 text-red-600' : 
                                      ($assignment->submissions->isNotEmpty() ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600') }}">
                                    <i class="fas fa-{{ $assignment->isExpired() && $assignment->submissions->isEmpty() ? 'exclamation-circle' : 
                                                     ($assignment->submissions->isNotEmpty() ? 'check-circle' : 'clock') }} text-xl"></i>
                                </span>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-gray-800">{{ $assignment->title }}</h3>
                                    <p class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-book mr-1"></i> {{ $assignment->subject->name }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="pl-12 pb-3">
                                <p class="text-gray-600 mb-3 line-clamp-2">{{ $assignment->description }}</p>
                                
                                <div class="flex flex-wrap gap-3 text-sm">
                                    <div class="bg-gray-100 px-3 py-1 rounded-full flex items-center">
                                        <i class="fas fa-calendar-alt text-gray-500 mr-1.5"></i>
                                        <span>{{ $assignment->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="bg-gray-100 px-3 py-1 rounded-full flex items-center">
                                        <i class="fas fa-calendar-check text-gray-500 mr-1.5"></i>
                                        <span class="{{ $assignment->isExpired() ? 'text-red-600 font-medium' : '' }}">Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}</span>
                                    </div>
                                    
                                    <!-- Submission Status -->
                                    <div class="{{ $assignment->submissions->isNotEmpty() ? 'bg-green-100 text-green-800' : ($assignment->isExpired() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }} px-3 py-1 rounded-full flex items-center">
                                        <i class="fas fa-{{ $assignment->submissions->isNotEmpty() ? 'check-circle' : ($assignment->isExpired() ? 'times-circle' : 'hourglass-half') }} mr-1.5"></i>
                                        <span>{{ $assignment->submissions->isNotEmpty() ? 'Selesai' : ($assignment->isExpired() ? 'Terlewat' : 'Belum dikerjakan') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Countdown or Completed Time -->
                        <div class="mt-3 md:mt-0 md:ml-4 flex flex-col items-center justify-center">
                            @if($assignment->submissions->isNotEmpty())
                                <div class="text-center">
                                    <div class="text-sm font-medium text-green-700 mb-1">Diserahkan pada</div>
                                    <div class="bg-green-50 rounded-lg px-4 py-2 text-center mb-3">
                                        <p class="text-sm font-medium text-green-800">{{ $assignment->submissions->first()->created_at->format('d M Y') }}</p>
                                        <p class="text-xs text-green-600">{{ $assignment->submissions->first()->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                            @elseif($assignment->isExpired())
                                <div class="text-center">
                                    <div class="text-sm font-medium text-red-600 mb-1">Waktu Habis</div>
                                    <div class="bg-red-50 text-red-700 rounded-lg px-4 py-2 text-center mb-3 font-medium">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Terlewat
                                    </div>
                                </div>
                            @else
                                <div class="text-center">
                                    <div class="text-sm font-medium text-yellow-700 mb-1">Sisa Waktu</div>
                                    <div class="bg-yellow-50 rounded-lg px-4 py-2 text-center mb-3 countdown" data-deadline="{{ $assignment->deadline->toIso8601String() }}">
                                        <div class="text-sm font-medium text-yellow-800">{{ $assignment->remainingTime }}</div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Action Button -->
                            <a href="{{ route('siswa.assignments.show', $assignment->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-5 rounded-lg transition-all duration-300 flex items-center justify-center min-w-[120px] focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-opacity-50">
                                @if($assignment->submissions->isNotEmpty())
                                    <i class="fas fa-eye mr-2"></i> Lihat
                                @else
                                    <i class="fas fa-pencil-alt mr-2"></i> Kerjakan
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm p-10 text-center border border-gray-100/80">
                <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-blue-100 text-blue-600 mb-6 animate-pulse">
                    <i class="fas fa-tasks text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-800 mb-2">Belum ada tugas</h3>
                <p class="text-gray-500 mb-6">Anda belum memiliki tugas yang ditugaskan. Periksa kembali nanti.</p>
                <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-home mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if(isset($assignments) && $assignments->hasPages())
        <div class="flex justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                {{ $assignments->withQueryString()->links() }}
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
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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
        background-color: #3B82F6;
        border-color: #3B82F6;
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
        
        // Initialize countdown timers
        updateCountdowns();
        setInterval(updateCountdowns, 60000); // Update every minute
    });
    
    function updateCountdowns() {
        const countdownElements = document.querySelectorAll('.countdown');
        
        countdownElements.forEach(element => {
            const deadline = new Date(element.dataset.deadline);
            const now = new Date();
            
            if (now >= deadline) {
                // Deadline passed
                element.innerHTML = '<div class="text-sm font-medium text-red-600">Waktu habis!</div>';
                element.classList.remove('bg-yellow-50');
                element.classList.add('bg-red-50');
                return;
            }
            
            // Calculate remaining time
            const diff = deadline - now;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            // Format remaining time
            let remainingTime = '';
            
            if (days > 0) {
                remainingTime += `${days} hari `;
            }
            
            if (hours > 0 || days > 0) {
                remainingTime += `${hours} jam `;
            }
            
            remainingTime += `${minutes} menit`;
            
            // Update countdown display
            element.querySelector('div').textContent = remainingTime;
            
            // Add warning class if close to deadline
            if (diff < 24 * 60 * 60 * 1000) { // Less than 24 hours
                element.classList.remove('bg-yellow-50');
                element.classList.add('bg-orange-50');
                element.querySelector('div').classList.remove('text-yellow-800');
                element.querySelector('div').classList.add('text-orange-800');
            }
        });
    }
</script>
@endpush
