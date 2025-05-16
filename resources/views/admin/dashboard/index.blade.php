@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('header', 'Dashboard Admin')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <!-- Refresh overlay -->
    <div id="refresh-overlay" class="refresh-overlay">
        <div class="bg-white p-6 rounded-xl shadow-xl flex flex-col items-center">
            <div class="refresh-spinner mb-4"></div>
            <p class="text-gray-700 font-medium">Memperbarui data...</p>
        </div>
    </div>
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-purple-600 rounded-xl shadow-xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute -right-5 -top-5 opacity-10">
            <i class="fas fa-user-shield text-9xl"></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center space-x-4 mb-4">
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-crown text-amber-300 text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold">Selamat datang, {{ auth()->user()->name }}!</h2>
            </div>
            <p class="text-lg text-white/90 max-w-2xl ml-1">
                Panel administrasi untuk mengelola data sekolah, pengguna, dan aktivitas akademik.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="#quick-actions" class="bg-white/20 backdrop-blur-sm border border-white/30 text-white px-5 py-3 rounded-lg flex items-center text-sm font-medium transition-all duration-300 hover:bg-white/30">
                    <i class="fas fa-bolt mr-2"></i> Aksi Cepat
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-indigo-800/80 text-white hover:bg-indigo-900 px-5 py-3 rounded-lg flex items-center text-sm font-medium transition-all duration-300 shadow-lg shadow-indigo-900/30">
                    <i class="fas fa-users mr-2"></i> Kelola Pengguna
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-medium text-gray-800 flex items-center">
            <div class="p-2 bg-indigo-100 rounded-lg mr-3 shadow-inner">
                <i class="fas fa-chart-pie text-indigo-600"></i>
            </div>
            <span>Ringkasan Sistem</span>
        </h3>
        <div class="text-sm text-gray-500 flex items-center bg-white py-1 px-3 rounded-lg shadow-sm">
            <i class="fas fa-sync-alt mr-1 hover:rotate-180 transition-transform cursor-pointer" id="refresh-data-btn" title="Refresh data"></i>
            <span>Terakhir diperbarui: {{ now()->format('H:i') }}</span>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Students Card -->
        <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-md p-6 transition-all hover:shadow-lg border border-gray-100/50 overflow-hidden relative">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full"></div>
            <div class="flex items-start relative z-10">
                <div class="p-3 rounded-xl bg-blue-100 text-blue-600 shadow-inner ring-4 ring-white">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-medium text-blue-600 mb-1">Total Siswa</h4>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-800 mr-2 counter" data-type="student">{{ $studentsCount ?? 0 }}</span>
                        <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="text-xs text-blue-600 hover:underline">
                            Lihat semua
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Teachers Card -->
        <div class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-md p-6 transition-all hover:shadow-lg border border-gray-100/50 overflow-hidden relative">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-green-50 rounded-full"></div>
            <div class="flex items-start relative z-10">
                <div class="p-3 rounded-xl bg-green-100 text-green-600 shadow-inner ring-4 ring-white">
                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-medium text-green-600 mb-1">Total Guru</h4>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-800 mr-2 counter" data-type="teacher">{{ $teachersCount ?? 0 }}</span>
                        <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" class="text-xs text-green-600 hover:underline">
                            Lihat semua
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Classes Card -->
        <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-md p-6 transition-all hover:shadow-lg border border-gray-100/50 overflow-hidden relative">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-50 rounded-full"></div>
            <div class="flex items-start relative z-10">
                <div class="p-3 rounded-xl bg-purple-100 text-purple-600 shadow-inner ring-4 ring-white">
                    <i class="fas fa-school text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-medium text-purple-600 mb-1">Total Kelas</h4>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-800 mr-2 counter" data-type="classroom">{{ $classroomsCount ?? 0 }}</span>
                        <a href="{{ route('admin.classrooms.index') }}" class="text-xs text-purple-600 hover:underline">
                            Lihat semua
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Subjects Card -->
        <div class="bg-gradient-to-br from-white to-amber-50 rounded-xl shadow-md p-6 transition-all hover:shadow-lg border border-gray-100/50 overflow-hidden relative">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-50 rounded-full"></div>
            <div class="flex items-start relative z-10">
                <div class="p-3 rounded-xl bg-amber-100 text-amber-600 shadow-inner ring-4 ring-white">
                    <i class="fas fa-book text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-medium text-amber-600 mb-1">Total Mata Pelajaran</h4>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-800 mr-2 counter" data-type="subject">{{ $subjectsCount ?? 0 }}</span>
                        <a href="{{ route('admin.subjects.index') }}" class="text-xs text-amber-600 hover:underline">
                            Lihat semua
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Management Section -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-800 flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3 shadow-inner">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <span>Manajemen Pengguna</span>
            </h3>
            <a href="{{ route('admin.users.create') }}" class="btn-primary text-sm px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow hover:shadow-lg transform hover:-translate-y-1 flex items-center">
                <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna Baru
            </a>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Teacher Management -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50">
                <div class="card-header p-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex justify-between items-center">
                        <h4 class="text-md font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-chalkboard-teacher mr-2 text-green-600"></i>
                            Guru Terbaru
                        </h4>
                        <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" class="text-sm text-green-600 hover:text-green-800 font-medium inline-flex items-center group">
                            <span>Lihat semua</span>
                            <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="p-5">
                    @if(isset($recentTeachers) && count($recentTeachers) > 0)
                        <div class="space-y-4">
                            @foreach($recentTeachers as $teacher)
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-medium text-gray-800">{{ $teacher->name }}</h5>
                                        <div class="text-xs text-gray-500">
                                            @if(isset($teacher->subjects_count))
                                                Mengajar {{ $teacher->subjects_count }} mata pelajaran
                                            @else
                                                Belum ada mata pelajaran
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.users.edit', $teacher) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-blue-400 text-xl mb-2"></i>
                            <p class="text-gray-500">Belum ada data guru</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Student Management -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50">
                <div class="card-header p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <div class="flex justify-between items-center">
                        <h4 class="text-md font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-user-graduate mr-2 text-blue-600"></i>
                            Siswa Terbaru
                        </h4>
                        <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium inline-flex items-center group">
                            <span>Lihat semua</span>
                            <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="p-5">
                    @if(isset($recentStudents) && count($recentStudents) > 0)
                        <div class="space-y-4">
                            @foreach($recentStudents as $student)
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-medium text-gray-800">{{ $student->name }}</h5>
                                        <p class="text-xs text-gray-500">
                                            @if($student->classroom && $student->classroom->count() > 0)
                                                Kelas {{ $student->classroom->first()->name }}
                                            @else
                                                Belum ada kelas
                                            @endif
                                        </p>
                                    </div>
                                    <a href="{{ route('admin.users.edit', $student) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-blue-400 text-xl mb-2"></i>
                            <p class="text-gray-500">Belum ada data siswa</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Academic Management Section -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-800 flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg mr-3 shadow-inner">
                    <i class="fas fa-university text-purple-600"></i>
                </div>
                <span>Manajemen Akademis</span>
            </h3>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Classroom Management -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50">
                <div class="card-header p-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                    <div class="flex justify-between items-center">
                        <h4 class="text-md font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-school mr-2 text-purple-600"></i>
                            Kelas Terbaru
                        </h4>
                        <a href="{{ route('admin.classrooms.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium inline-flex items-center group">
                            <span>Lihat semua</span>
                            <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="p-5">
                    @if(isset($recentClassrooms) && count($recentClassrooms) > 0)
                        <div class="space-y-4">
                            @foreach($recentClassrooms as $classroom)
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-3">
                                        <i class="fas fa-chalkboard"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-medium text-gray-800">{{ $classroom->name }}</h5>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-users mr-1"></i> 
                                            <span>{{ $classroom->students->count() ?? 0 }} siswa</span>
                                            @if(isset($classroom->homeroomTeacher))
                                            <span class="mx-1">•</span>
                                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                                            <span>{{ $classroom->homeroomTeacher->name ?? 'Belum ada wali kelas' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-purple-400 text-xl mb-2"></i>
                            <p class="text-gray-500">Belum ada data kelas</p>
                            <a href="{{ route('admin.classrooms.create') }}" class="inline-block mt-3 text-sm text-white bg-purple-600 py-2 px-3 rounded-lg hover:bg-purple-700">
                                <i class="fas fa-plus mr-1"></i> Tambah Kelas
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50">
                <div class="card-header p-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-indigo-100">
                    <div class="flex justify-between items-center">
                        <h4 class="text-md font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-users mr-2 text-indigo-600"></i>
                            Pengguna Terbaru
                        </h4>
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center group">
                            <span>Lihat semua</span>
                            <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="p-5 space-y-4">
                    @if(isset($recentUsers) && count($recentUsers) > 0)
                        @foreach($recentUsers as $user)
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-all duration-200">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-10 h-10 rounded-full bg-{{ $user->role->slug == 'admin' ? 'red' : ($user->role->slug == 'guru' ? 'green' : 'blue') }}-100 flex items-center justify-center text-{{ $user->role->slug == 'admin' ? 'red' : ($user->role->slug == 'guru' ? 'green' : 'blue') }}-600">
                                        <i class="fas fa-{{ $user->role->slug == 'admin' ? 'user-shield' : ($user->role->slug == 'guru' ? 'chalkboard-teacher' : 'user-graduate') }}"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h5 class="text-sm font-medium text-gray-800">{{ $user->name }}</h5>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $user->role->slug == 'admin' ? 'bg-red-100 text-red-700' : ($user->role->slug == 'guru' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                                            {{ $user->role->name }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-indigo-400 text-xl mb-2"></i>
                            <p class="text-gray-500">Belum ada data pengguna</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div id="quick-actions" class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl shadow-md overflow-hidden mb-6 border border-gray-100/50">
        <div class="p-6 border-b border-gray-200/50 bg-white">
            <div class="flex items-center">
                <div class="p-2 bg-indigo-100 rounded-lg mr-3 shadow-inner">
                    <i class="fas fa-bolt text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-800">Aksi Cepat</h3>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100/50 hover:shadow transition-all transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4 shadow-inner">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Tambah Pengguna</h4>
                        <p class="text-xs text-gray-500 mt-1">Tambah admin, guru, atau siswa baru</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.classrooms.create') }}" class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100/50 hover:shadow transition-all transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-4 shadow-inner">
                        <i class="fas fa-school"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Tambah Kelas</h4>
                        <p class="text-xs text-gray-500 mt-1">Buat kelas baru dan tetapkan wali kelas</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.subjects.create') }}" class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100/50 hover:shadow transition-all transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-4 shadow-inner">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Tambah Mata Pelajaran</h4>
                        <p class="text-xs text-gray-500 mt-1">Buat mata pelajaran baru</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.schedule.create') }}" class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100/50 hover:shadow transition-all transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-4 shadow-inner">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Tambah Jadwal</h4>
                        <p class="text-xs text-gray-500 mt-1">Buat jadwal pelajaran baru</p>
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
                <span>Pengumuman Terbaru</span>
            </h3>
            <a href="{{ route('admin.announcements.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium inline-flex items-center group bg-red-50 px-3 py-1 rounded-lg hover:bg-red-100 transition-colors">
                <span>Lihat semua</span>
                <i class="fas fa-chevron-right ml-1 text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50">
            @if(isset($recentAnnouncements) && count($recentAnnouncements) > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($recentAnnouncements as $announcement)
                        <div class="p-6 hover:bg-red-50/20 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-md font-semibold text-gray-800 flex items-center">
                                    @if($announcement->is_important)
                                        <span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                                    @endif
                                    {{ $announcement->title }}
                                </h4>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $announcement->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ \Illuminate\Support\Str::limit($announcement->content, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="far fa-user mr-1"></i>
                                    <span>{{ $announcement->author ? $announcement->author->name : 'System' }}</span>
                                </div>
                                <div class="space-x-2">
                                    <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="{{ route('admin.announcements.show', $announcement->id) }}" class="text-gray-600 hover:text-gray-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <i class="fas fa-bullhorn text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-800 mb-2">Belum Ada Pengumuman</h4>
                    <p class="text-gray-500 mb-6">Buat pengumuman untuk disebarkan kepada guru dan siswa</p>
                    <a href="{{ route('admin.announcements.create') }}" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Pengumuman
                    </a>
                </div>
            @endif
            <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-between items-center">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                    Pengumuman akan ditampilkan di dashboard guru dan siswa
                </p>
                <a href="{{ route('admin.announcements.create') }}" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-sm hover:shadow">
                    <i class="fas fa-plus mr-1"></i> Buat Pengumuman
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
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
    
    @keyframes counter-line {
        0% { width: 0; left: 50%; opacity: 0; }
        50% { opacity: 1; }
        100% { width: 100%; left: 0; opacity: 0; }
    }
    
    .refresh-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    
    .refresh-overlay.active {
        opacity: 1;
        pointer-events: all;
    }
    
    .refresh-spinner {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #4338ca;
        border-right-color: #4338ca;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate counter numbers
        document.querySelectorAll('.counter').forEach(counter => {
            const value = parseInt(counter.textContent, 10);
            counter.textContent = '0';
            
            setTimeout(() => {
                animateCounter(counter, 0, value);
            }, 300);
        });
        
        // Refresh button functionality
        const refreshBtn = document.getElementById('refresh-data-btn');
        const refreshOverlay = document.getElementById('refresh-overlay');
        
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                this.classList.add('animate-spin');
                
                if (refreshOverlay) {
                    refreshOverlay.classList.add('active');
                }
                
                // Simulate data refresh (replace with actual AJAX call)
                setTimeout(() => {
                    this.classList.remove('animate-spin');
                    if (refreshOverlay) {
                        refreshOverlay.classList.remove('active');
                    }
                    
                    // Update the timestamp
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    this.nextElementSibling.textContent = `Terakhir diperbarui: ${hours}:${minutes}`;
                    
                    // Show a notification
                    showNotification('Data berhasil diperbarui', 'success');
                }, 1500);
            });
        }
    });
    
    function animateCounter(element, start, end) {
        const duration = 1500;
        const steps = 20;
        const stepValue = (end - start) / steps;
        const stepTime = duration / steps;
        let currentStep = 0;
        let currentValue = start;
        
        const interval = setInterval(() => {
            currentStep++;
            currentValue += stepValue;
            
            // Round to nearest integer and display
            element.textContent = Math.ceil(currentValue);
            
            if (currentStep >= steps) {
                clearInterval(interval);
                element.textContent = end;
            }
        }, stepTime);
    }
    
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white 
            ${type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-blue-600')}
            transform transition-all duration-300 translate-y-20 opacity-0 z-50 flex items-center`;
        
        // Add icon based on type
        const icon = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' : 'info-circle');
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${icon} mr-2 text-xl"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add to DOM
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-y-20', 'opacity-0');
        }, 10);
        
        // Remove after delay
        setTimeout(() => {
            notification.classList.add('translate-y-20', 'opacity-0');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
</script>
@endpush
