@extends('layouts.dashboard')

@section('title', 'Profil Siswa')

@section('header', 'Profil Saya')

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="animate-fade-in mb-6">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm">
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
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6 relative overflow-hidden">
                    <div class="flex flex-col items-center justify-center relative z-10">
                        <div class="relative mb-4">
                            <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-white/70 shadow-lg">
                                @if(isset($user->avatar) && $user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto Profil {{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-3xl">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <h4 class="text-xl font-bold mb-1 text-center">{{ $user->name }}</h4>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm text-white">
                            {{ $user->role->name }}
                        </span>
                        
                        @if($user->classroom)
                            <div class="mt-3 flex items-center bg-white/15 backdrop-blur-sm px-4 py-2 rounded-lg">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                <span>{{ $user->classroom->name }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-white/10 rounded-full"></div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">Alamat Email</h6>
                                <p class="font-medium text-gray-800">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">NIS</h6>
                                <p class="font-medium text-gray-800">{{ $user->nisn ?? 'Belum tersedia' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">Bergabung Sejak</h6>
                                <p class="font-medium text-gray-800">{{ $user->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Academic Info Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-all duration-300 h-full">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center mb-2">
                        <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                        Informasi Akademik
                    </h3>
                    <p class="text-gray-500 text-sm">Detail informasi akademik dan pembelajaran Anda</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kelas Card -->
                        <div class="bg-gray-50 rounded-lg p-5">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-users text-blue-500 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-gray-700 font-medium">Kelas</h4>
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
                                    <h4 class="text-gray-700 font-medium">Kehadiran</h4>                                    <p class="text-green-600 text-lg font-semibold">
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
                                    <i class="fas fa-book text-purple-500 text-xl"></i>
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

<style>
    .animate-fade-in {
        animation: fadeIn 0.7s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Card hover effects */
    .bg-white.rounded-xl {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
    }
    
    .bg-white.rounded-xl:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Gradient text */
    .gradient-text {
        background: linear-gradient(to right, #3b82f6, #6366f1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-fill-color: transparent;
    }
      /* Decorative elements animations */
    @keyframes float {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
        100% {
            transform: translateY(0px);
        }
    }
    
    .float-animation {
        animation: float 5s ease-in-out infinite;
    }
</style>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Add decorative floating elements
        const decorativeElements = document.querySelectorAll('.absolute');
        decorativeElements.forEach(el => {
            el.classList.add('float-animation');
        });
        
        // Add shimmer effect on hover for cards
        const cards = document.querySelectorAll('.bg-white.rounded-xl');
        cards.forEach(card => {
            card.addEventListener('mouseover', function() {
                this.style.background = 'linear-gradient(45deg, #ffffff, #f9fafb, #ffffff)';
                this.style.backgroundSize = '200% 200%';
                this.style.animation = 'shimmer 1.5s ease infinite';
            });
            
            card.addEventListener('mouseout', function() {
                this.style.background = '';
                this.style.animation = '';
            });
        });
        
        // Add animation to info boxes
        const infoBoxes = document.querySelectorAll('.bg-gray-50.rounded-lg');
        infoBoxes.forEach((box, index) => {
            box.style.opacity = "0";
            box.style.transform = "translateY(20px)";
            box.style.transition = "all 0.5s ease-out";
            
            setTimeout(() => {
                box.style.opacity = "1";
                box.style.transform = "translateY(0)";
            }, 300 + (index * 100));
        });
    });
</script>
@endpush

<style>
@keyframes shimmer {
    0% {
        background-position: 0% 0%;
    }
    50% {
        background-position: 100% 100%;
    }
    100% {
        background-position: 0% 0%;
    }
}
</style>
@endsection
