@extends('layouts.guru')

@section('title', 'Profil Guru')
@section('header', 'Profil Saya')

@push('styles')
<style>
    html {
        scroll-behavior: smooth; /* Enable smooth scrolling */
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out forwards;
        opacity: 0;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Improved box shadow for cards */
    .bg-white {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    
    .gradient-text {
        background: linear-gradient(90deg, #3b82f6, #4f46e5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    /* Accessibility focus styles */
    a:focus, button:focus, [role="button"]:focus, input:focus, select:focus, textarea:focus {
        outline: 2px solid rgba(59, 130, 246, 0.5);
        outline-offset: 2px;
    }
    
    /* Keyboard navigation indicator */
    .keyboard-focus {
        position: relative;
    }
    
    .keyboard-focus:focus-visible::after {
        content: '';
        position: absolute;
        inset: -3px;
        border-radius: inherit;
        border: 2px solid rgba(59, 130, 246, 0.6);
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.8);
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .float-animation {
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255,255,255, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(255,255,255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255,255,255, 0); }
    }
    
    /* Online status pulse animation */
    .pulse-online-status {
        animation: onlinePulse 2s infinite;
    }
    
    @keyframes onlinePulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 7px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    
    .hover-translate {
        transition: transform 0.3s ease;
    }
    
    .hover-translate:hover {
        transform: translateY(-5px);
    }
    
    .stat-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(229, 231, 235, 0.5);
    }
      .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-color: rgba(79, 70, 229, 0.2);
    }
    
    /* Card border animation */
    .border-pulse {
        position: relative;
    }
    
    .border-pulse::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(90deg, rgba(99, 102, 241, 0), rgba(99, 102, 241, 0.5), rgba(99, 102, 241, 0));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        animation: border-pulse 2s linear infinite;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .border-pulse:hover::after {
        opacity: 1;
    }
    
    @keyframes border-pulse {
        0% { background-position: 0% 0% }
        100% { background-position: 200% 0% }
    }      /* Enhanced mobile styling - improved for better responsiveness */
    @media (max-width: 768px) {
        .mobile-optimized {
            padding: 1rem !important;
        }
        
        .grid-cols-2 {
            grid-template-columns: 1fr !important;
        }
        
        .w-36 {
            width: 6rem !important;
        }
        
        .h-36 {
            height: 6rem !important;
        }
        
        .md\:grid-cols-3 {
            grid-template-columns: 1fr !important;
        }
        
        .md\:grid-cols-4 {
            grid-template-columns: 1fr 1fr !important;
        }
        
        .md\:grid-cols-2 {
            grid-template-columns: 1fr !important;
        }
        
        .ml-13 {
            margin-left: 0 !important;
            margin-top: 0.25rem !important;
        }
        
        .container {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        /* Improved profile image and header for mobile */
        .profile-header {
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
        }
        
        /* Better font sizes and spacing for mobile */
        .text-3xl {
            font-size: 1.5rem !important;
            line-height: 2rem !important;
        }
        
        .text-lg {
            font-size: 1.125rem !important;
        }
        
        .p-6 {
            padding: 1.25rem !important;
        }
        
        /* Improved stacking for statistics cards */
        .stat-card {
            margin-bottom: 0.75rem;
        }
        
        /* Fix spacing in activity items */
        .flex.items-center.p-3 {
            padding: 0.75rem !important;
        }
        
        .w-12.h-12 {
            width: 2.5rem !important;
            height: 2.5rem !important;
        }
        
        /* Optimize form layout */
        form .grid-cols-3 {
            grid-template-columns: 1fr !important;
        }
        
        /* Better animations for mobile performance */
        .animate-fade-in {
            animation-duration: 0.3s !important;
        }
        
        /* Improve scroll performance */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
    
    /* Tablet optimization */
    @media (min-width: 769px) and (max-width: 1024px) {
        .lg\:col-span-4, .lg\:col-span-8 {
            grid-column: span 12 / span 12 !important;
        }
        
        .grid-cols-3 {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
        
        /* Improved spacing for tablets */
        .container {
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }
        
        .p-6 {
            padding: 1.5rem !important;
        }
        
        /* Scale down some decorative elements */
        .float-animation {
            transform: scale(0.8);
        }
        
        /* Better layout for tablet orientation */
        .stat-cards-container {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 1rem !important;
        }
        
        /* Optimize profile card on tablets */
        .profile-image-wrapper {
            width: 7rem !important;
            height: 7rem !important;
        }
        
        /* Better form layout for tablets */
        form .grid.grid-cols-3 {
            grid-template-columns: 1fr 1fr !important;
        }
        
        /* Cleaner spacing for activity items */
        .activity-item {
            padding: 0.75rem !important;
        }
    }
    
    /* Small screen landscape optimization */
    @media (max-height: 600px) and (orientation: landscape) {
        .w-36 {
            width: 5rem !important;
            height: 5rem !important;
        }
        
        .h-36 {
            width: 5rem !important;
            height: 5rem !important;
        }
        
        .py-8 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }
        
        .mb-6 {
            margin-bottom: 1rem !important;
        }
    }
    
    /* Enhanced input styling */
    input[type="password"], input[type="text"] {
        transition: all 0.2s ease;
    }
    
    input[type="password"]:focus, input[type="text"]:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.1);
    }
    
    /* Button animations */
    button[type="submit"] {
        position: relative;
        overflow: hidden;
    }
    
    button[type="submit"]::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }
    
    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        100% {
            transform: scale(20, 20);
            opacity: 0;
        }
    }
    
    /* Toast notification system */
    #toast-container {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        max-width: 24rem;
        width: calc(100% - 2rem);
        pointer-events: none;
    }
    
    .toast {
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        transform: translateY(-100%);
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: auto;
        max-width: 100%;
    }
    
    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    .toast-success {
        background-color: #def7ec;
        border-left: 4px solid #0e9f6e;
        color: #03543e;
    }
    
    .toast-error {
        background-color: #fde8e8;
        border-left: 4px solid #f98080;
        color: #9b1c1c;
    }
    
    .toast-info {
        background-color: #e1effe;
        border-left: 4px solid #3f83f8;
        color: #1e429f;
    }
    
    .toast-icon {
        margin-right: 0.75rem;
        flex-shrink: 0;
    }
    
    .toast-content {
        flex: 1;
    }
    
    .toast-close {
        background: none;
        border: none;
        cursor: pointer;
        color: currentColor;
        opacity: 0.7;
        padding: 0.25rem;
        border-radius: 9999px;
        margin-left: 0.5rem;
    }
    
    .toast-close:hover {
        opacity: 1;
        background-color: rgba(0, 0, 0, 0.05);
    }
    
      button[type="submit"]:focus:not(:active)::after {
        animation: ripple 1s ease-out;
    }
    
    /* Button effect */
    .button-effect {
        background: rgba(255, 255, 255, 0.3);
        opacity: 0;
    }
    
    button:hover .button-effect {
        opacity: 1;
    }
    
    /* Loading indicator for form submission */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading::after {
        content: '';
        position: absolute;
        width: 1rem;
        height: 1rem;
        top: 50%;
        right: 0.5rem;
        margin-top: -0.5rem;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 0.7s linear infinite;
        transition: all 0.3s ease;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Accessibility improvements */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }
    
    :focus {
        outline: 2px solid rgba(79, 70, 229, 0.5);
        outline-offset: 2px;
    }
    
    /* Loading optimization classes */
    .lazy-load {
        opacity: 0;
        transition: opacity 0.5s;
    }
    
    .lazy-load.loaded {
        opacity: 1;
    }
    
    /* Online status pulse animation */
    .pulse-online-status {
        animation: pulse 2s infinite;
    }
</style>
@endpush

@section('content')
<!-- Skip to main content link for accessibility -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:p-4 bg-blue-700 text-white z-50">
    Lewati ke konten utama
</a>

<div class="container px-6 py-8 mx-auto">
    <main id="main-content" aria-labelledby="profile-heading">
    <!-- Header Section with Actions -->    
    <div class="mb-6 animate-fade-in flex flex-col md:flex-row md:items-center md:justify-between bg-gradient-to-r from-white to-gray-50 p-6 rounded-xl shadow-md border border-gray-100" style="animation-delay: 0.1s">
        <div class="flex items-center">
            <div class="w-14 h-14 rounded-lg bg-gradient-to-br from-indigo-600 to-blue-600 items-center justify-center mr-4 shadow-md text-white hidden md:flex">
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-800 gradient-text">
                    Profil Guru
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Informasi detail akun dan profil Anda sebagai pengajar
                </p>
            </div>
        </div>
        <div class="mt-4 md:mt-0 space-x-2 flex">
            <a href="{{ route('guru.profile.edit') }}" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 rounded-md text-white text-sm font-medium inline-flex items-center transition-colors shadow-sm hover:shadow-md">
                <i class="fas fa-pencil-alt mr-2"></i>
                Edit Profil
            </a>
            <a href="{{ route('guru.settings.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700 text-sm font-medium inline-flex items-center transition-colors shadow-sm hover:shadow-md">
                <i class="fas fa-cog mr-2"></i>
                Pengaturan
            </a>
        </div>
    </div>

    @if (session('success') || session('status'))
        <div class="animate-fade-in mb-6" style="animation-delay: 0.2s">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            {{ session('success') ?? session('status') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Profile Status Summary -->
    <div class="animate-fade-in mb-6" style="animation-delay: 0.25s">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl shadow-sm border border-blue-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white">
                        <i class="fas fa-info"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-medium text-blue-900">Status Profil</h3>
                        <p class="text-sm text-blue-700">
                            @if(isset($user->teacher->profile_completed) && $user->teacher->profile_completed)
                                Profil Anda sudah lengkap
                            @else
                                Lengkapi profil Anda untuk pengalaman yang lebih baik
                            @endif
                        </p>
                    </div>
                </div>
                <div>                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ isset($user->teacher->profile_completed) && $user->teacher->profile_completed ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                        @if(isset($user->teacher->profile_completed) && $user->teacher->profile_completed)
                            <i class="fas fa-check-circle mr-1"></i> Lengkap
                        @else
                            <i class="fas fa-exclamation-circle mr-1"></i> Perlu Dilengkapi
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column - About Me -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Profile Card - Left Column -->
            <div class="lg:col-span-4 animate-fade-in" style="animation-delay: 0.3s">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <!-- Profile Header -->
                    <div class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white p-8 relative overflow-hidden">
                        <!-- Profile Photo -->
                        <div class="relative flex flex-col items-center justify-center z-10">
                            <div class="w-36 h-36 mb-5 relative">
                                <div class="relative w-full h-full rounded-full border-4 border-white shadow-md overflow-hidden group">
                                    @if($user->avatar)
                                        <img class="w-full h-full object-cover lazy-load" loading="lazy"
                                            src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-4xl font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                                        <a href="{{ route('guru.profile.edit') }}" class="bg-white/30 backdrop-blur-sm text-white text-sm py-1 px-3 rounded-full hover:bg-white/50 transition-colors">
                                            <i class="fas fa-camera mr-1"></i> Ubah Foto
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Online Status Indicator -->
                                <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-2 border-white rounded-full pulse-online-status" aria-label="Status: Online"></div>
                            </div>
                            
                            <!-- Name and Role -->
                            <h3 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h3>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm text-white mb-3 pulse-animation">
                                <i class="fas fa-user-tie mr-1"></i>
                                {{ $user->role->name ?? 'Guru' }}
                            </div>
                            
                            <!-- NIP Badge if available -->
                            @if(isset($user->teacher) && $user->teacher && isset($user->teacher->nip))
                                <div class="px-4 py-2 bg-white/15 backdrop-blur-sm rounded-lg text-white text-sm flex items-center">
                                    <i class="fas fa-id-badge mr-2"></i>
                                    <span>NIP: {{ $user->teacher->nip }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Decorative elements -->
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full float-animation" style="animation-delay: 0s;"></div>
                        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-white/10 rounded-full float-animation" style="animation-delay: 1s;"></div>
                        <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-white/5 rounded-full float-animation" style="animation-delay: 2s;"></div>
                    </div>
                    
                    <!-- Profile Info -->
                    <div class="p-6">
                        <div class="space-y-5">
                            <div class="flex items-center hover-translate">
                                <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="ml-4">
                                    <h6 class="text-sm text-gray-500 mb-1">Email</h6>
                                    <p class="font-medium text-gray-800">{{ $user->email }}</p>
                                </div>
                            </div>
                              <div class="flex items-center hover-translate">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center text-blue-600 flex-shrink-0 shadow-sm">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <h6 class="text-sm text-gray-500 mb-1">Username</h6>
                                    <p class="font-medium text-gray-800">{{ $user->username }}</p>
                                </div>
                            </div>

                            <div class="flex items-center hover-translate">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-100 to-green-200 flex items-center justify-center text-green-600 flex-shrink-0 shadow-sm">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="ml-4">
                                    <h6 class="text-sm text-gray-500 mb-1">Bergabung Sejak</h6>
                                    <p class="font-medium text-gray-800">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            
                            @if(isset($user->teacher) && $user->teacher && isset($user->teacher->phone))
                            <div class="flex items-center hover-translate">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-yellow-100 to-amber-200 flex items-center justify-center text-yellow-600 flex-shrink-0 shadow-sm">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="ml-4">
                                    <h6 class="text-sm text-gray-500 mb-1">No. Telepon</h6>
                                    <p class="font-medium text-gray-800">{{ $user->teacher->phone }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="mt-8 space-y-3">
                            <a href="{{ route('guru.profile.edit') }}" class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white font-medium rounded-lg shadow-sm hover:shadow-md text-center transition-all duration-200 inline-flex items-center justify-center space-x-2">
                                <i class="fas fa-user-edit"></i>
                                <span>Edit Profil</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="lg:col-span-8 space-y-8">            <!-- Teaching Information -->
                <div class="animate-fade-in" style="animation-delay: 0.4s">
                    <div class="bg-gradient-to-r from-slate-50 to-gray-50 rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50">
                            <h3 class="text-lg font-semibold gradient-text flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-sm mr-3">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                Informasi Mengajar
                            </h3>
                            <p class="text-sm text-gray-500 mt-1 ml-13">
                                Detail mata pelajaran dan kelas yang Anda ajar
                            </p>
                        </div>
                        
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Subjects Section -->
                            <div>                            <h4 class="text-base font-medium text-gray-700 mb-4 flex items-center">
                                    <div class="w-8 h-8 rounded-md bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-sm mr-3">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    Mata Pelajaran yang Diampu
                                </h4>
                                
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if(isset($user->teacher) && method_exists($user->teacher, 'subjects'))
                                        @forelse($user->teacher->subjects()->get() as $subject)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-800 border border-blue-200 hover:shadow-md transition-all duration-200">
                                                <i class="fas fa-book-open mr-2 text-blue-600"></i>
                                                {{ $subject->name }}
                                            </span>
                                        @empty
                                            <div class="p-4 bg-gray-50 rounded-lg w-full text-center border border-gray-100">
                                                <p class="text-gray-500">Belum ada mata pelajaran yang diampu</p>
                                            </div>
                                        @endforelse
                                    @else
                                        <div class="p-4 bg-gray-50 rounded-lg w-full text-center border border-gray-100">
                                            <p class="text-gray-500">Belum ada mata pelajaran yang diampu</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                              <!-- Classes Section -->
                            <div>
                                <h4 class="text-base font-medium text-gray-700 mb-4 flex items-center">
                                    <div class="w-8 h-8 rounded-md bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center text-white shadow-sm mr-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    Kelas yang Diampu
                                </h4>
                                
                                <div>
                                    @if(isset($user->teacher) && method_exists($user->teacher, 'classrooms'))
                                        @forelse($user->teacher->classrooms()->get() as $classroom)                                        <div class="bg-gradient-to-r from-green-50 to-teal-50 mb-2 rounded-lg p-3 border border-green-100 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 transform">
                                                <div class="flex items-center">                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-sm mr-3 shadow-sm" aria-hidden="true">
                                        <i class="fas fa-users"></i>
                                    </div>
                                                    <div class="flex-grow">
                                                        <h5 class="font-medium text-gray-800">{{ $classroom->name }}</h5>
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-sm text-gray-500">{{ $classroom->students_count ?? '0' }} Siswa</p>
                                                            <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full">Aktif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-4 bg-gray-50 rounded-lg text-center border border-gray-100">
                                                <p class="text-gray-500">Belum ada kelas yang diampu</p>
                                            </div>
                                        @endforelse
                                    @else
                                        <div class="p-4 bg-gray-50 rounded-lg text-center border border-gray-100">
                                            <p class="text-gray-500">Belum ada kelas yang diampu</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Teaching Schedule Preview -->
                <div class="animate-fade-in" style="animation-delay: 0.5s">
                    <div class="bg-gradient-to-r from-teal-50 to-green-50 rounded-xl shadow-md border border-teal-100 overflow-hidden hover:shadow-lg transition-all duration-300 border-pulse">
                        <div class="p-6 border-b border-teal-100">
                            <h3 class="text-lg font-semibold gradient-text flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-teal-600 to-green-600 flex items-center justify-center text-white shadow-sm mr-3 pulse-animation">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                Jadwal Mengajar Hari Ini
                            </h3>
                            <p class="text-sm text-gray-500 mt-1 ml-13">
                                {{ now()->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                        
                        <div class="p-6">                            @if(isset($todaySchedules) && $todaySchedules->count() > 0)
                                <div class="space-y-4">
                                    @foreach($todaySchedules as $schedule)
                                        <div class="flex items-center p-3 bg-white rounded-lg shadow-sm border border-teal-100 hover:shadow-md transition-all duration-200">
                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-teal-100 to-green-100 flex items-center justify-center text-teal-600 mr-3">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="flex-grow">
                                                <div class="flex items-center justify-between">
                                                    <h5 class="text-sm font-medium text-gray-800">
                                                        {{ $schedule->subject->name ?? 'Mata Pelajaran' }}
                                                    </h5>
                                                    <span class="text-xs px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full">
                                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Kelas {{ $schedule->classroom->name ?? 'Belum ditetapkan' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-white rounded-lg p-6 text-center border border-teal-100">
                                    <div class="w-16 h-16 rounded-full bg-teal-100 flex items-center justify-center text-teal-500 mx-auto mb-3">
                                        <i class="fas fa-calendar-day text-xl"></i>
                                    </div>
                                    <h5 class="text-gray-700 font-medium mb-1">Tidak ada jadwal mengajar hari ini</h5>
                                    <p class="text-gray-500 text-sm">Nikmati waktu Anda untuk persiapan materi</p>
                                </div>
                            @endif
                                  <div class="mt-4 flex justify-center">
                            <a href="{{ route('guru.schedule.index') }}" class="inline-flex items-center px-4 py-2 text-sm text-teal-700 hover:text-teal-800 transition-colors">
                                <i class="fas fa-calendar mr-1"></i>
                                Lihat Jadwal Lengkap
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="p-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-address-card mr-2 text-indigo-500"></i> Informasi Kontak
                    </h4>
                    <div class="space-y-3">
                        @if(isset($user->email))
                        <div class="flex items-center text-sm">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 mr-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-sm text-gray-700">{{ $user->email }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if(isset($user->teacher) && isset($user->teacher->phone))
                        <div class="flex items-center text-sm">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-500 mr-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Telepon</p>
                                <p class="text-sm text-gray-700">{{ $user->teacher->phone ?? 'Belum diisi' }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if(isset($user->teacher) && isset($user->teacher->address))
                        <div class="flex items-center text-sm">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Alamat</p>
                                <p class="text-sm text-gray-700">{{ $user->teacher->address ?? 'Belum diisi' }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Education Background -->
                <div class="p-6 border-t border-gray-100">
                    <h4 class="text-sm font-medium text-gray-700 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-graduation-cap mr-2 text-indigo-500"></i> Latar Belakang Pendidikan
                    </h4>
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-500 mr-3">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Pendidikan Terakhir</p>
                                <p class="text-sm text-gray-700">{{ $user->teacher->education ?? 'S1 Pendidikan' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center text-sm">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500 mr-3">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Spesialisasi</p>
                                <p class="text-sm text-gray-700">{{ $user->teacher->specialization ?? 'Belum diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistics Section -->
            <div class="animate-fade-in" style="animation-delay: 0.4s">
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl shadow-md border border-indigo-100 overflow-hidden hover:shadow-lg transition-all duration-300 border-pulse">
                    <div class="p-6 border-b border-indigo-100">
                        <h3 class="text-lg font-semibold gradient-text flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 flex items-center justify-center text-white shadow-sm mr-3 pulse-animation">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            Statistik Pengajaran
                        </h3>
                        <p class="text-sm text-gray-500 mt-1 ml-13">Pencapaian dan aktivitas mengajar Anda</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Teaching Hours -->
                            <div class="stat-card bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-clock text-indigo-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Jam Mengajar</p>
                                        <p class="text-2xl font-bold text-gray-800">
                                            @if(isset($user->teacher) && isset($user->teacher->teaching_hours))
                                                {{ $user->teacher->teaching_hours }}
                                            @else
                                                24
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-500" style="width: 75%"></div>
                                </div>
                                <p class="text-xs text-right mt-1 text-gray-500">75% dari rata-rata</p>
                            </div>
                            
                            <!-- Materials Count -->
                            <div class="stat-card bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-teal-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Materi</p>
                                        <p class="text-2xl font-bold text-gray-800">
                                            @if(isset($user->teacher) && method_exists($user->teacher, 'materials'))
                                                {{ $user->teacher->materials()->count() }}
                                            @else
                                                0
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500" style="width: 60%"></div>
                                </div>
                                <p class="text-xs text-right mt-1 text-gray-500">60% dari target</p>
                            </div>
                            
                            <!-- Student Engagement -->
                            <div class="stat-card bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-user-graduate text-amber-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Keterlibatan Siswa</p>
                                        <p class="text-2xl font-bold text-gray-800">
                                            @if(isset($user->teacher) && isset($user->teacher->student_engagement))
                                                {{ $user->teacher->student_engagement }}%
                                            @else
                                                87%
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500" style="width: 87%"></div>
                                </div>
                                <p class="text-xs text-right mt-1 text-gray-500">87% tingkat partisipasi</p>
                            </div>
                        </div>
                        
                        <!-- Achievement Section -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-4 flex items-center border-b pb-2">
                                <i class="fas fa-trophy mr-2 text-amber-500"></i> Pencapaian
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                <div class="flex items-center p-2 rounded-lg bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-100">
                                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-3">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-amber-700">Guru Teladan</p>
                                        <p class="text-sm text-gray-700 font-medium">Semester 2023/2024</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-blue-700">Materi Terbaik</p>
                                        <p class="text-sm text-gray-700 font-medium">3 Materi Unggulan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Change Password Section -->            <div class="animate-fade-in" style="animation-delay: 0.7s">
                    <div class="bg-gradient-to-r from-slate-50 to-gray-50 rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 border-pulse">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50">
                            <h3 class="text-lg font-semibold gradient-text flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-slate-600 to-gray-700 flex items-center justify-center text-white shadow-sm mr-3 pulse-animation">
                                    <i class="fas fa-lock"></i>
                                </div>
                                Keamanan Akun
                            </h3>
                            <p class="text-sm text-gray-500 mt-1 ml-13">Perbarui password Anda secara berkala untuk keamanan lebih baik</p>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('guru.profile.update-password') }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>                                    <div class="relative">
                                            <input type="password" name="current_password" id="current_password" 
                                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 pl-10 shadow-sm hover:border-indigo-400 transition-colors" 
                                                required
                                                autocomplete="current-password"
                                                aria-label="Password Saat Ini">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" aria-hidden="true">
                                                <i class="fas fa-lock text-indigo-500"></i>
                                            </div>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <i class="fas fa-eye-slash text-gray-400 cursor-pointer toggle-password" 
                                                   data-target="current_password" 
                                                   tabindex="0" 
                                                   role="button" 
                                                   aria-label="Tampilkan Password"
                                                   title="Tampilkan Password"></i>
                                            </div>
                                        </div>
                                        @error('current_password')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>                                    <div class="relative">
                                            <input type="password" name="password" id="password" 
                                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 pl-10 shadow-sm hover:border-indigo-400 transition-colors" 
                                                required
                                                autocomplete="new-password"
                                                aria-label="Password Baru"
                                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$" 
                                                title="Password minimal 8 karakter, memiliki huruf besar, huruf kecil, dan angka">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" aria-hidden="true">
                                                <i class="fas fa-key text-indigo-500"></i>
                                            </div>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <i class="fas fa-eye-slash text-gray-400 cursor-pointer toggle-password" 
                                                   data-target="password" 
                                                   tabindex="0" 
                                                   role="button" 
                                                   aria-label="Tampilkan Password"
                                                   title="Tampilkan Password"></i>
                                            </div>
                                        </div>                                    <div class="mt-2">
                                        <div class="text-xs text-gray-500 mb-1">
                                            Password minimal 8 karakter, memiliki huruf besar, huruf kecil, dan angka
                                        </div>
                                        <!-- Password strength meter -->
                                        <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden" id="password-strength-meter" aria-label="Kekuatan Password">
                                            <div class="h-full bg-gray-400 transition-all duration-300" id="password-strength-bar" style="width: 0%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs mt-1">
                                            <span class="text-gray-400" id="strength-text">Belum dinilai</span>
                                            <span class="text-blue-500 cursor-pointer keyboard-focus" tabindex="0" role="button" id="password-tips" aria-label="Tips password yang kuat">
                                                <i class="fas fa-info-circle mr-1"></i>Tips
                                            </span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    </div>
                                      <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>                                    <div class="relative">
                                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 pl-10 shadow-sm hover:border-indigo-400 transition-colors" 
                                                required
                                                autocomplete="new-password"
                                                aria-label="Konfirmasi Password Baru">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" aria-hidden="true">
                                                <i class="fas fa-check-double text-indigo-500"></i>
                                            </div>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <i class="fas fa-eye-slash text-gray-400 cursor-pointer toggle-password" 
                                                   data-target="password_confirmation" 
                                                   tabindex="0" 
                                                   role="button" 
                                                   aria-label="Tampilkan Password"
                                                   title="Tampilkan Password"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>                              <div class="flex justify-end">                                <button type="submit" 
                                           class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-lg shadow-md flex items-center transform transition-transform duration-200 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                           aria-label="Ubah Password">
                                        <i class="fas fa-save mr-2" aria-hidden="true"></i>
                                        <span>Ubah Password</span>
                                        <div class="absolute inset-0 overflow-hidden rounded-lg transition-all duration-300 ease-out transform pointer-events-none button-effect"></div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Page Footer -->
    <div class="mt-8 pt-6 border-t border-gray-200 text-center">
        <div class="flex flex-col md:flex-row items-center justify-between text-sm text-gray-500">
            <div class="flex items-center mb-3 md:mb-0">
                <i class="fas fa-clock mr-1"></i>
                <span>Terakhir diperbaharui: {{ $user->updated_at->diffForHumans() }}</span>
            </div>            <div class="flex space-x-4">
                <a href="{{ route('guru.settings.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-question-circle mr-1"></i>
                    <span>Bantuan</span>
                </a>
                <a href="{{ route('guru.settings.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-shield-alt mr-1"></i>
                    <span>Privasi</span>
                </a>
                <a href="#main-content" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>Kembali ke atas</span>
                </a>
            </div>
        </div>
    </div>
</main>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Create toast container if it doesn't exist
        if (!document.getElementById('toast-container')) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            document.body.appendChild(toastContainer);
        }
        
        // Toast notification function
        window.showToast = function(message, type = 'info', duration = 5000) {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            let icon = '';
            switch(type) {
                case 'success':
                    icon = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'error':
                    icon = '<i class="fas fa-exclamation-circle"></i>';
                    break;
                case 'info':
                default:
                    icon = '<i class="fas fa-info-circle"></i>';
            }
            
            toast.innerHTML = `
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">${message}</div>
                <button class="toast-close" aria-label="Tutup notifikasi">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            toastContainer.appendChild(toast);
            
            // Trigger reflow to enable transitions
            toast.offsetHeight;
            
            // Show the toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            // Set up close button
            const closeBtn = toast.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => {
                closeToast(toast);
            });
            
            // Auto close after duration
            setTimeout(() => {
                closeToast(toast);
            }, duration);
            
            function closeToast(toast) {
                toast.classList.remove('show');
                toast.addEventListener('transitionend', function() {
                    toast.remove();
                });
            }
            
            return toast;
        };
        
        // Show toast for session messages
        @if (session('success'))
            window.showToast("{{ session('success') }}", 'success');
        @endif
        
        @if (session('error'))
            window.showToast("{{ session('error') }}", 'error');
        @endif
        
        @if (session('status'))
            window.showToast("{{ session('status') }}", 'info');
        @endif
        
        // Add animation for elements with staggered timing
        const animatedElements = document.querySelectorAll('.animate-fade-in');
        
        animatedElements.forEach((el, index) => {
            // Use requestAnimationFrame for smoother animations
            requestAnimationFrame(() => {
                setTimeout(() => {
                    el.style.opacity = "1";
                    el.style.transform = "translateY(0)";
                }, 150 * index); // Slightly longer delay for more noticeable staggering
            });
        });
        
        // Animate number counters when they come into view
        const numberElements = document.querySelectorAll('.text-2xl.font-bold');
        
        // Create an intersection observer for number animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Get the target number from the element
                    const targetNumber = parseInt(entry.target.textContent.trim(), 10);
                    if (!isNaN(targetNumber)) {
                        // Animate count up
                        animateCountUp(entry.target, targetNumber);
                    }
                    // Unobserve after animation
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        // Function to animate count up
        function animateCountUp(element, target) {
            let count = 0;
            const duration = 1500; // ms
            const frameDuration = 1000 / 60; // 60fps
            const totalFrames = Math.ceil(duration / frameDuration);
            
            // Save the original text for non-number parts
            const originalText = element.textContent;
            const numbersOnly = originalText.match(/\d+/);
            
            if (!numbersOnly) return;
            
            const startText = originalText.split(numbersOnly[0])[0] || '';
            const endText = originalText.split(numbersOnly[0])[1] || '';
            
            // Use requestAnimationFrame for smooth animation
            function countUp(currentFrame) {
                const progress = currentFrame / totalFrames;
                const currentCount = Math.floor(easeOutQuad(progress) * target);
                
                element.textContent = `${startText}${currentCount}${endText}`;
                
                if (currentFrame < totalFrames) {
                    requestAnimationFrame(() => countUp(currentFrame + 1));
                } else {
                    element.textContent = originalText; // Reset to original for accuracy
                }
            }
            
            // Easing function for more natural animation
            function easeOutQuad(x) {
                return 1 - (1 - x) * (1 - x);
            }
            
            // Start the animation
            countUp(1);
        }
        
        // Observe all number elements
        numberElements.forEach(el => {
            observer.observe(el);
        });
        
        // Password visibility toggle
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');
        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                
                // Toggle password visibility
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                    this.setAttribute('aria-label', 'Sembunyikan Password');
                    this.setAttribute('title', 'Sembunyikan Password');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                    this.setAttribute('aria-label', 'Tampilkan Password');
                    this.setAttribute('title', 'Tampilkan Password');
                }
                
                // Add a brief highlight effect
                passwordInput.classList.add('ring-2', 'ring-indigo-200');
                setTimeout(() => {
                    passwordInput.classList.remove('ring-2', 'ring-indigo-200');
                }, 300);
            });
        });
        
        // Add responsive adjustments
        function handleResponsiveLayout() {
            const isMobile = window.innerWidth < 768;
            const profileCards = document.querySelectorAll('.stat-card');
            
            if (isMobile) {
                profileCards.forEach(card => {
                    card.classList.add('mobile-optimized');
                });
            } else {
                profileCards.forEach(card => {
                    card.classList.remove('mobile-optimized');
                });
            }
        }
        
        // Form submission handler for better UX
        const passwordForm = document.querySelector('form[action*="update-password"]');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                
                // Add loading state
                if (submitBtn) {
                    submitBtn.classList.add('btn-loading');
                    submitBtn.setAttribute('disabled', 'disabled');
                    
                    // Create loading text for screen readers
                    const srOnly = document.createElement('span');
                    srOnly.classList.add('sr-only');
                    srOnly.textContent = 'Memproses perubahan password...';
                    submitBtn.appendChild(srOnly);
                }
                
                // Password validation before submit
                const newPassword = document.getElementById('password');
                const passwordConfirm = document.getElementById('password_confirmation');
                
                if (newPassword && passwordConfirm) {
                    if (newPassword.value !== passwordConfirm.value) {
                        e.preventDefault();
                        // Show error
                        alert('Password baru dan konfirmasi password tidak sama');
                        // Remove loading state
                        submitBtn.classList.remove('btn-loading');
                        submitBtn.removeAttribute('disabled');
                        if (srOnly) submitBtn.removeChild(srOnly);
                        return false;
                    }
                    
                    // Check password strength
                    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
                    if (!regex.test(newPassword.value)) {
                        e.preventDefault();
                        // Show error
                        alert('Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, dan angka');
                        // Remove loading state
                        submitBtn.classList.remove('btn-loading');
                        submitBtn.removeAttribute('disabled');
                        if (srOnly) submitBtn.removeChild(srOnly);
                        return false;
                    }
                }
            });
        }
        
        // Run on load and resize
        handleResponsiveLayout();
        window.addEventListener('resize', handleResponsiveLayout);
        
        // Password Strength Meter
        const passwordInput = document.getElementById('password');
        const strengthMeter = document.getElementById('password-strength-bar');
        const strengthText = document.getElementById('strength-text');
        const passwordTips = document.getElementById('password-tips');
        
        if (passwordInput && strengthMeter && strengthText) {
            passwordInput.addEventListener('input', function() {
                const val = passwordInput.value;
                let strength = 0;
                let message = '';
                
                // Calculate strength
                if (val.length >= 8) strength += 1;
                if (val.match(/[a-z]+/)) strength += 1;
                if (val.match(/[A-Z]+/)) strength += 1;
                if (val.match(/[0-9]+/)) strength += 1;
                if (val.match(/[^a-zA-Z0-9]+/)) strength += 1;
                
                // Update UI based on strength
                switch(strength) {
                    case 0:
                        strengthMeter.style.width = '0%';
                        strengthMeter.className = 'h-full bg-gray-400 transition-all duration-300';
                        strengthText.textContent = 'Belum dinilai';
                        strengthText.className = 'text-gray-400 text-xs';
                        break;
                    case 1:
                        strengthMeter.style.width = '20%';
                        strengthMeter.className = 'h-full bg-red-500 transition-all duration-300';
                        strengthText.textContent = 'Sangat lemah';
                        strengthText.className = 'text-red-500 text-xs';
                        break;
                    case 2:
                        strengthMeter.style.width = '40%';
                        strengthMeter.className = 'h-full bg-orange-500 transition-all duration-300';
                        strengthText.textContent = 'Lemah';
                        strengthText.className = 'text-orange-500 text-xs';
                        break;
                    case 3:
                        strengthMeter.style.width = '60%';
                        strengthMeter.className = 'h-full bg-yellow-500 transition-all duration-300';
                        strengthText.textContent = 'Sedang';
                        strengthText.className = 'text-yellow-700 text-xs';
                        break;
                    case 4:
                        strengthMeter.style.width = '80%';
                        strengthMeter.className = 'h-full bg-blue-500 transition-all duration-300';
                        strengthText.textContent = 'Kuat';
                        strengthText.className = 'text-blue-500 text-xs';
                        break;
                    case 5:
                        strengthMeter.style.width = '100%';
                        strengthMeter.className = 'h-full bg-green-500 transition-all duration-300';
                        strengthText.textContent = 'Sangat kuat';
                        strengthText.className = 'text-green-500 text-xs';
                        break;
                }
            });
        }
        
        // Password Tips Modal
        if (passwordTips) {
            passwordTips.addEventListener('click', function() {
                // Simple alert for tips, could be replaced with a modal
                alert('Tips untuk password yang kuat:\n\n' +
                      '- Gunakan minimal 8 karakter\n' +
                      '- Kombinasikan huruf besar dan kecil\n' +
                      '- Tambahkan angka dan karakter khusus (!@#$%^&*)\n' +
                      '- Hindari kata-kata umum dan informasi pribadi\n' +
                      '- Gunakan frasa atau kalimat yang mudah diingat');
            });
            
            // Ensure keyboard accessibility
            passwordTips.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        }
        
        // Implement lazy loading for images for slow connections
        if ('loading' in HTMLImageElement.prototype) {
            // Browser supports native lazy loading
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                if (!img.hasAttribute('loading')) {
                    img.setAttribute('loading', 'lazy');
                }
                img.classList.add('lazy-load');
                setTimeout(() => img.classList.add('loaded'), 100);
            });
        } else {
            // Fallback for browsers that don't support native lazy loading
            const lazyLoadObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                        }
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });
            
            const images = document.querySelectorAll('img[data-src]');
            images.forEach(img => {
                img.classList.add('lazy-load');
                lazyLoadObserver.observe(img);
            });
        }
        
        // Add connection speed detection for optimization
        if (navigator.connection) {
            connectionSpeed = navigator.connection.effectiveType;
            
            // Apply optimization for slow connections
            if (connectionSpeed === 'slow-2g' || connectionSpeed === '2g') {
                // Reduce animation complexity
                document.querySelectorAll('.float-animation, .border-pulse').forEach(el => {
                    el.classList.remove('float-animation', 'border-pulse');
                });
                
                // Disable heavyweight animations
                document.querySelectorAll('.animate-fade-in').forEach(el => {
                    el.style.opacity = "1";
                    el.style.transform = "translateY(0)";
                    el.classList.remove('animate-fade-in');
                });
            }
        }
        
        // Online status indicator functionality
        const onlineIndicator = document.querySelector('.pulse-online-status');
        
        function updateOnlineStatus() {
            if (navigator.onLine) {
                if (onlineIndicator) {
                    onlineIndicator.classList.add('bg-green-500');
                    onlineIndicator.classList.remove('bg-gray-400');
                    onlineIndicator.setAttribute('aria-label', 'Status: Online');
                }
            } else {
                if (onlineIndicator) {
                    onlineIndicator.classList.remove('bg-green-500');
                    onlineIndicator.classList.add('bg-gray-400');
                    onlineIndicator.setAttribute('aria-label', 'Status: Offline');
                    
                    // Show offline notification
                    showToast('Anda sedang offline. Beberapa fitur mungkin tidak tersedia.', 'error');
                }
            }
        }
        
        // Check online status when page loads
        updateOnlineStatus();
        
        // Monitor online/offline events
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
    });
</script>
@endsection
