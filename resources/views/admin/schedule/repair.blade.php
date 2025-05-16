@extends('layouts.dashboard')

@section('title', 'Perbaikan Data Jadwal')

@section('header', 'Perbaikan Relasi Jadwal')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.schedule.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-medium transition-all duration-200 group">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform duration-200"></i> Kembali ke Daftar Jadwal
        </a>
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

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Info Panel -->
    <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4 mb-6 rounded-md shadow-sm">
        <h4 class="text-lg font-medium mb-2">Tentang Halaman Perbaikan</h4>
        <p class="text-sm mb-2">Halaman ini menampilkan jadwal dengan relasi yang rusak (ID ada tetapi data tidak ditemukan).</p>
        <p class="text-sm">Anda dapat memperbaiki relasi tersebut dengan memilih data yang valid atau menghapus relasi yang rusak.</p>
    </div>

    <!-- Quick Fix Button -->
    <div class="mb-6">
        <a href="{{ route('admin.schedule.clean-relations') }}" class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-all duration-200 flex items-center text-sm font-medium shadow-sm inline-block">
            <i class="fas fa-magic mr-2"></i> Bersihkan Semua Relasi Yang Rusak
        </a>
        <p class="text-xs text-gray-500 mt-1">Tindakan ini akan menghapus ID relasi yang rusak dengan menjadikannya null (kosong)</p>
    </div>

    <!-- Broken Schedules Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-tools mr-2 text-red-500"></i>
                Jadwal dengan Relasi Rusak
            </h3>
        </div>

        @if(count($brokenSchedules) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hari & Waktu
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mata Pelajaran
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Guru
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($brokenSchedules as $schedule)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $schedule->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $schedule->day }}</div>
                                <div class="text-xs text-gray-500">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($schedule->classroom)
                                    <span class="text-sm text-gray-900">{{ $schedule->classroom->name }}</span>
                                @elseif($schedule->classroom_id)
                                    <span class="text-sm text-red-600">ID: {{ $schedule->classroom_id }} (tidak ditemukan)</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($schedule->subject)
                                    <span class="text-sm text-gray-900">{{ $schedule->subject->name }}</span>
                                @elseif($schedule->subject_id)
                                    <span class="text-sm text-red-600">ID: {{ $schedule->subject_id }} (tidak ditemukan)</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($schedule->teacher)
                                    <span class="text-sm text-gray-900">{{ $schedule->teacher->name }}</span>
                                @elseif($schedule->teacher_id)
                                    <span class="text-sm text-red-600">ID: {{ $schedule->teacher_id }} (tidak ditemukan)</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="text-blue-600 hover:text-blue-900" onclick="openRepairModal({{ $schedule->id }})">
                                    <i class="fas fa-wrench mr-1"></i> Perbaiki
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center">
                <i class="far fa-check-circle text-green-500 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada jadwal dengan relasi rusak</h3>
                <p class="text-gray-500">Semua jadwal memiliki relasi yang valid</p>
            </div>
        @endif
    </div>
    
    <!-- Repair Modal -->
    <div id="repairModal" class="hidden fixed inset-0 overflow-y-auto z-50">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                Perbaiki Relasi Jadwal
                            </h3>
                            <form action="{{ route('admin.schedule.repair') }}" method="POST" id="repairForm">
                                @csrf
                                <input type="hidden" name="schedule_id" id="scheduleId">
                                
                                <!-- Teacher Selection -->
                                <div class="mb-4">
                                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Guru
                                    </label>
                                    <select id="teacher_id" name="teacher_id" class="form-select rounded-lg border-gray-300 w-full">
                                        <option value="">-- Kosongkan --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Pilih guru yang benar atau kosongkan untuk menghapus relasi</p>
                                </div>
                                
                                <!-- Classroom Selection -->
                                <div class="mb-4">
                                    <label for="classroom_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Kelas
                                    </label>
                                    <select id="classroom_id" name="classroom_id" class="form-select rounded-lg border-gray-300 w-full">
                                        <option value="">-- Kosongkan --</option>
                                        @foreach($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Subject Selection -->
                                <div class="mb-4">
                                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Mata Pelajaran
                                    </label>
                                    <select id="subject_id" name="subject_id" class="form-select rounded-lg border-gray-300 w-full">
                                        <option value="">-- Kosongkan --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="submitRepair()">
                        Perbaiki
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeRepairModal()">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openRepairModal(scheduleId) {
        document.getElementById('scheduleId').value = scheduleId;
        document.getElementById('repairModal').classList.remove('hidden');
    }
    
    function closeRepairModal() {
        document.getElementById('repairModal').classList.add('hidden');
    }
    
    function submitRepair() {
        document.getElementById('repairForm').submit();
    }
    
    // Close modal when clicking outside of it
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('repairModal');
        const modalContent = document.querySelector('#repairModal > div > div:last-child');
        
        if (modal && !modal.classList.contains('hidden') && modalContent && !modalContent.contains(event.target)) {
            closeRepairModal();
        }
    });
</script>
@endpush
