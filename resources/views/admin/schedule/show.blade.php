@extends('layouts.dashboard')

@section('title', 'Detail Jadwal')

@section('header', 'Detail Jadwal')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-calendar-alt text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Detail Jadwal</h2>
            <p class="text-blue-100">Informasi lengkap tentang jadwal</p>
        </div>
    </div>

    <div class="mb-6">
        <a href="{{ route('admin.schedule.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <i class="fas fa-chevron-left mr-2 text-sm"></i>
            <span>Kembali ke Daftar Jadwal</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200/50">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Informasi Jadwal</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-calendar-day text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Hari</p>
                                <p class="text-base text-gray-900">{{ $dayNames[$schedule->day] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-clock text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Waktu</p>
                                <p class="text-base text-gray-900">{{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-door-open text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Ruangan</p>
                                <p class="text-base text-gray-900">{{ $schedule->room ?? 'Belum ditentukan' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-history text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Terakhir Diperbarui</p>
                                <p class="text-base text-gray-900">{{ $schedule->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200/50">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Detail Pelajaran</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-book text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Mata Pelajaran</p>
                                <p class="text-base text-gray-900">{{ $schedule->subject->name }}</p>
                                <p class="text-xs text-gray-500">Kode: {{ $schedule->subject->code }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-chalkboard-teacher text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Guru</p>
                                <p class="text-base text-gray-900">{{ $schedule->teacher->name }}</p>
                                <p class="text-xs text-gray-500">Email: {{ $schedule->teacher->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-users text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Kelas</p>
                                <p class="text-base text-gray-900">{{ $schedule->classroom->name }}</p>
                                <p class="text-xs text-gray-500">{{ $schedule->classroom->grade_level }}, Tahun Ajaran {{ $schedule->classroom->academic_year }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-200 flex justify-between">
                <div>
                    <form action="{{ route('admin.schedule.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-trash-alt mr-2"></i> Hapus Jadwal
                        </button>
                    </form>
                </div>
                <div class="space-x-2">
                    <a href="{{ route('admin.schedule.edit', $schedule->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-edit mr-2"></i> Edit Jadwal
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .animate-fade-in {
        animation: fade-in 0.6s ease-in-out;
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
</style>
@endpush
