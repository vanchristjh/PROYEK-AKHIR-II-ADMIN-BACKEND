@extends('layouts.dashboard')

@section('title', 'Detail Pengguna')

@section('header', 'Detail Pengguna')

@section('navigation')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-users text-lg w-6"></i>
            <span class="ml-3">Pengguna</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.subjects.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Mata Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.classrooms.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-school text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kelas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-bullhorn text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Profil Pengguna</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="flex flex-col md:flex-row">
                <!-- User Avatar and Basic Info -->
                <div class="w-full md:w-1/3 flex flex-col items-center mb-6 md:mb-0">
                    <div class="mb-4">                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-48 h-48 rounded-full object-cover border-4 border-indigo-100">
                        @else
                            <div class="w-48 h-48 rounded-full bg-blue-500 flex items-center justify-center text-white text-5xl font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <span class="mt-2 px-4 py-1 text-sm rounded-full {{ $user->role_id == 1 ? 'bg-red-100 text-red-800' : ($user->role_id == 2 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                        {{ $user->role->name ?? 'Unknown' }}
                    </span>
                </div>

                <!-- Detailed User Information -->
                <div class="w-full md:w-2/3 md:pl-6">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informasi Akun</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Username</p>
                                <p class="font-medium">{{ $user->username }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium">{{ $user->email }}</p>
                            </div>
                              @if($user->nip || $user->nisn)
                            <div>
                                <p class="text-sm text-gray-500">{{ $user->role_id == 2 ? 'NIP' : ($user->role_id == 3 ? 'NISN' : 'ID Pengguna') }}</p>
                                <p class="font-medium">{{ $user->id_number }}</p>
                            </div>
                            @endif
                            
                            <div>
                                <p class="text-sm text-gray-500">Bergabung Pada</p>
                                <p class="font-medium">{{ $user->created_at->format('d F Y') }}</p>
                            </div>
                        </div>
                        
                        @if($user->classroom_id)
                        <h3 class="text-lg font-semibold text-gray-700 mt-6 mb-4 border-b pb-2">Informasi Kelas</h3>
                        <div>
                            <p class="text-sm text-gray-500">Kelas</p>
                            <p class="font-medium">{{ $user->classroom->name ?? 'Tidak Ada' }}</p>
                        </div>
                        @endif
                        
                        <!-- Display classrooms for student -->
                        @if($user->role_id == 3 && count($user->classrooms) > 0)
                        <h3 class="text-lg font-semibold text-gray-700 mt-6 mb-4 border-b pb-2">Kelas yang Diikuti</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($user->classrooms as $classroom)
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-md text-sm">
                                {{ $classroom->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                        
                        <!-- Display subjects for teacher -->
                        @if($user->role_id == 2 && count($user->subjects) > 0)
                        <h3 class="text-lg font-semibold text-gray-700 mt-6 mb-4 border-b pb-2">Mata Pelajaran yang Diampu</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($user->subjects as $subject)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-md text-sm">
                                {{ $subject->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Activity Log Section - Optional, if you're tracking user activity -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-medium mb-4">Riwayat Aktivitas</h2>
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-gray-500">Fitur riwayat aktivitas belum tersedia</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Add any specific scripts for this page if needed
</script>
@endpush
