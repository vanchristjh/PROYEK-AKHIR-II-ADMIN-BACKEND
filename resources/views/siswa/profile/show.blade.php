@extends('layouts.dashboard')

@section('title', 'Profil Siswa')

@section('header', 'Profil Saya')

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert-container animate-fade-in" style="animation-delay: 0.1s">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    <div class="ml-auto">
                        <button type="button" class="text-green-600 hover:text-green-800" onclick="this.parentElement.parentElement.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Picture Card -->
        <div class="animate-fade-in" style="animation-delay: 0.2s">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 hover:shadow-lg transition-all duration-300 h-full">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-8 relative overflow-hidden">
                    <div class="flex flex-col items-center justify-center relative z-10">
                        <div class="relative mb-5 group">
                            <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-white/70 shadow-lg">
                                @if (isset($user->avatar) && $user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto Profil {{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-purple-400 to-indigo-500 flex items-center justify-center text-white font-bold text-4xl">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold mb-2 text-center">{{ $user->name }}</h4>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white mb-3">
                            <i class="fas fa-user-graduate mr-2"></i>
                            {{ $user->role->name }}
                        </span>
                        
                        @if($user->classroom)
                            <div class="mt-2 px-4 py-2 bg-white/15 backdrop-blur-sm rounded-lg text-white text-sm flex items-center">
                                <i class="fas fa-school mr-2"></i>
                                <span>{{ $user->classroom->name }}</span>
                            </div>
                        @endif
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-56 h-56 bg-white/10 rounded-full"></div>
                    <!-- Decorative particles -->
                    <div class="particles-container absolute inset-0 pointer-events-none"></div>
                </div>
                <div class="p-6">
                    <div class="space-y-5">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0 shadow-sm">
                                <i class="fas fa-envelope text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">Alamat Email</h6>
                                <p class="font-medium text-gray-800">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 flex-shrink-0 shadow-sm">
                                <i class="fas fa-id-card text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">NIS/NISN</h6>
                                <p class="font-medium text-gray-800">{{ $user->nisn ?? ($user->student->nisn ?? 'Tidak tersedia') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0 shadow-sm">
                                <i class="fas fa-calendar-check text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">Bergabung Sejak</h6>
                                <p class="font-medium text-gray-800">{{ $user->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 text-center">
                        <a href="{{ route('siswa.settings.index') }}" class="inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-cog mr-2"></i>
                            Pengaturan Akun
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Account and Academic Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Account Details -->
            <div class="animate-fade-in" style="animation-delay: 0.3s">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 hover:shadow-md transition-all duration-300 h-full">
                    <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                        <h5 class="font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-user-circle text-indigo-500 mr-2"></i>
                            Detail Akun
                        </h5>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Nama Lengkap</div>
                                <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Email</div>
                                <div class="font-semibold text-gray-800">{{ $user->email }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">NIS/NISN</div>
                                <div class="font-semibold text-gray-800">{{ $user->nisn ?? ($user->student->nisn ?? 'Tidak tersedia') }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Kelas</div>
                                <div class="font-semibold text-gray-800">{{ $user->classroom ? $user->classroom->name : 'Belum ditetapkan' }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Role</div>
                                <div class="font-semibold">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-indigo-800 bg-indigo-100">
                                        {{ $user->role->name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Info Card -->
            <div class="animate-fade-in" style="animation-delay: 0.4s">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 hover:shadow-md transition-all duration-300 h-full">
                    <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                        <h5 class="font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-chart-line text-indigo-500 mr-2"></i>
                            Informasi Akademik
                        </h5>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kelas Card -->
                            <div class="bg-gray-50 rounded-lg p-5">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-school text-blue-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-gray-700 font-medium">Kelas Saat Ini</h4>
                                        <p class="text-blue-600 text-lg font-semibold">{{ $user->classroom ? $user->classroom->name : 'Belum ditetapkan' }}</p>
                                    </div>
                                </div>
                                <div class="pl-16">
                                    <p class="text-gray-500 text-sm">Wali Kelas: 
                                        <span class="font-medium text-gray-700">
                                            {{ $user->classroom && $user->classroom->homeRoomTeacher ? $user->classroom->homeRoomTeacher->name : 'Belum ditetapkan' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Absensi Card -->
                            <div class="bg-gray-50 rounded-lg p-5">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-clipboard-check text-green-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-gray-700 font-medium">Kehadiran</h4>
                                        <p class="text-green-600 text-lg font-semibold">
                                            {{ $user->studentAttendanceRecords && $user->studentAttendanceRecords->isNotEmpty() ? 
                                                round(($user->studentAttendanceRecords->where('status', 'present')->count() / $user->studentAttendanceRecords->count()) * 100) . '%' : 
                                                'Belum ada data' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="pl-16">
                                    <p class="text-gray-500 text-sm">Total Pertemuan: 
                                        <span class="font-medium text-gray-700">
                                            {{ $user->studentAttendanceRecords ? $user->studentAttendanceRecords->count() : '0' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Tugas Card -->
                            <div class="bg-gray-50 rounded-lg p-5">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-book-reader text-purple-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-gray-700 font-medium">Tugas</h4>
                                        <p class="text-purple-600 text-lg font-semibold">
                                            {{ $user->assignments ? $user->assignments->count() : '0' }} Tugas
                                        </p>
                                    </div>
                                </div>
                                <div class="pl-16">
                                    <p class="text-gray-500 text-sm">Diselesaikan: 
                                        <span class="font-medium text-gray-700">
                                            {{ $user->assignments ? $user->assignments->where('status', 'submitted')->count() : '0' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Nilai Card -->
                            <div class="bg-gray-50 rounded-lg p-5">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-medal text-amber-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-gray-700 font-medium">Rata-rata Nilai</h4>
                                        <p class="text-amber-600 text-lg font-semibold">
                                            {{ $user->grades && $user->grades->isNotEmpty() ? 
                                                number_format($user->grades->avg('score'), 1) : 
                                                'Belum ada nilai' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="pl-16">
                                    <p class="text-gray-500 text-sm">Total Penilaian: 
                                        <span class="font-medium text-gray-700">
                                            {{ $user->grades ? $user->grades->count() : '0' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Create floating particles effect for profile card
        const particlesContainers = document.querySelectorAll('.particles-container');
        particlesContainers.forEach(container => {
            createParticles(container);
        });
        
        function createParticles(container) {
            if (!container) return;
            for (let i = 0; i < 25; i++) {
                const particle = document.createElement('div');
                
                particle.style.position = 'absolute';
                particle.style.width = Math.random() * 5 + 1 + 'px';
                particle.style.height = particle.style.width;
                particle.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
                particle.style.borderRadius = '50%';
                particle.style.pointerEvents = 'none';
                
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                
                particle.style.opacity = Math.random() * 0.5 + 0.1;
                const animationDuration = Math.random() * 20 + 10 + 's';
                const animationDelay = Math.random() * 5 + 's';
                
                particle.style.animation = `floatingParticle ${animationDuration} ease-in-out ${animationDelay} infinite alternate`;
                
                container.appendChild(particle);
            }
        }
        
        // Add hover effects to cards
        const cards = document.querySelectorAll('.bg-white.rounded-xl.shadow-md'); // Target the main cards
        cards.forEach(card => {
            card.classList.add('hover-scale'); 
            // Removed glow-on-hover to simplify, can be added back from admin if desired
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Animation utilities */
    @keyframes fade-in {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.7s ease-out forwards;
    }
    
    @keyframes floatingParticle {
        0% {
            transform: translate(0, 0) rotate(0deg);
        }
        50% {
            transform: translate(10px, -10px) rotate(180deg);
        }
        100% {
            transform: translate(0, 0) rotate(360deg);
        }
    }
    
    /* Card hover effects */
    .hover-scale {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    
    .hover-scale:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1), 0 6px 6px rgba(0,0,0,0.1);
    }
    
    /* Ensure alert container is styled if not already globally */
    .alert-container {
        /* Basic styling, can be enhanced */
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }
</style>
@endpush
