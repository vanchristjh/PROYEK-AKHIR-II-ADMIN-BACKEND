@extends('layouts.guru')

@section('title', 'Profil Guru')

@section('content')
<div class="container px-6 py-8 mx-auto">
    <!-- Header Section -->
    <div class="mb-6 animate-fade-in" style="animation-delay: 0.1s">
        <h2 class="text-3xl font-bold text-gray-800 gradient-text">
            Profil Guru
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Informasi detail akun dan profil Anda sebagai pengajar
        </p>
    </div>

    @if (session('success'))
        <div class="animate-fade-in mb-6" style="animation-delay: 0.2s">
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

    <!-- Main Content Grid -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Profile Card -->
        <div class="lg:col-span-1 animate-fade-in" style="animation-delay: 0.3s">
            <div class="bg-white rounded-xl shadow-md border border-gray-100/50 overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-8 relative overflow-hidden">
                    <div class="relative flex flex-col items-center justify-center z-10">
                        <div class="w-32 h-32 mb-5">
                            <div class="relative w-full h-full rounded-full border-4 border-white/70 shadow-lg overflow-hidden group">
                                @if($user->avatar)
                                    <img class="w-full h-full object-cover" 
                                        src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                                    <a href="{{ route('guru.profile.edit') }}" class="text-white text-sm">
                                        <i class="fas fa-camera me-1"></i> Ubah Foto
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm text-white mb-3 pulse-animation">
                            {{ $user->role->name ?? 'Guru' }}
                        </span>
                        
                        <!-- NIP Badge if available -->
                        @if(isset($user->nip) && $user->nip)
                            <div class="px-4 py-2 bg-white/15 backdrop-blur-sm rounded-lg text-white text-sm flex items-center">
                                <i class="fas fa-id-badge mr-2"></i>
                                <span>NIP: {{ $user->nip }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 bg-white/10 rounded-full float-animation"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-white/10 rounded-full float-animation"></div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-5">
                        <div class="flex items-center hover-translate transform transition-transform duration-300 hover:-translate-y-1">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">Email</h6>
                                <p class="font-medium text-gray-800">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center hover-translate transform transition-transform duration-300 hover:-translate-y-1">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">Username</h6>
                                <p class="font-medium text-gray-800">{{ $user->username }}</p>
                            </div>
                        </div>

                        <div class="flex items-center hover-translate transform transition-transform duration-300 hover:-translate-y-1">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">Bergabung Sejak</h6>
                                <p class="font-medium text-gray-800">{{ $user->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('guru.profile.edit') }}" class="block w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md text-center transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-user-edit mr-2"></i>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Teaching Information -->
        <div class="lg:col-span-2 animate-fade-in" style="animation-delay: 0.4s">
            <div class="bg-white rounded-xl shadow-md border border-gray-100/50 overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gradient-text">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Informasi Mengajar
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Detail mata pelajaran dan kelas yang Anda ajar
                    </p>
                </div>
                
                <div class="p-6">
                    <!-- Subjects Section -->
                    <div class="mb-8">
                        <h4 class="text-base font-medium text-gray-700 mb-4 flex items-center gradient-text">
                            <i class="fas fa-book text-blue-500 mr-2"></i>
                            Mata Pelajaran yang Diampu
                        </h4>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            @forelse($user->teacherSubjects()->get() as $subject)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200 hover:shadow-sm hover:-translate-y-1 transform transition-all duration-200">
                                    <i class="fas fa-book-open mr-2 text-blue-600"></i>
                                    {{ $subject->name }}
                                </span>
                            @empty
                                <div class="p-4 bg-gray-50 rounded-lg w-full text-center">
                                    <p class="text-gray-500">Belum ada mata pelajaran yang diampu</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Classes Section -->
                    <div>
                        <h4 class="text-base font-medium text-gray-700 mb-4 flex items-center gradient-text">
                            <i class="fas fa-users text-blue-500 mr-2"></i>
                            Kelas yang Diampu
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($user->teachingClassrooms()->get() as $classroom)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1 transform">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="font-medium text-gray-800">{{ $classroom->name }}</h5>
                                            <p class="text-sm text-gray-500">{{ $classroom->students_count ?? 0 }} Siswa</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 p-4 bg-gray-50 rounded-lg text-center">
                                    <p class="text-gray-500">Belum ada kelas yang diampu</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="grid gap-4 mt-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 animate-fade-in" style="animation-delay: 0.5s">
                <!-- Mata Pelajaran Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100/50 overflow-hidden hover:shadow-lg p-5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                            <i class="fas fa-book text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Mata Pelajaran</h5>
                            <p class="text-2xl font-bold text-green-600">{{ $user->teacherSubjects()->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Kelas Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100/50 overflow-hidden hover:shadow-lg p-5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Kelas</h5>
                            <p class="text-2xl font-bold text-blue-600">{{ $user->teachingClassrooms()->count() }}</p>
                        </div>
                    </div>
                </div>
                    <p class="text-sm text-gray-500">Kelas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $user->teachingClassrooms()->count() }}</p>                <!-- Materi Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100/50 overflow-hidden hover:shadow-lg p-5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                            <i class="fas fa-book-open text-amber-600 text-xl"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Materi</h5>
                            <p class="text-2xl font-bold text-amber-600">{{ $user->materials()->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tugas Card -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100/50 overflow-hidden hover:shadow-lg p-5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                            <i class="fas fa-tasks text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Tugas</h5>
                            <p class="text-2xl font-bold text-purple-600">{{ $user->assignments()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Teaching Highlights -->
            <div class="mt-6 animate-fade-in" style="animation-delay: 0.6s">
                <div class="bg-white rounded-xl shadow-md border border-gray-100/50 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold gradient-text flex items-center">
                            <i class="fas fa-chart-line mr-2"></i>
                            Pencapaian Mengajar
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas pengajaran Anda</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Recent Activities -->
                            <div class="bg-gray-50 rounded-lg p-5">
                                <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-history text-blue-500 mr-2"></i>
                                    Aktivitas Terbaru
                                </h4>
                                
                                <div class="space-y-4">
                                    @if($user->materials()->count() > 0 || $user->assignments()->count() > 0)
                                        <!-- Potentially show recent materials and assignments here -->
                                        <div class="flex items-center text-sm">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 flex-shrink-0 mr-3">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div>
                                                <p class="text-gray-800">{{ $user->materials()->count() }} materi pembelajaran telah dibuat</p>
                                                <p class="text-gray-500 text-xs mt-1">Untuk {{ $user->teacherSubjects()->count() }} mata pelajaran</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center text-sm">
                                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 flex-shrink-0 mr-3">
                                                <i class="fas fa-clipboard-check"></i>
                                            </div>
                                            <div>
                                                <p class="text-gray-800">{{ $user->assignments()->count() }} tugas telah diberikan</p>
                                                <p class="text-gray-500 text-xs mt-1">Untuk {{ $user->teachingClassrooms()->count() }} kelas</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Change Password Section -->
    <div class="mt-8 animate-fade-in" style="animation-delay: 0.7s">
        <div class="bg-white rounded-xl shadow-md border border-gray-100/50 overflow-hidden mb-6 transition-all duration-300 hover:shadow-lg">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text flex items-center">
                    <i class="fas fa-lock mr-2"></i>
                    Keamanan Akun
                </h3>
                <p class="text-sm text-gray-500 mt-1">Kelola keamanan akun Anda</p>
            </div>
            
            <div class="p-6">
                @if(session('status'))
                    <div class="mb-6 p-4 rounded-lg {{ session('status') === 'password-updated' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} animate-fade-in">
                        @if(session('status') === 'password-updated')
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Password berhasil diubah.
                            </div>
                        @else
                            {{ session('status') }}
                        @endif
                    </div>
                @endif
                
                <form action="{{ route('guru.profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="current_password" 
                                       class="w-full rounded-lg shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                            </div>
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" 
                                       class="w-full rounded-lg shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="w-full rounded-lg shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-check text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Ubah Password
                        </button>
                    </div>
                </form>
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
    
    /* Glow effect */
    .hover-glow:hover {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
    }
    
    /* Badge animations */
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }
        50% {
            transform: scale(1.05);
            opacity: 1;
        }
        100% {
            transform: scale(1);
            opacity: 0.8;
        }
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    /* Gradient text */
    .gradient-text {
        background: linear-gradient(to right, #3b82f6, #4f46e5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-fill-color: transparent;
    }
    
    /* Hover translate */
    .hover-translate {
        transition: transform 0.3s ease;
    }
    
    .hover-translate:hover {
        transform: translateY(-3px);
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
    
    /* Shimmer effect */
    @keyframes shimmer {
        0% { background-position: 0% 0%; }
        100% { background-position: 100% 100%; }
    }
</style>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
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
        
        // Add smooth animations on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, { threshold: 0.1 });
        
        const elements = document.querySelectorAll('.animate-fade-in');
        elements.forEach(el => {
            observer.observe(el);
        });
        
        // Add decorative floating elements animation
        const decorativeElements = document.querySelectorAll('.float-animation');
        decorativeElements.forEach(el => {
            el.style.transformOrigin = 'center';
            el.style.animation = 'float 5s ease-in-out infinite';
        });
    });
</script>
@endpush
@endsection
