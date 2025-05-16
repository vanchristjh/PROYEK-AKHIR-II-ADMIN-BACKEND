@extends('layouts.dashboard')

@section('title', 'Jadwal Pelajaran')

@section('header', 'Manajemen Jadwal Pelajaran')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
        <div>
            <h3 class="text-xl font-medium text-gray-900 flex items-center">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i> Daftar Jadwal Pelajaran
            </h3>
            <p class="text-sm text-gray-600 mt-1">Daftar seluruh jadwal pelajaran di sekolah</p>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.schedule.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 flex items-center text-sm font-medium">
                <i class="fas fa-plus mr-2"></i> Tambah Jadwal Baru
            </a>
        </div>
    </div>
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-500"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    
    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden border border-gray-200">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h4 class="font-medium text-gray-800">
                <i class="fas fa-filter mr-2 text-blue-500"></i> Filter Jadwal
            </h4>
        </div>
        
        <div class="p-4">
            <form action="{{ route('admin.schedule.index') }}" method="GET" class="flex flex-col md:flex-row items-end space-y-4 md:space-y-0 md:space-x-4">
                <!-- Classroom Filter -->
                <div class="w-full md:w-1/3">
                    <label for="classroom" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select id="classroom" name="classroom" class="form-select pl-3 pr-10 py-2 w-full rounded-lg border-gray-300">
                        <option value="">Semua Kelas</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @if($classroomFilter == $classroom->id) selected @endif>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Day Filter -->
                <div class="w-full md:w-1/3">
                    <label for="day" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select id="day" name="day" class="form-select pl-3 pr-10 py-2 w-full rounded-lg border-gray-300">
                        <option value="">Semua Hari</option>
                        @foreach($days as $day)
                            <option value="{{ $day }}" @if($dayFilter == $day) selected @endif>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filter Button -->
                <div class="w-full md:w-auto flex space-x-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 flex items-center text-sm font-medium">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    
                    <a href="{{ route('admin.schedule.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-all duration-200 flex items-center text-sm font-medium">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Schedule Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h4 class="font-medium text-gray-800">
                <i class="fas fa-list mr-2 text-blue-500"></i> Daftar Jadwal Pelajaran
            </h4>
            <p class="text-sm text-gray-600 mt-1">Total: {{ $schedules->total() }} jadwal</p>
        </div>
        
        @if($schedules->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($schedules as $index => $schedule)
                            <tr class="{{ $index % 2 == 0 ? '' : 'bg-gray-50' }} hover:bg-blue-50 transition-all duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $schedules->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->day }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->classroom->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $schedule->subject ? $schedule->subject->name : 'Mata Pelajaran Tidak Tersedia' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $schedule->teacher_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.schedule.edit', $schedule) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.schedule.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 flex items-center" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $schedules->appends(request()->query())->links() }}
            </div>
        @else
            <div class="py-8 text-center">
                <div class="text-gray-500 mb-2">
                    <i class="fas fa-calendar-times text-4xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada jadwal</h3>
                <p class="text-gray-500">Tidak ada jadwal yang sesuai dengan filter yang dipilih.</p>
                <div class="mt-4">
                    <a href="{{ route('admin.schedule.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i> Tambah Jadwal Baru
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if new schedule added notification exists
        if (sessionStorage.getItem('newScheduleAdded')) {
            sessionStorage.removeItem('newScheduleAdded');
            
            // Add success alert 
            const alertContainer = document.createElement('div');
            alertContainer.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm';
            alertContainer.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    <p class="font-medium">Jadwal baru berhasil ditambahkan!</p>
                </div>
            `;
            
            // Find the location to insert the alert
            const content = document.querySelector('.content');
            content.insertBefore(alertContainer, content.firstChild);
            
            // Auto-hide after 4 seconds
            setTimeout(() => {
                alertContainer.style.opacity = '0';
                alertContainer.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    alertContainer.remove();
                }, 500);
            }, 4000);
        }
    });
</script>
@endpush
