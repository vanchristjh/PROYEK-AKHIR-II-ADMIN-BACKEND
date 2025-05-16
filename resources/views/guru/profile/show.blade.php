@extends('layouts.guru')

@section('title', 'Profil Guru')

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
    @if(session('status') === 'password-updated')
        <div class="alert-container animate-fade-in" style="animation-delay: 0.1s">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Password berhasil diubah.</p>
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
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto Profil {{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-purple-400 to-indigo-500 flex items-center justify-center text-white font-bold text-4xl">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                                <a href="{{ route('guru.profile.edit') }}" class="text-white text-sm">
                                    <i class="fas fa-camera me-1"></i> Ubah Foto
                                </a>
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold mb-2 text-center">{{ $user->name }}</h4>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white mb-3">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            {{ $user->role->name }}
                        </span>
                        
                        @if(isset($user->teacher) && $user->teacher->nip)
                            <div class="mt-2 px-4 py-2 bg-white/15 backdrop-blur-sm rounded-lg text-white text-sm flex items-center">
                                <i class="fas fa-id-badge mr-2"></i>
                                <span>NIP: {{ $user->teacher->nip }}</span>
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
                                <i class="fas fa-user-tag text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">Username</h6>
                                <p class="font-medium text-gray-800">{{ $user->username ?? 'Tidak tersedia' }}</p>
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
                        
                        @if(isset($user->teacher) && $user->teacher->phone)
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 flex-shrink-0 shadow-sm">
                                <i class="fas fa-phone text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">No. Telepon</h6>
                                <p class="font-medium text-gray-800">{{ $user->teacher->phone }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-8 text-center">
                        <a href="{{ route('guru.profile.edit') }}" class="inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-user-edit mr-2"></i>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Information and Activities -->
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Account Details -->
            <div class="animate-fade-in" style="animation-delay: 0.3s">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 hover:shadow-md transition-all duration-300 h-full">
                    <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                        <h5 class="font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-id-card text-indigo-500 mr-2"></i>
                            Detail Akun
                        </h5>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div class="text-sm text-gray-500 mb-1">Nama Lengkap</div>
                                <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div class="text-sm text-gray-500 mb-1">Email</div>
                                <div class="font-semibold text-gray-800">{{ $user->email }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div class="text-sm text-gray-500 mb-1">Username</div>
                                <div class="font-semibold text-gray-800">{{ $user->username ?? 'Tidak tersedia' }}</div>
                            </div>
                             @if(isset($user->teacher) && $user->teacher->nip)
                            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div class="text-sm text-gray-500 mb-1">NIP</div>
                                <div class="font-semibold text-gray-800">{{ $user->teacher->nip }}</div>
                            </div>
                            @endif
                            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
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
            
            <!-- Security -->
            <div class="animate-fade-in" style="animation-delay: 0.4s">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 hover:shadow-md transition-all duration-300 h-full">
                    <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                        <h5 class="font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-shield-alt text-indigo-500 mr-2"></i>
                            Keamanan
                        </h5>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('guru.profile.update-password') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                                <input type="password" name="current_password" id="current_password" class="w-full rounded-lg shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <input type="password" name="password" id="password" class="w-full rounded-lg shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-lg shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                            
                            <div class="text-right">
                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-lock mr-2"></i>
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                        <div class="border-t border-gray-100 pt-5 mt-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm text-gray-500 mb-1">Pembaruan Profil Terakhir</h6>
                                        <p class="font-medium text-gray-800">{{ $user->updated_at->translatedFormat('d F Y, H:i') }} WIB</p>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            
            <!-- Teaching Information -->
            <div class="md:col-span-2 animate-fade-in" style="animation-delay: 0.5s">
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 hover:shadow-lg transition-all duration-300">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5">
                        <h5 class="font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-chalkboard-teacher text-purple-500 mr-2"></i>
                            Informasi Mengajar
                        </h5>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                            <div class="p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 hover:shadow-md transition-all">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-md">
                                        <i class="fas fa-book text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm text-gray-500 mb-1">Mata Pelajaran</h6>
                                        <p class="font-bold text-gray-800 text-xl">{{ $user->teacherSubjects()->count() }}</p>
                                        <a href="{{ route('guru.materials.index') }}" class="text-xs text-blue-600 mt-1 hover:underline">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-5 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100 hover:shadow-md transition-all">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white shadow-md">
                                        <i class="fas fa-users text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm text-gray-500 mb-1">Kelas Diampu</h6>
                                        <p class="font-bold text-gray-800 text-xl">{{ $user->teachingClassrooms()->count() }}</p>
                                         <a href="{{ route('guru.schedule.index') }}" class="text-xs text-purple-600 mt-1 hover:underline">Lihat Jadwal</a>
                                    </div>
                                </div>
                            </div>
                             <div class="p-5 bg-gradient-to-br from-green-50 to-teal-50 rounded-xl border border-green-100 hover:shadow-md transition-all">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white shadow-md">
                                        <i class="fas fa-book-open text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm text-gray-500 mb-1">Total Materi</h6>
                                        <p class="font-bold text-gray-800 text-xl">{{ $user->materials()->count() }}</p>
                                        <a href="{{ route('guru.materials.index') }}" class="text-xs text-green-600 mt-1 hover:underline">Kelola Materi</a>
                                    </div>
                                </div>
                            </div>
                             <div class="p-5 bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl border border-yellow-100 hover:shadow-md transition-all">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center text-white shadow-md">
                                        <i class="fas fa-tasks text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm text-gray-500 mb-1">Total Tugas</h6>
                                        <p class="font-bold text-gray-800 text-xl">{{ $user->assignments()->count() }}</p>
                                        <a href="{{ route('guru.assignments.index') }}" class="text-xs text-yellow-600 mt-1 hover:underline">Kelola Tugas</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 text-center">
                             <a href="{{ route('guru.settings.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200">
                                <i class="fas fa-cog mr-2"></i>
                                Pengaturan Akun & Notifikasi
                            </a>
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
        const cards = document.querySelectorAll('.bg-white.rounded-xl'); // Corrected selector
        cards.forEach(card => {
            card.classList.add('hover-scale', 'glow-on-hover'); // Added glow-on-hover
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
        transition: transform 0.3s ease-in-out; /* Align with admin: only for scale */
    }
    
    .hover-scale:hover {
        transform: scale(1.02);
    }

    /* Glow effect from admin */
    .glow-on-hover:hover {
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.5); /* Indigo glow, same as admin */
    }
    
    .alert-container {
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }
</style>
@endpush
