@extends('layouts.dashboard')

@section('title', 'Profil Saya')

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
    @endif    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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
                                <a href="{{ route('admin.profile.edit') }}" class="text-white text-sm">
                                    <i class="fas fa-camera me-1"></i> Ubah Foto
                                </a>
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold mb-2 text-center">{{ $user->name }}</h4>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white mb-3">
                            <i class="fas fa-user-shield mr-2"></i>
                            {{ $user->role->name }}
                        </span>
                        
                        <!-- Admin badge -->
                        <div class="mt-2 px-4 py-2 bg-white/15 backdrop-blur-sm rounded-lg text-white text-sm flex items-center">
                            <i class="fas fa-crown mr-2 text-yellow-300"></i>
                            <span>Super Administrator</span>
                        </div>
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
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 shadow-sm">
                                <i class="fas fa-sign-in-alt text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <h6 class="text-sm text-gray-500 mb-1">Login Terakhir</h6>
                                <p class="font-medium text-gray-800">{{ now()->subHours(rand(1, 5))->format('d/m/Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 text-center">
                        <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
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
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Nama Lengkap</div>
                                <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Email</div>
                                <div class="font-semibold text-gray-800">{{ $user->email }}</div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Username</div>
                                <div class="font-semibold text-gray-800">{{ $user->username ?? 'Tidak tersedia' }}</div>
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
                        <div class="flex flex-col space-y-6">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h6 class="font-medium text-gray-700 mb-1">Password</h6>
                                        <p class="text-sm text-gray-500">Diperbarui terakhir: {{ $user->updated_at->diffForHumans() }}</p>
                                    </div>
                                    <a href="{{ route('admin.profile.edit') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                        Update
                                    </a>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1">
                                    <div class="bg-green-500 h-1 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-5">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm text-gray-500 mb-1">Pembaruan Terakhir</h6>
                                        <p class="font-medium text-gray-800">{{ $user->updated_at->translatedFormat('d F Y, H:i') }} WIB</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <a href="{{ route('admin.profile.edit') }}#password" class="inline-flex items-center justify-center px-4 py-2 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200">
                                    <i class="fas fa-lock mr-2"></i>
                                    Ubah Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information -->                <div class="md:col-span-2 animate-fade-in" style="animation-delay: 0.5s">
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 hover:shadow-lg transition-all duration-300">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5">
                        <h5 class="font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-info-circle text-purple-500 mr-2"></i>
                            Informasi Sistem & Dashboard
                        </h5>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 hover:shadow-md transition-all">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-md">
                                        <i class="fas fa-school text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm text-gray-500 mb-1">Total Kelas</h6>
                                        <p class="font-bold text-gray-800 text-xl">{{ \App\Models\Classroom::count() }}</p>
                                        <p class="text-xs text-blue-600 mt-1">Kelas aktif</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-5 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100 hover:shadow-md transition-all">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white shadow-md">
                                        <i class="fas fa-user-graduate text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm text-gray-500 mb-1">Total Siswa</h6>
                                        <p class="font-bold text-gray-800 text-xl">{{ \App\Models\User::where('role_id', 3)->count() }}</p>
                                        <p class="text-xs text-purple-600 mt-1">Siswa terdaftar</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-5 bg-gradient-to-br from-green-50 to-teal-50 rounded-xl border border-green-100 hover:shadow-md transition-all">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white shadow-md">
                                        <i class="fas fa-chalkboard-teacher text-lg"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h6 class="text-sm text-gray-500 mb-1">Total Guru</h6>
                                        <p class="font-bold text-gray-800 text-xl">{{ \App\Models\User::where('role_id', 2)->count() }}</p>
                                        <p class="text-xs text-green-600 mt-1">Guru aktif</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- System Version Info -->
                        <div class="mt-6 p-5 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0 mr-3">
                                        <i class="fas fa-server"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-sm text-gray-500">Versi Sistem</h6>
                                        <p class="font-medium text-gray-800">E-Learning v2.5.0</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 mr-3">
                                        <i class="fas fa-laptop-code"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-sm text-gray-500">Alamat IP</h6>
                                        <p class="font-medium text-gray-800">{{ request()->ip() }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 flex-shrink-0 mr-3">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-sm text-gray-500">Tahun Ajaran</h6>
                                        <p class="font-medium text-gray-800">{{ date('Y') }}/{{ date('Y')+1 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <div class="text-sm text-gray-600 mb-4">
                                <p>E-Learning System SMAN 1 Girsip &copy; {{ date('Y') }}. Semua hak dilindungi.</p>
                            </div>
                            <div class="flex justify-center items-center space-x-3">
                                <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-all">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-all">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-all">
                                    <i class="fab fa-instagram"></i>
                                </a>
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
            for (let i = 0; i < 25; i++) {
                const particle = document.createElement('div');
                
                // Style the particle
                particle.style.position = 'absolute';
                particle.style.width = Math.random() * 5 + 1 + 'px';
                particle.style.height = particle.style.width;
                particle.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
                particle.style.borderRadius = '50%';
                particle.style.pointerEvents = 'none';
                
                // Position the particle randomly
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                
                // Set animation properties
                particle.style.opacity = Math.random() * 0.5 + 0.1;
                const animationDuration = Math.random() * 20 + 10 + 's';
                const animationDelay = Math.random() * 5 + 's';
                
                // Apply animation
                particle.style.animation = `floatingParticle ${animationDuration} ease-in-out ${animationDelay} infinite alternate`;
                
                // Add particle to container
                container.appendChild(particle);
            }
        }
        
        // Add hover effects to cards
        const cards = document.querySelectorAll('.bg-white.rounded-xl');
        cards.forEach(card => {
            card.classList.add('hover-scale', 'glow-on-hover');
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
        transition: transform 0.3s ease-in-out;
    }
    
    .hover-scale:hover {
        transform: scale(1.02);
    }
    
    /* Gradient text */
    .gradient-text {
        background: linear-gradient(to right, #6366f1, #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-fill-color: transparent;
    }
    
    /* Glow effect */
    .glow-on-hover:hover {
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.5);
    }
</style>
@endpush
