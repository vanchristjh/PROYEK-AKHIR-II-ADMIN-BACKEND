@extends('layouts.dashboard')

@section('title', 'Daftar Materi Pelajaran')

@section('header', 'Daftar Materi Pelajaran')

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
        <a href="{{ route('siswa.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-blue-700/50 transition-all duration-200">
                <i class="fas fa-tasks text-lg w-5 h-5 flex items-center justify-center text-indigo-300 group-hover:text-white"></i>
            </div>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.materials.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-2.5 group relative text-white">
            <div class="p-1.5 rounded-lg bg-purple-800 transition-all duration-200">
                <i class="fas fa-book text-lg w-5 h-5 flex items-center justify-center text-white"></i>
            </div>
            <span class="ml-3">Materi Pelajaran</span>
            <span class="absolute inset-y-0 left-0 w-1 bg-purple-400 rounded-tr-md rounded-br-md"></span>
        </a>
    </li>
    <li>
        <a href="{{ route('siswa.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-2.5 group relative text-indigo-100 hover:text-white transition-all duration-200">
            <div class="p-1.5 rounded-lg bg-indigo-700/50 group-hover:bg-green-700/50 transition-all duration-200">
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
    <div class="bg-gradient-to-r from-purple-600 via-purple-500 to-indigo-600 animate-gradient-x rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden">
        <div class="particles-container absolute inset-0 pointer-events-none"></div>
        <div class="absolute -right-5 -top-5 opacity-10 transform hover:scale-110 transition-transform duration-700">
            <i class="fas fa-book text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-purple-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10 animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2 flex items-center">
                        <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center mr-3 shadow-inner backdrop-blur-sm">
                            <i class="fas fa-book"></i>
                        </div>
                        Materi Pembelajaran
                    </h2>
                    <p class="text-purple-100 ml-1">Akses semua materi pembelajaran dari setiap mata pelajaran</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/15 rounded-lg px-3 py-2 backdrop-blur-sm flex items-center">
                        <i class="fas fa-bookmark text-amber-300 mr-2"></i>
                        <span class="text-sm font-medium">
                            Total: {{ $totalMaterials ?? 0 }} Materi
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100/80">
        <form action="{{ route('siswa.materials.index') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex flex-1 items-center w-full">
                <div class="relative rounded-md flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari materi..." 
                           class="block w-full pl-10 sm:text-sm rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300">
                </div>
            </div>
            <div class="flex flex-wrap md:flex-nowrap gap-3 w-full sm:w-auto">
                <select name="subject" class="rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects ?? [] as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:ring-opacity-50 flex items-center">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Materials Grid View with Card Design -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        @forelse($materials ?? [] as $material)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/80 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 group">
                <!-- Material Card -->
                <div class="p-1 bg-gradient-to-r from-purple-500 to-indigo-600"></div>
                
                <div class="p-5">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-xl flex items-center justify-center
                                {{ $material->getFileType() === 'PDF Document' ? 'bg-red-100 text-red-600' : 
                                ($material->getFileType() === 'PowerPoint Presentation' ? 'bg-orange-100 text-orange-600' : 
                                ($material->getFileType() === 'Word Document' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600')) }}">
                                <i class="fas fa-{{ $material->getFileType() === 'PDF Document' ? 'file-pdf' : 
                                                  ($material->getFileType() === 'PowerPoint Presentation' ? 'file-powerpoint' : 
                                                  ($material->getFileType() === 'Word Document' ? 'file-word' : 'file-alt')) }} text-2xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $material->getFileTypeShort() }}
                                </span>
                                @if($material->isNew())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 animate-pulse-slow">
                                        <i class="fas fa-certificate mr-1 text-green-500"></i> Baru
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-lg font-medium text-gray-800 mt-1 group-hover:text-purple-600 transition-colors duration-300">
                                {{ $material->title }}
                            </h3>
                            
                            <div class="flex items-center text-sm text-gray-500 mt-1">
                                <i class="fas fa-book mr-1.5 text-gray-400"></i>
                                <span>{{ $material->subject->name }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-gray-600 text-sm line-clamp-2">{{ $material->description }}</p>
                    </div>
                    
                    <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                        <div class="flex items-center text-xs text-gray-500">
                            <div class="flex items-center mr-3">
                                <i class="fas fa-user-circle mr-1 text-gray-400"></i>
                                <span>{{ $material->teacher->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>
                                <span>{{ $material->publish_date->format('d M Y') }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('siswa.materials.show', $material->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300">
                            <i class="fas fa-download mr-1.5"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 xl:col-span-3 bg-white rounded-xl shadow-sm p-10 text-center border border-gray-100/80">
                <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-purple-100 text-purple-600 mb-6 animate-bounce-slow">
                    <i class="fas fa-book text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-800 mb-2">Belum ada materi pelajaran</h3>
                <p class="text-gray-500 mb-6">Materi pelajaran akan ditampilkan di sini saat guru mengunggahnya.</p>
                <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-home mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if(isset($materials) && $materials->hasPages())
        <div class="flex justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                {{ $materials->withQueryString()->links() }}
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
    
    .animate-bounce-slow {
        animation: bounce-slow 3s infinite;
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
        }
        50% {
            opacity: 0.7;
        }
    }
    
    @keyframes bounce-slow {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    /* Line clamp for description text */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
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
        background-color: #8B5CF6;
        border-color: #8B5CF6;
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
