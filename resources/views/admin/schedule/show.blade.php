@extends('layouts.dashboard')

@section('title', 'Detail Jadwal')

@section('header', 'Detail Jadwal Pelajaran')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.schedule.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-medium transition-all duration-200 group">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform duration-200"></i> Kembali ke Daftar Jadwal
        </a>
    </div>
    
    <!-- Schedule Details -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200/80">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">Detail Jadwal</h3>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.schedule.edit', $schedule) }}" class="px-4 py-2 bg-amber-500 text-white text-sm rounded-lg hover:bg-amber-600 transition-all duration-200 shadow-sm flex items-center">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('admin.schedule.destroy', $schedule) }}" method="POST" class="inline-block delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-all duration-200 shadow-sm flex items-center">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Schedule Info -->
                <div class="col-span-2">
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <span class="text-sm text-gray-500 font-medium">Kelas</span>
                            <span class="text-sm font-semibold text-gray-800 bg-gray-50 px-3 py-1 rounded-md">
                                {{ $schedule->classroom ? $schedule->classroom->name : 'Tidak ditemukan' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <span class="text-sm text-gray-500 font-medium">Mata Pelajaran</span>
                            <span class="text-sm font-semibold text-gray-800 bg-gray-50 px-3 py-1 rounded-md">
                                {{ $schedule->subject ? $schedule->subject->name : 'Tidak ditemukan' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <span class="text-sm text-gray-500 font-medium">Guru</span>
                            <span class="text-sm font-semibold text-gray-800 bg-gray-50 px-3 py-1 rounded-md">
                                @if($schedule->teacher_id)
                                    {{ $schedule->teacher ? $schedule->teacher->name : 'Tidak ditemukan (ID: '.$schedule->teacher_id.')' }}
                                @else
                                    <span class="text-red-500">Tidak dipilih</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <span class="text-sm text-gray-500 font-medium">Hari</span>
                            <span class="text-sm font-semibold text-gray-800 bg-gray-50 px-3 py-1 rounded-md">{{ $schedule->day }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <span class="text-sm text-gray-500 font-medium">Waktu</span>
                            <span class="text-sm font-semibold text-gray-800 bg-gray-50 px-3 py-1 rounded-md">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</span>
                        </div>
                        
                        @if($schedule->notes)
                            <div class="pt-2">
                                <span class="text-sm text-gray-500 font-medium block mb-2">Catatan</span>
                                <p class="text-sm text-gray-800 bg-gray-50 p-4 rounded-lg border border-gray-100">{{ $schedule->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 shadow-sm">
                    <h4 class="text-md font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200">Informasi Lainnya</h4>
                    
                    <!-- Schedule Visibility -->
                    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <h5 class="text-sm font-semibold text-gray-600 mb-2">Dapat Dilihat Oleh</h5>
                        <ul class="text-xs text-gray-500 space-y-2">
                            @if($schedule->teacher)
                            <li class="flex items-center bg-blue-50 p-2 rounded-md">
                                <i class="fas fa-chalkboard-teacher mr-2 text-blue-500"></i>
                                <span>Guru: <span class="font-medium">{{ $schedule->teacher->name }}</span></span>
                            </li>
                            @elseif($schedule->teacher_id)
                            <li class="flex items-center bg-yellow-50 p-2 rounded-md">
                                <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>
                                <span>Guru: <span class="font-medium text-yellow-600">ID ada ({{ $schedule->teacher_id }}) tapi data tidak ditemukan</span></span>
                            </li>
                            @else
                            <li class="flex items-center bg-red-50 p-2 rounded-md">
                                <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                                <span>Guru: <span class="font-medium text-red-600">Tidak dipilih</span></span>
                            </li>
                            @endif
                            
                            @if($schedule->classroom)
                            <li class="flex items-center bg-green-50 p-2 rounded-md">
                                <i class="fas fa-users mr-2 text-green-500"></i>
                                <span>Siswa kelas <span class="font-medium">{{ $schedule->classroom->name }}</span></span>
                            </li>
                            @else
                            <li class="flex items-center bg-red-50 p-2 rounded-md">
                                <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                                <span>Kelas: <span class="font-medium text-red-600">Tidak ditemukan</span></span>
                            </li>
                            @endif
                        </ul>
                        <div class="mt-3 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                            Jadwal ini otomatis muncul di akun guru dan siswa terkait.
                        </div>
                    </div>
                    
                    <!-- Other students in the same classroom -->
                    @if($schedule->classroom && $schedule->classroom->students && $schedule->classroom->students->count() > 0)
                        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <h5 class="text-sm font-semibold text-gray-600 mb-2">Siswa di Kelas Ini</h5>
                            <div class="text-xs text-gray-500 flex items-center">
                                <i class="fas fa-users mr-2 text-blue-500"></i>
                                Jumlah: {{ $schedule->classroom->students->count() }} siswa
                            </div>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-xs mt-2 inline-block hover:underline">
                                <i class="fas fa-arrow-right mr-1"></i> Lihat daftar siswa
                            </a>
                        </div>
                    @endif
                    
                    <!-- Teacher's other classes -->
                    @if($schedule->teacher)
                        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <h5 class="text-sm font-semibold text-gray-600 mb-2">Jadwal Guru yang Sama</h5>
                            @php
                                $otherSchedules = \App\Models\Schedule::where('teacher_id', $schedule->teacher_id)
                                    ->where('id', '!=', $schedule->id)
                                    ->orderBy('day')
                                    ->orderBy('start_time')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            
                            @if($otherSchedules->count() > 0)
                                <ul class="text-xs text-gray-500 space-y-2">
                                    @foreach($otherSchedules as $otherSchedule)
                                        <li class="flex items-center justify-between bg-gray-50 p-2 rounded-md">
                                            <span class="flex items-center">
                                                <i class="far fa-calendar-alt mr-2 text-indigo-400"></i>
                                                {{ $otherSchedule->day }}, {{ substr($otherSchedule->start_time, 0, 5) }}
                                            </span>
                                            <span class="font-medium">
                                                {{ $otherSchedule->classroom->name ?? 'N/A' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                                @if($otherSchedules->count() == 5)
                                    <a href="{{ route('admin.schedule.index', ['teacher_id' => $schedule->teacher_id]) }}" class="text-blue-600 hover:text-blue-800 text-xs mt-3 inline-block hover:underline">
                                        <i class="fas fa-arrow-right mr-1"></i> Lihat semua jadwal
                                    </a>
                                @endif
                            @else
                                <p class="text-xs text-gray-500 bg-gray-50 p-2 rounded-md">Tidak ada jadwal lain</p>
                            @endif
                        </div>
                    @endif
                    
                    <!-- Created/Updated info -->
                    <div class="text-xs text-gray-500 mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center mb-1">
                            <i class="far fa-clock mr-2"></i>
                            <p>Dibuat pada: {{ $schedule->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-history mr-2"></i>
                            <p>Terakhir diubah: {{ $schedule->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin() || config('app.debug'))
    <div class="mt-8 p-4 border border-gray-200 rounded-lg bg-gray-50">
        <h3 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
            <i class="fas fa-bug mr-2 text-gray-500"></i> Informasi Debug
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-600">
                <tbody>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium bg-gray-100">Schedule ID</td>
                        <td class="py-2 px-4">{{ $schedule->id }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium bg-gray-100">Teacher ID</td>
                        <td class="py-2 px-4">{{ $schedule->teacher_id ?: 'null' }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium bg-gray-100">Teacher Object</td>
                        <td class="py-2 px-4">{{ $schedule->teacher ? 'Loaded (ID: '.$schedule->teacher->id.')' : 'Not loaded' }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium bg-gray-100">Classroom Object</td>
                        <td class="py-2 px-4">{{ $schedule->classroom ? 'Loaded (ID: '.$schedule->classroom->id.')' : 'Not loaded' }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium bg-gray-100">Subject Object</td>
                        <td class="py-2 px-4">{{ $schedule->subject ? 'Loaded (ID: '.$schedule->subject->id.')' : 'Not loaded' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="mt-3 flex items-center">
            <a href="{{ route('admin.schedule.edit', $schedule) }}" class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fas fa-wrench mr-1"></i> Perbaiki data jadwal
            </a>
            <span class="mx-2 text-gray-300">|</span>
            <button id="refreshRelations" class="text-xs text-green-600 hover:text-green-800 flex items-center">
                <i class="fas fa-sync-alt mr-1"></i> Refresh data
            </button>
            
            @if(!$schedule->teacher && $schedule->teacher_id || !$schedule->classroom && $schedule->classroom_id || !$schedule->subject && $schedule->subject_id)
            <span class="mx-2 text-gray-300">|</span>
            <a href="{{ route('admin.schedule.repair') }}" class="text-xs text-red-600 hover:text-red-800 flex items-center font-bold">
                <i class="fas fa-tools mr-1"></i> Perbaikan Relasi
            </a>
            @endif
        </div>
    </div>

    <script>
        // Simple refresh button functionality
        document.getElementById('refreshRelations')?.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat ulang...';
            location.reload();
        });
    </script>
    @endif
@endsection

@push('scripts')
<script>
    // Confirm delete with more detailed dialog
    document.querySelector('.delete-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const subjectName = "{{ $schedule->subject ? $schedule->subject->name : 'Tidak ditemukan' }}";
        const className = "{{ $schedule->classroom ? $schedule->classroom->name : 'Tidak ditemukan' }}";
        const dayTime = "{{ $schedule->day }}, {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}";
        
        const confirmation = confirm(
            `Apakah Anda yakin ingin menghapus jadwal ini?\n\n` +
            `Mata Pelajaran: ${subjectName}\n` +
            `Kelas: ${className}\n` +
            `Waktu: ${dayTime}\n\n` +
            `Tindakan ini tidak dapat dibatalkan.`
        );
        
        if (confirmation) {
            // Add loading state
            const deleteBtn = this.querySelector('button');
            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...';
                deleteBtn.disabled = true;
            }
            
            // Show overlay loading indicator
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            loadingOverlay.innerHTML = '<div class="bg-white p-5 rounded-lg shadow-lg"><i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i><p class="mt-2 text-gray-700">Menghapus jadwal...</p></div>';
            document.body.appendChild(loadingOverlay);
            
            // Submit with error handling
            try {
                this.submit();
            } catch (error) {
                console.error('Error during form submission:', error);
                alert('Terjadi kesalahan saat menghapus jadwal. Silakan coba lagi.');
                loadingOverlay.remove();
                if (deleteBtn) {
                    deleteBtn.innerHTML = '<i class="fas fa-trash mr-2"></i>Hapus';
                    deleteBtn.disabled = false;
                }
            }
        }
    });
</script>
@endpush
