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
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-medium text-blue-600 mb-1">Total Siswa</h4>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-800 mr-2 counter" data-type="student">{{ $studentCount ?? 0 }}</span>
                        <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="text-xs text-blue-600 hover:underline">
                            Lihat semua
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
                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-medium text-green-600 mb-1">Total Guru</h4>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-800 mr-2 counter" data-type="teacher">{{ $teacherCount ?? 0 }}</span>
                        <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" class="text-xs text-green-600 hover:underline">
                            Lihat semua
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
                    <i class="fas fa-school text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-medium text-purple-600 mb-1">Total Kelas</h4>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-800 mr-2 counter" data-type="classroom">{{ $classroomCount ?? 0 }}</span>
                        <a href="{{ route('admin.classrooms.index') }}" class="text-xs text-purple-600 hover:underline">
                            Lihat semua
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
                    <i class="fas fa-book text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-sm font-medium text-amber-600 mb-1">Total Mata Pelajaran</h4>
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-gray-800 mr-2 counter" data-type="subject">{{ $subjectCount ?? 0 }}</span>
                        <a href="{{ route('admin.subjects.index') }}" class="text-xs text-amber-600 hover:underline">
                            Lihat semua
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
                    <i class="fas fa-users text-blue-600"></i>
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
                                        @if($teacher->avatar)
                                            <img src="{{ asset('storage/' . $teacher->avatar) }}" alt="{{ $teacher->name }}" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
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
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg duration-300">
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
                                        @if($student->avatar)
                                            <img src="{{ asset('storage/' . $student->avatar) }}" alt="{{ $student->name }}" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-medium text-gray-800">{{ $student->name }}</h5>
                                        <p class="text-xs text-gray-500">
                                            @if($student->classroom)
                                                Kelas {{ $student->classroom->name }}
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
    
    <!-- Classroom and Subject Integration -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-800 flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg mr-3 shadow-inner">
                    <i class="fas fa-university text-purple-600"></i>
                </div>
                <span class="text-shadow-sm">Manajemen Akademis</span>
            </h3>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Classroom Management -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg duration-300">
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
                        <div class="space-y-4 classroom-list-container">
                            @foreach($recentClassrooms as $index => $classroom)
                                <div class="classroom-item flex items-center hover:bg-purple-50/40 p-3 rounded-lg transition-colors" data-classroom-id="{{ $classroom->id }}">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-3">
                                        <i class="fas fa-chalkboard"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-medium text-gray-800">{{ $classroom->name }}</h5>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-users mr-1"></i> 
                                            <span>{{ $classroom->students_count ?? 0 }} siswa</span>
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
            
            <!-- Subject Management -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg duration-300">
                <div class="card-header p-5 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-amber-100">
                    <div class="flex justify-between items-center">
                        <h4 class="text-md font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-book mr-2 text-amber-600"></i>
                            Mata Pelajaran Terbaru
                        </h4>
                        <a href="{{ route('admin.subjects.index') }}" class="text-sm text-amber-600 hover:text-amber-800 font-medium inline-flex items-center group">
                            <span>Lihat semua</span>
                            <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="p-5">
                    @if(isset($recentSubjects) && count($recentSubjects) > 0)
                        <div class="space-y-4 subject-list-container">
                            @foreach($recentSubjects as $index => $subject)
                                <div class="subject-item flex items-center hover:bg-amber-50/40 p-3 rounded-lg transition-colors" data-subject-id="{{ $subject->id }}">
                                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-3">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-medium text-gray-800">{{ $subject->name }}</h5>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                                            <span>{{ $subject->teachers_count ?? 0 }} guru pengajar</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="text-amber-600 hover:text-amber-800">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-amber-400 text-xl mb-2"></i>
                            <p class="text-gray-500">Belum ada data mata pelajaran</p>
                            <a href="{{ route('admin.subjects.create') }}" class="inline-block mt-3 text-sm text-white bg-amber-600 py-2 px-3 rounded-lg hover:bg-amber-700">
                                <i class="fas fa-plus mr-1"></i> Tambah Mata Pelajaran
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- User List with real data -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg">
            <div class="card-header flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-indigo-50">
                <h4 class="text-md font-semibold text-gray-700 flex items-center">
                    <i class="fas fa-users mr-2 text-indigo-600"></i>
                    Pengguna Terbaru
                </h4>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center group">
                    <span>Lihat semua pengguna</span>
                    <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
            
            <div class="divide-y divide-gray-100 user-list-container">
                @if(isset($recentUsers) && count($recentUsers) > 0)
                    @foreach($recentUsers as $user)
                        <div class="user-item flex items-center p-5 hover:bg-gray-50/50 transition-colors">
                            <div class="flex-shrink-0 mr-4">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-{{ $user->role->slug == 'admin' ? 'red' : ($user->role->slug == 'guru' ? 'green' : 'blue') }}-100 flex items-center justify-center text-{{ $user->role->slug == 'admin' ? 'red' : ($user->role->slug == 'guru' ? 'green' : 'blue') }}-600 shadow">
                                        <i class="fas fa-{{ $user->role->slug == 'admin' ? 'user-shield' : ($user->role->slug == 'guru' ? 'chalkboard-teacher' : 'user-graduate') }}"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center justify-between">
                                    <h5 class="text-sm font-medium text-gray-800">{{ $user->name }}</h5>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $user->role->slug == 'admin' ? 'bg-red-100 text-red-700' : ($user->role->slug == 'guru' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                                        {{ $user->role->name }}
                                    </span>
                                </div>
                                <div class="mt-1 text-xs text-gray-500 flex items-center">
                                    <i class="far fa-envelope mr-1"></i>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            
                            <div class="ml-4">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-gray-500 hover:text-blue-600 mr-2" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-6 text-center">
                        <p class="text-gray-500">Belum ada data pengguna</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- Activity Log with real data -->
        <div id="shortcuts" class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg">
            <div class="card-header p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-blue-50">
                <div class="flex items-center justify-between">
                    <h4 class="text-md font-semibold text-gray-700 flex items-center">
                        <i class="fas fa-history mr-2 text-blue-600"></i>
                        Aktivitas Terbaru
                    </h4>
                    <button id="refresh-activities-btn" class="text-blue-600 hover:text-blue-800" title="Refresh activities">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            
            <div class="divide-y divide-gray-100 max-h-[400px] overflow-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 activity-list-container">
                @if(isset($recentActivities) && count($recentActivities) > 0)
                    @foreach($recentActivities as $index => $activity)
                        <div class="activity-item p-4 hover:bg-gray-50/50 transition-colors" data-activity-id="{{ $activity->id }}">
                            <div class="flex items-start">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 
                                    @if($activity->type == 'login')
                                        bg-green-100 text-green-600
                                    @elseif($activity->type == 'user_created')
                                        bg-blue-100 text-blue-600
                                    @elseif($activity->type == 'user_updated')
                                        bg-amber-100 text-amber-600
                                    @elseif($activity->type == 'user_deleted' || $activity->type == 'class_deleted' || $activity->type == 'subject_deleted')
                                        bg-red-100 text-red-600
                                    @elseif($activity->type == 'class_created' || $activity->type == 'class_updated')
                                        bg-purple-100 text-purple-600
                                    @elseif($activity->type == 'subject_created' || $activity->type == 'subject_updated')
                                        bg-amber-100 text-amber-600
                                    @elseif($activity->type == 'logout')
                                        bg-gray-100 text-gray-600
                                    @else
                                        bg-indigo-100 text-indigo-600
                                    @endif
                                ">
                                    <i class="fas fa-{{ 
                                        $activity->type == 'login' ? 'sign-in-alt' : 
                                        ($activity->type == 'logout' ? 'sign-out-alt' : 
                                        ($activity->type == 'user_created' ? 'user-plus' : 
                                        ($activity->type == 'user_updated' ? 'user-edit' : 
                                        ($activity->type == 'user_deleted' ? 'user-minus' : 
                                        ($activity->type == 'class_created' || $activity->type == 'class_updated' ? 'school' : 
                                        ($activity->type == 'subject_created' || $activity->type == 'subject_updated' ? 'book' : 'info-circle')))))) 
                                    }}"></i>
                                </div>
                                
                                <div class="flex-1">
                                    <p class="text-sm text-gray-700">{{ $activity->description }}</p>
                                    <div class="flex items-center mt-1 text-xs text-gray-500">
                                        <i class="far fa-clock mr-1"></i>
                                        <span>{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</span>
                                        @if(isset($activity->user))
                                            <span class="mx-1">•</span>
                                            <i class="far fa-user mr-1"></i>
                                            <span>{{ $activity->user->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-6 text-center">
                        <div class="mb-3 bg-blue-100 rounded-full h-12 w-12 flex items-center justify-center mx-auto text-blue-500">
                            <i class="fas fa-info-circle text-xl"></i>
                        </div>
                        <p class="text-gray-500">Tidak ada aktivitas terbaru untuk ditampilkan</p>
                        <p class="text-sm text-gray-400 mt-1">Aktivitas akan muncul saat pengguna melakukan tindakan di sistem</p>
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
                <h3 class="text-lg font-medium text-gray-800">Aksi Cepat</h3>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Add User -->
                <a href="{{ route('admin.users.create') }}" class="quick-action flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100/50 hover:shadow transition-all transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4 shadow-inner">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Tambah Pengguna</h4>
                        <p class="text-xs text-gray-500 mt-1">Tambah admin, guru, atau siswa baru</p>
                    </div>
                </a>
                
                <!-- Add Classroom -->
                <a href="{{ route('admin.classrooms.create') }}" class="quick-action flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100/50 hover:shadow transition-all transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-4 shadow-inner">
                        <i class="fas fa-school"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Tambah Kelas</h4>
                        <p class="text-xs text-gray-500 mt-1">Buat kelas baru dan tetapkan wali kelas</p>
                    </div>
                </a>
                
                <!-- Add Subject -->
                <a href="{{ route('admin.subjects.create') }}" class="quick-action flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100/50 hover:shadow transition-all transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-4 shadow-inner">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Tambah Mata Pelajaran</h4>
                        <p class="text-xs text-gray-500 mt-1">Buat mata pelajaran baru</p>
                    </div>
                </a>
                
                <!-- Create Announcement -->
                <a href="{{ route('admin.announcements.create') }}" class="quick-action flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100/50 hover:shadow transition-all transform hover:-translate-y-1 duration-300">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-4 shadow-inner">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Buat Pengumuman</h4>
                        <p class="text-xs text-gray-500 mt-1">Buat dan publikasikan pengumuman baru</p>
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
                <span>Lihat semua</span>
                <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100/50 transform transition hover:shadow-lg">
            @if(isset($recentAnnouncements) && (is_object($recentAnnouncements) ? $recentAnnouncements->count() > 0 : count($recentAnnouncements) > 0))
                <div class="divide-y divide-gray-100 announcement-list-container">
                    @foreach($recentAnnouncements as $announcement)
                        <div class="announcement-item p-6 hover:bg-red-50/20 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-md font-semibold text-gray-800 flex items-center">
                                    @if($announcement->is_important)
                                        <span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                                    @endif
                                    {{ $announcement->title }}
                                </h4>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $announcement->formatted_date }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ $announcement->excerpt }}</p>
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
                    <i class="fas fa-info-circle mr-1 text-red-500"></i>
                    Pengumuman akan ditampilkan di dashboard Guru dan Siswa
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
    
    /* Refresh animation */
    .animate-spin {
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
    
    /* Animation for new activity items */
    .activity-item.new-activity {
        animation: highlight-activity 2s ease-in-out;
    }
    
    @keyframes highlight-activity {
        0% {
            background-color: rgba(96, 165, 250, 0.2);
            transform: translateY(0);
        }
        50% {
            background-color: rgba(96, 165, 250, 0.1);
        }
        100% {
            background-color: transparent;
        }
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
        const refreshBtn = document.getElementById('refresh-data-btn');
        const refreshOverlay = document.getElementById('refresh-overlay');
        
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                // Show refresh overlay with minimal duration
                showRefreshOverlay();
                
                // Fetch updated stats
                fetchUpdatedStats();
                
                // Add spin animation to refresh button
                this.classList.add('animate-spin');
                setTimeout(() => {
                    this.classList.remove('animate-spin');
                }, 1000);
            });
        }
        
        // Setup auto-refresh every 5 minutes
        setInterval(fetchUpdatedStats, 5 * 60 * 1000);
    });
    
    // Function to show refresh overlay
    function showRefreshOverlay() {
        const overlay = document.getElementById('refresh-overlay');
        if (overlay) {
            // Add spinner if not already present
            if (!overlay.querySelector('.refresh-spinner')) {
                const spinnerContainer = overlay.querySelector('.bg-white');
                if (spinnerContainer) {
                    const spinner = document.createElement('div');
                    spinner.className = 'refresh-spinner mb-4';
                    const text = document.createElement('p');
                    text.className = 'text-gray-700 font-medium';
                    text.textContent = 'Memperbarui data...';
                    
                    spinnerContainer.appendChild(spinner);
                    spinnerContainer.appendChild(text);
                }
            }
            
            overlay.classList.add('active');
        }
    }
    
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
            element.textContent = Math.round(currentValue);
            
            if (currentStep >= steps) {
                clearInterval(interval);
                element.textContent = end;
            }
        }, stepTime);
    }
    
    function animateCounters() {
        document.querySelectorAll('.counter').forEach(counter => {
            const value = parseInt(counter.textContent, 10);
            counter.textContent = '0';
            
            setTimeout(() => {
                animateCounter(counter, 0, value);
            }, 300);
        });
    }
      // Function to fetch updated statistics via AJAX
    function fetchUpdatedStats() {
        // Show loading indicator
        const refreshBtn = document.getElementById('refresh-data-btn');
        if (refreshBtn) {
            refreshBtn.classList.add('animate-spin');
        }
        
        // Show refresh overlay
        showRefreshOverlay();
        
        return fetch('{{ route("admin.dashboard.stats") }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update all the data sections
                updateCounters(data);
                updateLastUpdated();
                
                // Update recent lists if provided with enhanced handling for empty states
                if (data.recentUsers) updateRecentUsersList(data.recentUsers);
                if (data.recentClassrooms) updateRecentClassroomsList(data.recentClassrooms);
                if (data.recentSubjects) updateRecentSubjectsList(data.recentSubjects);
                if (data.recentAnnouncements) updateAnnouncementsList(data.recentAnnouncements);
                if (data.recentActivities) updateActivityList(data.recentActivities);
                
                // Show success notification
                showNotification('Data berhasil diperbarui', 'success');
                return data; // Return data for chaining
            })
            .catch(error => {
                console.error('Error fetching updated stats:', error);
                showNotification('Gagal memperbarui data', 'error');
                throw error; // Re-throw for proper error handling
            })
            .finally(() => {
                // Hide loading indicators
                if (refreshBtn) {
                    refreshBtn.classList.remove('animate-spin');
                }
                const overlay = document.getElementById('refresh-overlay');
                if (overlay) {
                    overlay.classList.remove('active');
                }
            });
    }
    
    function updateCounters(data) {
        // Update counter values and animate them
        const counters = {
            "student": data.studentCount,
            "teacher": data.teacherCount,
            "classroom": data.classroomCount,
            "subject": data.subjectCount
        };
        
        Object.keys(counters).forEach(type => {
            const counterElement = document.querySelector(`[data-type="${type}"]`);
            if (counterElement) {
                const oldValue = parseInt(counterElement.textContent, 10) || 0;
                const newValue = counters[type];
                
                if (oldValue !== newValue) {
                    // Animate the counter
                    animateCounter(counterElement, oldValue, newValue);
                }
            }
        });
    }
    
    function updateLastUpdated() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        
        const refreshBtn = document.getElementById('refresh-data-btn');
        if (refreshBtn && refreshBtn.nextElementSibling) {
            refreshBtn.nextElementSibling.textContent = `Terakhir diperbarui: ${hours}:${minutes}`;
        }
    }
      function updateRecentUsersList(users) {
        const userList = document.querySelector('.user-list-container');
        if (!userList) return;
        
        // Check if the container has a parent with the class card-header
        const hasParentCard = userList.closest('.card-header') !== null;
        
        userList.innerHTML = '';
        if (!users || users.length === 0) {
            userList.innerHTML = `
                <div class="py-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 w-16 rounded-full bg-indigo-100 text-indigo-500 mb-4">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">Belum ada pengguna yang terdaftar.</p>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Tambah Pengguna
                    </a>
                </div>
            `;
            return;
        }
        
        // Sort users by creation date (newest first)
        const sortedUsers = [...users].sort((a, b) => {
            return new Date(b.created_at || Date.now()) - new Date(a.created_at || Date.now());
        });
        
        sortedUsers.forEach((user, index) => {
            if (!user || !user.role) {
                console.error('Invalid user data:', user);
                return; // Skip this iteration if user data is invalid
            }

            const roleColor = user.role.slug === 'admin' ? 'indigo' : (user.role.slug === 'guru' ? 'green' : 'blue');
            const roleIcon = user.role.slug === 'admin' ? 'user-shield' : (user.role.slug === 'guru' ? 'chalkboard-teacher' : 'user-graduate');
            
            const userItem = document.createElement('div');
            userItem.className = `flex items-center py-4 px-6 hover:bg-indigo-50/30 transition-colors duration-150 animate-item`;
            userItem.style.animationDelay = `${index * 100}ms`;
            
            // Enhanced check for new users - consider them new if created in the last hour
            const isNewUser = user.created_at && (
                new Date(user.created_at).toDateString() === new Date().toDateString() &&
                (new Date() - new Date(user.created_at)) < 24 * 60 * 60 * 1000 // within 24 hours
            );
            
            const newBadge = isNewUser ? 
                `<span class="ml-2 px-1.5 py-0.5 bg-green-100 text-green-800 rounded-md text-xs font-medium">Baru</span>` : 
                '';
            
            userItem.innerHTML = `
                <div class="flex-shrink-0">
                    ${user.avatar ? 
                        `<img src="${user.avatar}" alt="${user.name}" class="h-12 w-12 rounded-xl object-cover shadow-sm border border-gray-200">` : 
                        `<div class="h-12 w-12 rounded-xl bg-gradient-to-br from-${roleColor}-400 to-${roleColor}-600 flex items-center justify-center text-white font-bold shadow-sm">
                            ${user.name ? user.name.substring(0, 2).toUpperCase() : 'NA'}
                         </div>`
                    }
                </div>
                <div class="ml-4 flex-1">
                    <div class="text-sm font-medium text-gray-900 flex items-center">
                        ${user.name || 'Unnamed User'}
                        ${newBadge}
                    </div>
                    <div class="text-sm text-gray-500">
                        ${user.email || 'No email provided'}
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-${roleColor}-100 text-${roleColor}-800 border border-${roleColor}-200">
                    <i class="fas fa-${roleIcon} mr-1"></i>
                    ${user.role.name || 'Unknown Role'}
                </span>
            `;
            userList.appendChild(userItem);
            
            // Apply a highlight effect to new items
            if (isNewUser) {
                setTimeout(() => {
                    userItem.classList.add('bg-indigo-50/40');
                    setTimeout(() => {
                        userItem.classList.remove('bg-indigo-50/40');
                    }, 3000);
                }, 300);
            }
        });
    }
      function updateRecentClassroomsList(classrooms) {
        const classroomList = document.querySelector('.classroom-list-container');
        if (!classroomList) return;
        
        // Get existing classroom IDs to identify new ones
        const existingIds = Array.from(classroomList.querySelectorAll('.classroom-item'))
            .map(item => item.dataset.classroomId)
            .filter(id => id);
        
        // For a full refresh or empty list, clear the container
        if (!existingIds.length) {
            classroomList.innerHTML = '';
        }
        
        if (!classrooms || classrooms.length === 0) {
            classroomList.innerHTML = `
                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 text-purple-500 mb-4">
                        <i class="fas fa-school text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">Belum ada kelas yang terdaftar.</p>
                    <a href="{{ route('admin.classrooms.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Tambah Kelas
                    </a>
                </div>
            `;
            return;
        }
        
        // Sort classrooms by creation date (newest first)
        const sortedClassrooms = [...classrooms].sort((a, b) => {
            return new Date(b.created_at || Date.now()) - new Date(a.created_at || Date.now());
        });
        
        // If this isn't a full refresh, only add new classrooms
        if (existingIds.length > 0 && existingIds.length < classrooms.length) {
            // Find new classrooms that aren't in the existing list
            const newClassrooms = sortedClassrooms.filter(classroom => 
                !existingIds.includes(classroom.id?.toString())
            );
            
            // Add only new classrooms at the top
            newClassrooms.forEach((classroom, index) => {
                createClassroomItem(classroom, index, classroomList, true);
            });
        } else {
            // Full refresh - clear and add all
            classroomList.innerHTML = '';
            sortedClassrooms.forEach((classroom, index) => {
                createClassroomItem(classroom, index, classroomList);
            });
        }
        
        function createClassroomItem(classroom, index, container, isNewAddition = false) {
            if (!classroom || !classroom.id) {
                console.error('Invalid classroom data:', classroom);
                return; // Skip this iteration if classroom data is invalid
            }
            
            const classroomItem = document.createElement('div');
            classroomItem.className = "classroom-item bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-all hover:bg-purple-50/30 hover:-translate-y-0.5 transform duration-300";
            classroomItem.dataset.classroomId = classroom.id;
            
            // Check if this is a newly created classroom (within the last 24 hours)
            const isNewClassroom = classroom.created_at && (
                (new Date() - new Date(classroom.created_at)) < 24 * 60 * 60 * 1000 // within 24 hours
            );
            
            // Add animation delay based on index
            if (index < 10) {
                classroomItem.style.animationDelay = `${index * 100}ms`;
                if (isNewClassroom || isNewAddition) {
                    classroomItem.classList.add('animate-item');
                }
            }
            
            const namePrefix = classroom.name ? classroom.name.substring(0, 2) : 'CL';
            
            classroomItem.innerHTML = `
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-2 rounded-lg mr-3 text-white shadow-sm">
                            <span class="font-bold">${namePrefix}</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-800 flex items-center">
                                ${classroom.name}
                                ${isNewClassroom ? 
                                    '<span class="ml-2 px-1.5 py-0.5 bg-green-100 text-green-800 rounded-md text-xs font-medium">Baru</span>' : 
                                    ''}
                            </h4>
                            <p class="text-xs text-gray-500">
                                ${classroom.students_count || 0} siswa
                                ${classroom.homeroom_teacher ? 
                                  ` · Wali kelas: ${classroom.homeroom_teacher.name}` : 
                                  ''}
                            </p>
                        </div>
                    </div>                    <a href="/admin/classrooms/${classroom.id}/edit" class="text-purple-600 hover:text-purple-900 bg-purple-50/50 hover:bg-purple-100 p-1 px-2 rounded-md transition-colors">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            `;
            
            // Place new items at the top
            if (isNewAddition && container.firstChild) {
                container.insertBefore(classroomItem, container.firstChild);
            } else {
                container.appendChild(classroomItem);
            }
            
            // Apply a highlight effect to new items
            if (isNewClassroom || isNewAddition) {
                setTimeout(() => {
                    classroomItem.classList.add('bg-purple-100/60');
                    setTimeout(() => {
                        classroomItem.classList.remove('bg-purple-100/60');
                        classroomItem.classList.add('bg-white');
                    }, 3000);
                }, 300);
            }
        }
    }
      function updateRecentSubjectsList(subjects) {
        const subjectList = document.querySelector('.subject-list-container');
        if (!subjectList) return;
        
        // Get existing subject IDs to identify new ones
        const existingIds = Array.from(subjectList.querySelectorAll('.subject-item'))
            .map(item => item.dataset.subjectId)
            .filter(id => id);
        
        // For a full refresh or empty list, clear the container
        if (!existingIds.length) {
            subjectList.innerHTML = '';
        }
        
        if (!subjects || subjects.length === 0) {
            subjectList.innerHTML = `
                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 text-amber-500 mb-4">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">Belum ada mata pelajaran yang terdaftar.</p>
                    <a href="{{ route('admin.subjects.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Tambah Mata Pelajaran
                    </a>
                </div>
            `;
            return;
        }
        
        // Sort subjects by creation date (newest first)
        const sortedSubjects = [...subjects].sort((a, b) => {
            return new Date(b.created_at || Date.now()) - new Date(a.created_at || Date.now());
        });
        
        // If this isn't a full refresh, only add new subjects
        if (existingIds.length > 0 && existingIds.length < subjects.length) {
            // Find new subjects that aren't in the existing list
            const newSubjects = sortedSubjects.filter(subject => 
                !existingIds.includes(subject.id?.toString())
            );
            
            // Add only new subjects at the top
            newSubjects.forEach((subject, index) => {
                createSubjectItem(subject, index, subjectList, true);
            });
        } else {
            // Full refresh - clear and add all
            subjectList.innerHTML = '';
            sortedSubjects.forEach((subject, index) => {
                createSubjectItem(subject, index, subjectList);
            });
        }
        
        function createSubjectItem(subject, index, container, isNewAddition = false) {
            if (!subject || !subject.id) {
                console.error('Invalid subject data:', subject);
                return; // Skip this iteration if subject data is invalid
            }
            
            const subjectItem = document.createElement('div');
            subjectItem.className = "subject-item bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition-all hover:bg-amber-50/30 hover:-translate-y-0.5 transform duration-300";
            subjectItem.dataset.subjectId = subject.id;
            
            // Check if this is a newly created subject (within the last 24 hours)
            const isNewSubject = subject.created_at && (
                (new Date() - new Date(subject.created_at)) < 24 * 60 * 60 * 1000 // within 24 hours
            );
            
            // Add animation delay based on index
            if (index < 10) {
                subjectItem.style.animationDelay = `${index * 100}ms`;
                if (isNewSubject || isNewAddition) {
                    subjectItem.classList.add('animate-item');
                }
            }
            
            // Create a short abbreviation for the subject
            const subjectInitials = subject.name 
                ? subject.name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase()
                : 'MP';
            
            subjectItem.innerHTML = `
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-br from-amber-400 to-amber-600 p-2 rounded-lg mr-3 text-white shadow-sm">
                            <span class="font-bold">${subjectInitials}</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-800 flex items-center">
                                ${subject.name}
                                ${isNewSubject ? 
                                    '<span class="ml-2 px-1.5 py-0.5 bg-green-100 text-green-800 rounded-md text-xs font-medium">Baru</span>' : 
                                    ''}
                            </h4>
                            <p class="text-xs text-gray-500">
                                ${subject.teachers_count || 0} guru pengajar
                            </p>
                        </div>
                    </div>                    <a href="/admin/subjects/${subject.id}/edit" class="text-amber-600 hover:text-amber-900 bg-amber-50/50 hover:bg-amber-100 p-1 px-2 rounded-md transition-colors">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            `;
            
            // Place new items at the top
            if (isNewAddition && container.firstChild) {
                container.insertBefore(subjectItem, container.firstChild);
            } else {
                container.appendChild(subjectItem);
            }
            
            // Apply a highlight effect to new items
            if (isNewSubject || isNewAddition) {
                setTimeout(() => {
                    subjectItem.classList.add('bg-amber-100/60');
                    setTimeout(() => {
                        subjectItem.classList.remove('bg-amber-100/60');
                        subjectItem.classList.add('bg-white');
                    }, 3000);
                }, 300);
            }
        }
    }
      function updateAnnouncementsList(announcements) {
        const announcementsList = document.querySelector('.announcement-list-container');
        if (!announcementsList) return;
        
        // Get existing announcement IDs to identify new ones
        const existingIds = Array.from(announcementsList.querySelectorAll('.announcement-item'))
            .map(item => item.dataset.announcementId)
            .filter(id => id);
        
        // If no announcements, show empty state
        if (!announcements || announcements.length === 0) {
            announcementsList.parentElement.innerHTML = `
                <div class="py-10 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-500 mb-4">
                        <i class="fas fa-bullhorn text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">Belum ada pengumuman yang dipublikasikan.</p>
                    <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Buat Pengumuman
                    </a>
                </div>
            `;
            return;
        }
        
        // Sort announcements by creation date (newest first)
        const sortedAnnouncements = [...announcements].sort((a, b) => {
            // First sort by importance, then by date
            if (a.is_important !== b.is_important) {
                return a.is_important ? -1 : 1; // Important announcements first
            }
            return new Date(b.created_at || b.publish_date || Date.now()) - 
                   new Date(a.created_at || a.publish_date || Date.now());
        });
        
        // If this isn't a full refresh, only add new announcements
        if (existingIds.length > 0 && existingIds.length < announcements.length) {
            // Find new announcements that aren't in the existing list
            const newAnnouncements = sortedAnnouncements.filter(announcement => 
                !existingIds.includes(announcement.id?.toString())
            );
            
            // Add only new announcements at the top
            newAnnouncements.forEach((announcement, index) => {
                createAnnouncementItem(announcement, index, announcementsList, true);
            });
        } else {
            // Full refresh - clear and add all
            announcementsList.innerHTML = '';
            sortedAnnouncements.forEach((announcement, index) => {
                createAnnouncementItem(announcement, index, announcementsList);
            });
        }
        
        function createAnnouncementItem(announcement, index, container, isNewAddition = false) {
            if (!announcement || !announcement.id) {
                console.error('Invalid announcement data:', announcement);
                return;
            }
            
            const announcementItem = document.createElement('div');
            announcementItem.className = `announcement-item p-5 hover:bg-red-50/20 transition-all duration-300 ${announcement.is_important ? 'bg-red-50' : ''}`;
            announcementItem.dataset.announcementId = announcement.id;
            
            // Check if this is a newly created announcement (within the last 24 hours)
            const publishDate = new Date(announcement.publish_date || announcement.created_at || Date.now());
            const isNewAnnouncement = (new Date() - publishDate) < 24 * 60 * 60 * 1000; // within 24 hours
            
            // Add animation for new items
            if (isNewAnnouncement || isNewAddition) {
                announcementItem.classList.add('animate-item');
                announcementItem.style.animationDelay = `${index * 100}ms`;
            }
            
            // Format the date properly
            const formattedDate = announcement.formatted_date || 
                publishDate.toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'});
            
            announcementItem.innerHTML = `
                <div class="flex items-start">
                    <div class="mr-4 flex-shrink-0">
                        <div class="p-2 rounded-lg ${announcement.is_important ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600'}">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="text-base font-medium text-gray-900 flex items-center">
                                ${announcement.title}
                                ${announcement.is_important ? 
                                    '<span class="ml-2 px-1.5 py-0.5 bg-red-100 text-red-800 rounded-full text-xs font-medium">Penting</span>' : 
                                    ''}
                                ${isNewAnnouncement && !announcement.is_important ? 
                                    '<span class="ml-2 px-1.5 py-0.5 bg-green-100 text-green-800 rounded-md text-xs font-medium">Baru</span>' : 
                                    ''}
                            </h4>
                            <span class="text-xs text-gray-500">${formattedDate}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">
                            ${announcement.excerpt || (announcement.content ? announcement.content.substring(0, 120) + (announcement.content.length > 120 ? '...' : '') : '')}
                        </p>
                        <div class="flex items-center justify-between mt-2">                            <a href="/admin/announcements/${announcement.id}" class="text-xs text-red-600 hover:text-red-800 flex items-center">
                                <span>Lihat pengumuman</span>
                                <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                            <span class="text-xs text-gray-500 flex items-center">
                                <i class="fas fa-user mr-1"></i>
                                ${announcement.author ? announcement.author.name : 'System'}
                            </span>
                        </div>
                    </div>
                </div>
            `;
            
            // Place new items at the top
            if (isNewAddition && container.firstChild) {
                container.insertBefore(announcementItem, container.firstChild);
            } else {
                container.appendChild(announcementItem);
            }
            
            // Apply a highlight effect to new items
            if (isNewAnnouncement || isNewAddition) {
                setTimeout(() => {
                    if (!announcement.is_important) { // Don't add highlight to already highlighted important announcements
                        announcementItem.classList.add('bg-red-50/60');
                        setTimeout(() => {
                            announcementItem.classList.remove('bg-red-50/60');
                            if (!announcement.is_important) {
                                announcementItem.classList.add('bg-white');
                            }
                        }, 3000);
                    }
                }, 300);
            }
        }
    }
      // Update activity list function
    function updateActivityList(activities) {
        if (!activities || !activities.length) return;
        
        const activityContainer = document.querySelector('.activity-list-container');
        if (!activityContainer) return;
        
        // Get existing activity IDs to identify new ones
        const existingIds = Array.from(activityContainer.querySelectorAll('.activity-item'))
            .map(item => item.dataset.activityId)
            .filter(id => id);
        
        // For a full refresh, clear the container
        if (activities.length > 5 || !existingIds.length) {
            activityContainer.innerHTML = '';
            // If there's a "no activities" message displayed, clear that too
            const emptyState = activityContainer.querySelector('.py-12');
            if (emptyState) {
                activityContainer.innerHTML = '';
            }
        }
        
        // Activity icon and color mapping
        const iconMap = {
            'login': 'sign-in-alt',
            'logout': 'sign-out-alt',
            'user_created': 'user-plus',
            'user_updated': 'user-edit',
            'user_deleted': 'user-minus',
            'profile_updated': 'id-card',
            'password_changed': 'key',
            'refresh': 'sync',
            'system': 'cog',
            'announcement': 'bullhorn',
            'schedule_update': 'calendar-alt',
            'subject': 'book',
            'classroom': 'chalkboard',
            'assignment': 'tasks',
            'submission': 'file-upload',
            'grade': 'star',
            'error': 'exclamation-triangle'
        };
        
        const colorMap = {
            'login': 'blue',
            'logout': 'gray',
            'user_created': 'green',
            'user_updated': 'indigo',
            'user_deleted': 'red',
            'profile_updated': 'purple',
            'password_changed': 'yellow',
            'refresh': 'cyan',
            'system': 'gray',
            'announcement': 'amber',
            'schedule_update': 'indigo',
            'subject': 'emerald',
            'classroom': 'violet',
            'assignment': 'lime',
            'submission': 'sky',
            'grade': 'amber',
            'error': 'red'
        };
        
        // Create document fragment for better performance
        const fragment = document.createDocumentFragment();
        
        // Start with newest activities at the top
        activities.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        
        activities.forEach(activity => {
            // Skip if the element already exists
            if (existingIds.includes(activity.id?.toString())) {
                return;
            }
            
            const icon = iconMap[activity.type] || 'bell';
            const color = colorMap[activity.type] || 'blue';
            const createdAtFormatted = formatActivityTime(activity.created_at);
            
            const activityItem = document.createElement('div');
            activityItem.className = 'activity-item flex items-center py-3 px-6 hover:bg-blue-50/20 transition-all duration-150 hover:-translate-y-0.5 transform animate-item new-activity';
            activityItem.dataset.activityId = activity.id;
            
            activityItem.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="h-9 w-9 rounded-full bg-${color}-100 flex items-center justify-center text-${color}-700 border border-${color}-200">
                        <i class="fas fa-${icon} text-sm"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm text-gray-800">
                        ${activity.description}
                    </p>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <i class="far fa-clock mr-1"></i>
                        <span>${createdAtFormatted}</span>
                        ${activity.user ? `
                            <span class="mx-1">•</span>
                            <i class="far fa-user mr-1"></i>
                            <span>${activity.user.name}</span>
                        ` : ''}
                    </div>
                </div>
            `;
            
            // Add new activity at the top
            fragment.prepend(activityItem);
        });
        
        // Insert the new activities at the top
        if (fragment.children && fragment.children.length > 0) {
            activityContainer.insertBefore(fragment, activityContainer.firstChild);
            
            // Highlight new activities with animation
            setTimeout(() => {
                Array.from(activityContainer.querySelectorAll('.new-activity')).forEach(item => {
                    item.classList.remove('new-activity');
                });
            }, 2000);
        }
    }
    
    // Helper function to format activity time in a human-friendly way
    function formatActivityTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffSec = Math.floor(diffMs / 1000);
        const diffMin = Math.floor(diffSec / 60);
        const diffHour = Math.floor(diffMin / 60);
        const diffDay = Math.floor(diffHour / 24);
        
        if (diffSec < 60) return 'baru saja';
        if (diffMin < 60) return `${diffMin} menit yang lalu`;
        if (diffHour < 24) return `${diffHour} jam yang lalu`;
        if (diffDay < 7) return `${diffDay} hari yang lalu`;
        
        return date.toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'});
    }
    
    // Show notification function
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
                <i class="fas fa-${icon} mr-2 text-white text-xl"></i>
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
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Function to fetch and refresh only the activities
    function refreshActivities() {
        const refreshBtn = document.getElementById('refresh-activities-btn');
        if (refreshBtn) {
            refreshBtn.classList.add('animate-spin');
        }
        
        fetch('{{ route("admin.dashboard.activities") }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.recentActivities) {
                    updateActivityList(data.recentActivities);
                    showNotification('Aktivitas berhasil diperbarui', 'success');
                }
            })
            .catch(error => {
                console.error('Error fetching activities:', error);
                showNotification('Gagal memperbarui aktivitas', 'error');
            })
            .finally(() => {
                if (refreshBtn) {
                    refreshBtn.classList.remove('animate-spin');
                }
            });
    }
    
    // Setup activity refresh button and auto-refresh
    document.addEventListener('DOMContentLoaded', function() {
        const refreshActivitiesBtn = document.getElementById('refresh-activities-btn');
        if (refreshActivitiesBtn) {
            refreshActivitiesBtn.addEventListener('click', function() {
                refreshActivities();
            });
        }
        
        // Auto-refresh activities every 30 seconds
        setInterval(refreshActivities, 30 * 1000);
    });
    
    // Create floating particles effect
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize counters animation on page load
        animateCounters();
        
        // Setup refresh button
        const refreshBtn = document.getElementById('refresh-data-btn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                showRefreshOverlay();
                fetchUpdatedStats();
            });
        }
        
        // Setup activity refresh button
        const refreshActivitiesBtn = document.getElementById('refresh-activities-btn');
        if (refreshActivitiesBtn) {
            refreshActivitiesBtn.addEventListener('click', function() {
                refreshActivities();
            });
        }
        
        // Auto-refresh data periodically (every 5 minutes)
        setInterval(fetchUpdatedStats, 5 * 60 * 1000);
        
        // Auto-refresh activities more frequently (every 30 seconds)
        setInterval(refreshActivities, 30 * 1000);
    });
</script>
@endpush
