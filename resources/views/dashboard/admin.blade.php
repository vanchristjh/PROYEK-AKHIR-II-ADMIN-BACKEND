@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('header', 'Dashboard Admin')

@section('content')
    <!-- Welcome Banner with enhanced gradient, animations and floating elements -->
    <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-purple-600 animate-gradient-x rounded-xl shadow-xl p-8 mb-8 text-white relative overflow-hidden group transform transition-all hover:-translate-y-1 hover:shadow-2xl duration-500">
        <div class="particles-container absolute inset-0 pointer-events-none"></div>
        <div class="absolute -right-5 -top-5 opacity-10 transform group-hover:scale-110 transition-transform duration-700">
            <i class="fas fa-user-shield text-10xl"></i>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
        <div class="absolute -left-24 -bottom-24 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-blue-300/20 rounded-full blur-3xl"></div>
        <div class="relative animate-fade-in z-10">
            <div class="flex items-center space-x-4 mb-4">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm shadow-lg">
                    <i class="fas fa-crown text-amber-300 text-2xl animate-pulse"></i>
                </div>
                <h2 class="text-3xl font-bold tracking-tight text-shadow-lg">Selamat datang, {{ auth()->user()->name }}!</h2>
            </div>
            <p class="text-lg text-white/90 max-w-2xl ml-1 text-shadow-sm">
                Panel administrasi untuk mengelola data sekolah, pengguna, dan aktivitas akademik.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="#shortcuts" class="btn-glass flex items-center px-5 py-3 rounded-lg text-sm font-medium transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                    <i class="fas fa-bolt mr-2"></i> Aksi Cepat
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-indigo-800/80 text-white hover:bg-indigo-900 px-5 py-3 rounded-lg inline-flex items-center text-sm font-medium transition-all duration-300 shadow-lg shadow-indigo-900/30 backdrop-blur-sm hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-users mr-2"></i> Kelola Pengguna
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards with real data -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-medium text-gray-800 flex items-center">
            <div class="p-2 bg-indigo-100 rounded-lg mr-3 shadow-inner">
                <i class="fas fa-chart-pie text-indigo-600"></i>
            </div>
            <span class="text-shadow-sm">Ringkasan Sistem</span>
        </h3>
        <div class="text-sm text-gray-500 flex items-center bg-white py-1 px-3 rounded-lg shadow-sm">
            <i class="fas fa-sync-alt mr-1 hover:rotate-180 transition-transform cursor-pointer" id="refresh-data-btn" title="Refresh data"></i>
            <span>Terakhir diperbarui: {{ now()->format('H:i') }}</span>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Students Card - Real data -->
        <div class="dashboard-card bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-md p-6 transition-all hover:shadow-lg border border-gray-100/50 overflow-hidden relative transform hover:-translate-y-1 duration-300">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full"></div>
            <div class="flex items-start relative z-10">
                <div class="p-3 rounded-xl bg-blue-100 text-blue-600 shadow-inner ring-4 ring-white">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Total Siswa</h3>
                    <p class="card-number text-2xl font-bold text-gray-800 my-1 counter floating-element" data-type="student">{{ $studentCount ?? 0 }}</p>
                    <div class="mt-2">
                        <a href="{{ route('admin.users.index') }}?role=siswa" class="text-sm text-blue-600 hover:text-blue-800 inline-flex items-center group">
                            <span>Lihat detail</span>
                            <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Teachers Card - Real data -->
        <div class="dashboard-card bg-gradient-to-br from-white to-green-50 rounded-xl shadow-md p-6 transition-all hover:shadow-lg border border-gray-100/50 overflow-hidden relative transform hover:-translate-y-1 duration-300">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-green-50 rounded-full"></div>
            <div class="flex items-start relative z-10">
                <div class="p-3 rounded-xl bg-green-100 text-green-600 shadow-inner ring-4 ring-white">
                    <i class="fas fa-chalkboard-teacher text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Total Guru</h3>
                    <p class="card-number text-2xl font-bold text-gray-800 my-1 counter floating-element" data-type="teacher">{{ $teacherCount ?? 0 }}</p>
                    <div class="mt-2">
                        <a href="{{ route('admin.users.index') }}?role=guru" class="text-sm text-green-600 hover:text-green-800 inline-flex items-center group">
                            <span>Lihat detail</span>
                            <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Classes Card - Real data -->
        <div class="dashboard-card bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-md p-6 transition-all hover:shadow-lg border border-gray-100/50 overflow-hidden relative transform hover:-translate-y-1 duration-300">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-50 rounded-full"></div>
            <div class="flex items-start relative z-10">
                <div class="p-3 rounded-xl bg-purple-100 text-purple-600 shadow-inner ring-4 ring-white">
                    <i class="fas fa-school text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Total Kelas</h3>
                    <p class="card-number text-2xl font-bold text-gray-800 my-1 counter floating-element" data-type="classroom">{{ $classroomCount ?? 0 }}</p>
                    <div class="mt-2">
                        <a href="{{ route('admin.classrooms.index') }}" class="text-sm text-purple-600 hover:text-purple-800 inline-flex items-center group">
                            <span>Lihat detail</span>
                            <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Subjects Card - Real data -->
        <div class="dashboard-card bg-gradient-to-br from-white to-amber-50 rounded-xl shadow-md p-6 transition-all hover:shadow-lg border border-gray-100/50 overflow-hidden relative transform hover:-translate-y-1 duration-300">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-50 rounded-full"></div>
            <div class="flex items-start relative z-10">
                <div class="p-3 rounded-xl bg-amber-100 text-amber-600 shadow-inner ring-4 ring-white">
                    <i class="fas fa-book text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Mata Pelajaran</h3>
                    <p class="card-number text-2xl font-bold text-gray-800 my-1 counter floating-element" data-type="subject">{{ $subjectCount ?? 0 }}</p>
                    <div class="mt-2">
                        <a href="{{ route('admin.subjects.index') }}" class="text-sm text-amber-600 hover:text-amber-800 inline-flex items-center group">
                            <span>Lihat detail</span>
                            <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Management and Integration Section -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-800 flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3 shadow-inner">
                    <i class="fas fa-users-cog text-blue-600"></i>
                </div>
                <span class="text-shadow-sm">Manajemen Pengguna</span>
            </h3>
            <a href="{{ route('admin.users.create') }}" class="btn-primary text-sm px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow hover:shadow-lg transform hover:-translate-y-1 flex items-center">
                <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna Baru
            </a>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Teacher Management -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg duration-300">
                <div class="card-header p-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-200 rounded-lg mr-3 shadow-inner">
                                <i class="fas fa-chalkboard-teacher text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Guru</h3>
                        </div>
                        <a href="{{ route('admin.users.index') }}?role=guru" class="text-sm text-green-600 hover:text-green-800 font-medium hover:underline flex items-center group">
                            <span>Lihat semua ({{ $teacherCount ?? 0 }})</span>
                            <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="p-5">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">Nama</th>
                                <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentTeachers ?? [] as $teacher)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white shadow-sm">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $teacher->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $teacher->email }}</div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        @if($teacher->teacherSubjects->count() > 0)
                                            {{ $teacher->teacherSubjects->pluck('name')->take(2)->join(', ') }}
                                            @if($teacher->teacherSubjects->count() > 2)
                                                <span class="text-xs text-gray-400">+{{ $teacher->teacherSubjects->count() - 2 }} lainnya</span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">Belum ada</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.users.edit', $teacher) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50/50 hover:bg-blue-100 p-1 px-2 rounded-md transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-center text-sm text-gray-500">
                                    <div class="py-6 flex flex-col items-center">
                                        <div class="rounded-full bg-gray-100 p-3 mb-2">
                                            <i class="fas fa-chalkboard-teacher text-gray-400 text-xl"></i>
                                        </div>
                                        <p>Belum ada data guru</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Student Management -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg duration-300">
                <div class="card-header p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-200 rounded-lg mr-3 shadow-inner">
                                <i class="fas fa-user-graduate text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Siswa</h3>
                        </div>
                        <a href="{{ route('admin.users.index') }}?role=siswa" class="text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline flex items-center group">
                            <span>Lihat semua ({{ $studentCount ?? 0 }})</span>
                            <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="p-5">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">Nama</th>
                                <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentStudents ?? [] as $student)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white shadow-sm">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        @if($student->classroom)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 shadow-sm">
                                                {{ $student->classroom->name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">Belum ada</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.users.edit', $student) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50/50 hover:bg-blue-100 p-1 px-2 rounded-md transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-center text-sm text-gray-500">
                                    <div class="py-6 flex flex-col items-center">
                                        <div class="rounded-full bg-gray-100 p-3 mb-2">
                                            <i class="fas fa-user-graduate text-gray-400 text-xl"></i>
                                        </div>
                                        <p>Belum ada data siswa</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Classroom and Subject Integration -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-800 flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg mr-3 shadow-inner">
                    <i class="fas fa-school text-purple-600"></i>
                </div>
                <span class="text-shadow-sm">Manajemen Akademis</span>
            </h3>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Classroom Management -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg duration-300">
                <div class="card-header p-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-200 rounded-lg mr-3 shadow-inner">
                                <i class="fas fa-school text-purple-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Kelas</h3>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.classrooms.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium hover:underline flex items-center group">
                                <span>Lihat semua</span>
                                <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                            </a>
                            <a href="{{ route('admin.classrooms.create') }}" class="text-sm inline-flex items-center px-3 py-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-md hover:from-purple-600 hover:to-purple-700 shadow hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                <i class="fas fa-plus mr-1"></i> Baru
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="p-5">
                    <div class="space-y-3">
                        @forelse($recentClassrooms ?? [] as $classroom)
                        <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-all hover:bg-purple-50/30 hover:-translate-y-0.5 transform duration-300">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-2 rounded-lg mr-3 text-white shadow-sm">
                                        <span class="font-bold">{{ substr($classroom->name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-800">{{ $classroom->name }}</h4>
                                        <p class="text-xs text-gray-500">
                                            {{ $classroom->students_count ?? 0 }} siswa
                                            @if($classroom->homeroom_teacher)
                                             · Wali kelas: {{ $classroom->homeroom_teacher->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.classrooms.edit', $classroom) }}" class="text-purple-600 hover:text-purple-900 bg-purple-50/50 hover:bg-purple-100 p-1 px-2 rounded-md transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="bg-gray-50 p-8 rounded-lg text-center">
                            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 text-purple-500 mb-4">
                                <i class="fas fa-school text-2xl"></i>
                            </div>
                            <h4 class="text-base font-medium text-gray-800 mb-2">Belum Ada Kelas</h4>
                            <p class="text-gray-500 mb-4">Anda belum menambahkan kelas apapun</p>
                            <a href="{{ route('admin.classrooms.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Tambah Kelas Baru
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Subject Management -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg duration-300">
                <div class="card-header p-5 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-amber-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-amber-200 rounded-lg mr-3 shadow-inner">
                                <i class="fas fa-book text-amber-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Mata Pelajaran</h3>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.subjects.index') }}" class="text-sm text-amber-600 hover:text-amber-800 font-medium hover:underline flex items-center group">
                                <span>Lihat semua</span>
                                <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                            </a>
                            <a href="{{ route('admin.subjects.create') }}" class="text-sm inline-flex items-center px-3 py-1 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-md hover:from-amber-600 hover:to-amber-700 shadow hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                <i class="fas fa-plus mr-1"></i> Baru
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="p-5">
                    <div class="space-y-3">
                        @forelse($recentSubjects ?? [] as $subject)
                        <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-all hover:bg-amber-50/30 hover:-translate-y-0.5 transform duration-300">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="bg-gradient-to-br from-amber-400 to-amber-600 p-2 rounded-lg mr-3 text-white shadow-sm">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-800">{{ $subject->name }}</h4>
                                        <p class="text-xs text-gray-500">
                                            @if($subject->teachers_count > 0)
                                                {{ $subject->teachers_count }} guru
                                            @else
                                                Belum ada guru
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.subjects.edit', $subject) }}" class="text-amber-600 hover:text-amber-900 bg-amber-50/50 hover:bg-amber-100 p-1 px-2 rounded-md transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="bg-gray-50 p-8 rounded-lg text-center">
                            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 text-amber-500 mb-4">
                                <i class="fas fa-book text-2xl"></i>
                            </div>
                            <h4 class="text-base font-medium text-gray-800 mb-2">Belum Ada Mata Pelajaran</h4>
                            <p class="text-gray-500 mb-4">Anda belum menambahkan mata pelajaran apapun</p>
                            <a href="{{ route('admin.subjects.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Tambah Mata Pelajaran
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- User List with real data -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg">
            <div class="card-header flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-indigo-50">
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-100 rounded-lg mr-3 shadow-inner">
                        <i class="fas fa-user-plus text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Pengguna Terbaru</h3>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center group">
                    <span>Lihat semua</span>
                    <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($recentUsers as $index => $user)
                <div class="flex items-center py-4 px-6 hover:bg-indigo-50/30 transition-colors duration-150 animate-item" style="animation-delay: {{ $index * 100 }}ms">
                    <div class="flex-shrink-0">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="h-12 w-12 rounded-xl object-cover shadow-sm border border-gray-200">
                        @else
                            <div class="h-12 w-12 rounded-xl 
                                {{ $user->role->slug === 'admin' ? 'bg-gradient-to-br from-indigo-400 to-indigo-600' : 
                                   ($user->role->slug === 'guru' ? 'bg-gradient-to-br from-green-400 to-green-600' : 
                                    'bg-gradient-to-br from-blue-400 to-blue-600') }} 
                                flex items-center justify-center text-white font-bold shadow-sm">
                                <i class="fas fa-{{ $user->role->slug === 'admin' ? 'user-shield' : ($user->role->slug === 'guru' ? 'chalkboard-teacher' : 'user-graduate') }}"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="text-base font-medium text-gray-800">{{ $user->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->role->slug === 'admin' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : ($user->role->slug === 'guru' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-blue-100 text-blue-800 border border-blue-200') }}">
                        {{ $user->role->name }}
                    </span>
                </div>
                @empty
                <div class="py-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-500 mb-4">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">Belum ada pengguna yang terdaftar.</p>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Tambah pengguna baru
                    </a>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Activity Log with real data -->
        <div id="shortcuts" class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg">
            <div class="card-header p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-blue-50">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3 shadow-inner">
                        <i class="fas fa-history text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Sistem</h3>
                </div>
            </div>
            
            <div class="divide-y divide-gray-100 max-h-[400px] overflow-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                @if(isset($recentActivities) && $recentActivities->count() > 0)
                    @foreach($recentActivities as $index => $activity)
                    <div class="flex items-center py-3 px-6 hover:bg-blue-50/20 transition-all duration-150 hover:-translate-y-0.5 transform animate-item" style="animation-delay: {{ $index * 100 }}ms">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-{{ $colors[$index % count($colors)] }}-400 to-{{ $colors[$index % count($colors)] }}-600 flex items-center justify-center text-white shadow-sm">
                                <i class="fas fa-{{ $icons[$activity->type] ?? 'info-circle' }} text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-800">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="fas fa-clock mr-1 opacity-70"></i>
                                {{ $activity->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mb-4">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                        <p class="text-gray-500 mb-2">Belum ada aktivitas yang tercatat.</p>
                        <p class="text-sm text-gray-400">Aktivitas akan tampil saat ada perubahan data dalam sistem.</p>
                    </div>
                    
                    <div class="p-4 bg-blue-50 border-t border-blue-100">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <p class="text-sm text-blue-700">Aktivitas yang direkam meliputi: penambahan pengguna, perubahan kelas, dan pengaturan mata pelajaran.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions with enhanced styling and hover effects -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl shadow-md overflow-hidden mb-6 border border-gray-100/50">
        <div class="card-header p-6 border-b border-gray-200/50 bg-white">
            <div class="flex items-center">
                <div class="p-2 bg-indigo-100 rounded-lg mr-3 shadow-inner">
                    <i class="fas fa-bolt text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Aksi Cepat</h3>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <a href="{{ route('admin.users.create') }}" class="quick-action block bg-white hover:bg-blue-50 border border-gray-200 p-5 rounded-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-lg group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-14 w-14 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white shadow-md group-hover:shadow-blue-200/50 transition-all duration-300 group-hover:scale-110">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-base font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Tambah Pengguna</h4>
                            <p class="text-sm text-gray-500 mt-1">Tambahkan pengguna baru ke sistem</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.classrooms.create') }}" class="quick-action block bg-white hover:bg-purple-50 border border-gray-200 p-5 rounded-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-lg group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-14 w-14 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white shadow-md group-hover:shadow-purple-200/50 transition-all duration-300 group-hover:scale-110">
                            <i class="fas fa-school text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-base font-semibold text-gray-800 group-hover:text-purple-600 transition-colors">Buat Kelas</h4>
                            <p class="text-sm text-gray-500 mt-1">Tambahkan kelas baru</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.subjects.create') }}" class="quick-action block bg-white hover:bg-amber-50 border border-gray-200 p-5 rounded-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-lg group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-14 w-14 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white shadow-md group-hover:shadow-amber-200/50 transition-all duration-300 group-hover:scale-110">
                            <i class="fas fa-book text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-base font-semibold text-gray-800 group-hover:text-amber-600 transition-colors">Tambah Mapel</h4>
                            <p class="text-sm text-gray-500 mt-1">Buat mata pelajaran baru</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.announcements.create') }}" class="quick-action block bg-white hover:bg-red-50 border border-gray-200 p-5 rounded-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-lg group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-14 w-14 rounded-xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-white shadow-md group-hover:shadow-red-200/50 transition-all duration-300 group-hover:scale-110">
                            <i class="fas fa-bullhorn text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-base font-semibold text-gray-800 group-hover:text-red-600 transition-colors">Buat Pengumuman</h4>
                            <p class="text-sm text-gray-500 mt-1">Sampaikan informasi penting</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Announcements Section -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-800 flex items-center">
                <div class="p-2 bg-red-100 rounded-lg mr-3 shadow-inner">
                    <i class="fas fa-bullhorn text-red-600"></i>
                </div>
                <span class="text-shadow-sm">Pengumuman Terbaru</span>
            </h3>
            <a href="{{ route('admin.announcements.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium inline-flex items-center group bg-red-50 px-3 py-1 rounded-lg transition-colors hover:bg-red-100">
                <span>Kelola semua pengumuman</span>
                <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg">
            @if(isset($recentAnnouncements) && $recentAnnouncements->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($recentAnnouncements as $announcement)
                    <div class="p-5 hover:bg-red-50/20 transition-all duration-300 {{ $announcement->is_important ? 'bg-red-50' : '' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full {{ $announcement->is_important ? 'bg-gradient-to-br from-red-400 to-red-600' : 'bg-gradient-to-br from-indigo-400 to-indigo-600' }} flex items-center justify-center text-white shadow-sm">
                                <i class="fas fa-{{ $announcement->is_important ? 'exclamation-circle' : 'bullhorn' }}"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between mb-1">
                                    <h4 class="font-medium text-gray-900 group flex items-center">
                                        @if($announcement->is_important)
                                            <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full mr-2 inline-block shadow-sm">Penting</span>
                                        @endif
                                        {{ $announcement->title }}
                                    </h4>
                                    <span class="text-sm text-gray-500">{{ $announcement->publish_date->format('d M Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ Str::limit($announcement->content, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Oleh: {{ $announcement->author ? $announcement->author->name : 'Unknown' }}</span>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-xs text-blue-600 hover:text-blue-800 bg-blue-50 px-2 py-1 rounded hover:bg-blue-100 transition-colors">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.announcements.show', $announcement) }}" class="text-xs text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2 py-1 rounded hover:bg-indigo-100 transition-colors">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center">
                    <div class="h-20 w-20 mx-auto bg-red-50 rounded-full flex items-center justify-center text-red-500 mb-4">
                        <i class="fas fa-bullhorn text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada pengumuman</h3>
                    <p class="text-sm text-gray-500 mb-4">Buat pengumuman untuk dibagikan kepada guru dan siswa</p>
                    <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Buat Pengumuman Sekarang
                    </a>
                </div>
            @endif
            
            <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-between items-center">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1 text-red-500"></i>
                    Pengumuman akan dilihat oleh guru dan siswa
                </p>
                <a href="{{ route('admin.announcements.create') }}" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-1"></i> Buat Pengumuman
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .btn-glass {
        background-color: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-glass:hover {
        background-color: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .text-10xl {
        font-size: 10rem;
    }
    
    .animate-item {
        opacity: 0;
        animation: item-appear 0.5s ease forwards;
    }
    
    .counter {
        display: inline-block;
        position: relative;
    }
    
    .counter:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, currentColor, transparent);
        animation: counter-line 2s ease-in-out;
    }
    
    .animate-gradient-x {
        background-size: 300% 300%;
        animation: gradient-x 15s ease infinite;
    }
    
    .animate-fade-in {
        animation: fade-in 0.6s ease-in-out;
    }
    
    .floating-element {
        animation: floating 3s ease-in-out infinite alternate;
    }
    
    .particles-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    
    .text-shadow-sm {
        text-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .text-shadow-lg {
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }
    
    .scrollbar-thumb-gray-300::-webkit-scrollbar-thumb {
        background-color: #D1D5DB;
        border-radius: 3px;
    }
    
    .scrollbar-track-gray-100::-webkit-scrollbar-track {
        background-color: #F3F4F6;
    }
    
    @keyframes counter-line {
        0% { width: 0; left: 50%; opacity: 0; }
        50% { opacity: 1; }
        100% { width: 100%; left: 0; opacity: 0; }
    }
    
    @keyframes item-appear {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
    
    @keyframes floating {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(-5px);
        }
    }
    
    /* Add subtle pulse animation to icons on hover */
    .dashboard-card:hover i {
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
    
    /* Card hover effects */
    .quick-action:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Better animation for cards */
    .dashboard-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Animation for icons */
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    // Create floating particles effect
    document.addEventListener("DOMContentLoaded", function() {
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
        
        // Simple counter animation
        animateCounters();
        
        // Refresh data functionality
        document.getElementById('refresh-data-btn').addEventListener('click', function() {
            fetchUpdatedStats();
            this.classList.add('animate-spin');
            setTimeout(() => {
                this.classList.remove('animate-spin');
            }, 1000);
        });
    });
    
    function animateCounters() {
        document.querySelectorAll('.counter').forEach(counter => {
            const value = parseInt(counter.textContent, 10);
            counter.textContent = '0';
            
            setTimeout(() => {
                const duration = 1500;
                const steps = 20;
                const stepValue = value / steps;
                const stepTime = duration / steps;
                let currentStep = 0;
                
                const interval = setInterval(() => {
                    currentStep++;
                    counter.textContent = Math.ceil(Math.min(stepValue * currentStep, value)).toString();
                    
                    if (currentStep >= steps) {
                        clearInterval(interval);
                    }
                }, stepTime);
            }, 300);
        });
    }
    
    // Function to fetch updated statistics via AJAX
    function fetchUpdatedStats() {
        fetch('{{ route("admin.dashboard.stats") }}')
            .then(response => response.json())
            .then(data => {
                updateCounters(data);
                updateLastUpdated();
                
                // Update recent lists if provided
                if (data.recentUsers) updateRecentUsersList(data.recentUsers);
                if (data.recentClassrooms) updateRecentClassroomsList(data.recentClassrooms);
                if (data.recentSubjects) updateRecentSubjectsList(data.recentSubjects);
                if (data.recentAnnouncements) updateAnnouncementsList(data.recentAnnouncements);
            })
            .catch(error => console.error('Error fetching updated stats:', error));
    }
    
    function updateCounters(data) {
        // Update counter values
        document.querySelector('[data-type="student"]').textContent = data.studentCount;
        document.querySelector('[data-type="teacher"]').textContent = data.teacherCount;
        document.querySelector('[data-type="classroom"]').textContent = data.classroomCount;
        document.querySelector('[data-type="subject"]').textContent = data.subjectCount;
    }
    
    function updateLastUpdated() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.querySelector('#refresh-data-btn').nextElementSibling.textContent = `Terakhir diperbarui: ${hours}:${minutes}`;
    }
    
    function updateRecentUsersList(users) {
        const userList = document.querySelector('#recent-users-list');
        if (!userList) return;
        
        userList.innerHTML = '';
        if (users.length === 0) {
            userList.innerHTML = `
                <div class="py-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-500 mb-4">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">Belum ada pengguna yang terdaftar.</p>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Tambah pengguna baru
                    </a>
                </div>
            `;
            return;
        }
        
        users.forEach((user, index) => {
            const roleColor = user.role.slug === 'admin' ? 'indigo' : (user.role.slug === 'guru' ? 'green' : 'blue');
            const roleIcon = user.role.slug === 'admin' ? 'user-shield' : (user.role.slug === 'guru' ? 'chalkboard-teacher' : 'user-graduate');
            
            const userItem = document.createElement('div');
            userItem.className = `flex items-center py-4 px-6 hover:bg-indigo-50/30 transition-colors duration-150 animate-item`;
            userItem.style.animationDelay = `${index * 100}ms`;
            userItem.innerHTML = `
                <div class="flex-shrink-0">
                    ${user.avatar 
                        ? `<img src="${user.avatar}" alt="${user.name}" class="h-12 w-12 rounded-xl object-cover shadow-sm border border-gray-200">`
                        : `<div class="h-12 w-12 rounded-xl bg-gradient-to-br from-${roleColor}-400 to-${roleColor}-600 flex items-center justify-center text-white font-bold shadow-sm">
                               <i class="fas fa-${roleIcon}"></i>
                           </div>`
                    }
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-base font-medium text-gray-800">${user.name}</h4>
                    <p class="text-sm text-gray-500">${user.email}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-${roleColor}-100 text-${roleColor}-800 border border-${roleColor}-200">
                    ${user.role.name}
                </span>
            `;
            userList.appendChild(userItem);
        });
    }
    
    function updateRecentClassroomsList(classrooms) {
        const classroomList = document.querySelector('#recent-classrooms-list');
        if (!classroomList) return;
        
        classroomList.innerHTML = '';
        if (classrooms.length === 0) {
            classroomList.innerHTML = `
                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 text-purple-500 mb-4">
                        <i class="fas fa-school text-2xl"></i>
                    </div>
                    <h4 class="text-base font-medium text-gray-800 mb-2">Belum Ada Kelas</h4>
                    <p class="text-gray-500 mb-4">Anda belum menambahkan kelas apapun</p>
                    <a href="{{ route('admin.classrooms.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Tambah Kelas Baru
                    </a>
                </div>
            `;
            return;
        }
        
        classrooms.forEach(classroom => {
            const classroomItem = document.createElement('div');
            classroomItem.className = "bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-all hover:bg-purple-50/30 hover:-translate-y-0.5 transform duration-300 mb-3";
            classroomItem.innerHTML = `
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-2 rounded-lg mr-3 text-white shadow-sm">
                            <span class="font-bold">${classroom.name.substr(0, 2)}</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-800">${classroom.name}</h4>
                            <p class="text-xs text-gray-500">
                                ${classroom.students_count || 0} siswa
                                ${classroom.homeroom_teacher ? '· Wali kelas: ' + classroom.homeroom_teacher.name : ''}
                            </p>
                        </div>
                    </div>
                    <a href="/admin/classrooms/${classroom.id}/edit" class="text-purple-600 hover:text-purple-900 bg-purple-50/50 hover:bg-purple-100 p-1 px-2 rounded-md transition-colors">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            `;
            classroomList.appendChild(classroomItem);
        });
    }
    
    function updateRecentSubjectsList(subjects) {
        const subjectList = document.querySelector('#recent-subjects-list');
        if (!subjectList) return;
        
        subjectList.innerHTML = '';
        if (subjects.length === 0) {
            subjectList.innerHTML = `
                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 text-amber-500 mb-4">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <h4 class="text-base font-medium text-gray-800 mb-2">Belum Ada Mata Pelajaran</h4>
                    <p class="text-gray-500 mb-4">Anda belum menambahkan mata pelajaran apapun</p>
                    <a href="{{ route('admin.subjects.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Tambah Mata Pelajaran
                    </a>
                </div>
            `;
            return;
        }
        
        subjects.forEach(subject => {
            const subjectItem = document.createElement('div');
            subjectItem.className = "bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-all hover:bg-amber-50/30 hover:-translate-y-0.5 transform duration-300 mb-3";
            subjectItem.innerHTML = `
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-br from-amber-400 to-amber-600 p-2 rounded-lg mr-3 text-white shadow-sm">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-800">${subject.name}</h4>
                            <p class="text-xs text-gray-500">
                                ${subject.teachers_count > 0 ? subject.teachers_count + ' guru' : 'Belum ada guru'}
                            </p>
                        </div>
                    </div>
                    <a href="/admin/subjects/${subject.id}/edit" class="text-amber-600 hover:text-amber-900 bg-amber-50/50 hover:bg-amber-100 p-1 px-2 rounded-md transition-colors">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            `;
            subjectList.appendChild(subjectItem);
        });
    }
    
    function updateAnnouncementsList(announcements) {
        const announcementsList = document.querySelector('#announcements-list');
        if (!announcementsList) return;
        
        announcementsList.innerHTML = '';
        if (announcements.length === 0) {
            announcementsList.innerHTML = `
                <div class="py-10 text-center">
                    <div class="h-20 w-20 mx-auto bg-red-50 rounded-full flex items-center justify-center text-red-500 mb-4">
                        <i class="fas fa-bullhorn text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada pengumuman</h3>
                    <p class="text-sm text-gray-500 mb-4">Buat pengumuman untuk dibagikan kepada guru dan siswa</p>
                    <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Buat Pengumuman Sekarang
                    </a>
                </div>
            `;
            return;
        }
        
        announcements.forEach(announcement => {
            const announcementItem = document.createElement('div');
            announcementItem.className = `p-5 hover:bg-red-50/20 transition-all duration-300 ${announcement.is_important ? 'bg-red-50' : ''}`;
            announcementItem.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full ${announcement.is_important ? 'bg-gradient-to-br from-red-400 to-red-600' : 'bg-gradient-to-br from-indigo-400 to-indigo-600'} flex items-center justify-center text-white shadow-sm">
                        <i class="fas fa-${announcement.is_important ? 'exclamation-circle' : 'bullhorn'}"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between mb-1">
                            <h4 class="font-medium text-gray-900 group flex items-center">
                                ${announcement.is_important ? '<span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full mr-2 inline-block shadow-sm">Penting</span>' : ''}
                                ${announcement.title}
                            </h4>
                            <span class="text-sm text-gray-500">${announcement.formatted_date}</span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2 mb-2">${announcement.excerpt}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Oleh: ${announcement.author ? announcement.author.name : 'Unknown'}</span>
                            <div class="flex space-x-2">
                                <a href="/admin/announcements/${announcement.id}/edit" class="text-xs text-blue-600 hover:text-blue-800 bg-blue-50 px-2 py-1 rounded hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <a href="/admin/announcements/${announcement.id}" class="text-xs text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2 py-1 rounded hover:bg-indigo-100 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            announcementsList.appendChild(announcementItem);
        });
    }
</script>
@endpush
