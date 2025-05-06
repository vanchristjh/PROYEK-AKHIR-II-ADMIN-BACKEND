@extends('layouts.dashboard')

@section('title', 'Edit Pengumuman')

@section('header', 'Edit Pengumuman')

@section('navigation')
    <li>
        <a href="{{ route('guru.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.materials.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-book text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Materi Pelajaran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.assignments.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tasks text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Tugas</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.grades.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-star text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Penilaian</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.attendance.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-clipboard-check text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Kehadiran</span>
        </a>
    </li>
    <li>
        <a href="{{ route('guru.announcements.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-bullhorn text-lg w-6"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="flex items-center space-x-2 mb-6">
        <a href="{{ route('guru.announcements.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        <span class="text-gray-500">/</span>
        <h2 class="text-xl font-semibold text-gray-800">Edit Pengumuman</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden p-6">
        <form action="{{ route('guru.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman <span class="text-red-600">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="audience" class="block text-sm font-medium text-gray-700 mb-1">Ditujukan Untuk <span class="text-red-600">*</span></label>
                    <select name="audience" id="audience" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="all" {{ old('audience', $announcement->audience) == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="teachers" {{ old('audience', $announcement->audience) == 'teachers' ? 'selected' : '' }}>Guru</option>
                        <option value="students" {{ old('audience', $announcement->audience) == 'students' ? 'selected' : '' }}>Siswa</option>
                    </select>
                    @error('audience')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman <span class="text-red-600">*</span></label>
                    <textarea name="content" id="content" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi <span class="text-red-600">*</span></label>
                    <input type="datetime-local" name="publish_date" id="publish_date" value="{{ old('publish_date', $announcement->publish_date->format('Y-m-d\TH:i')) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    @error('publish_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kedaluwarsa</label>
                    <input type="datetime-local" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $announcement->expiry_date ? $announcement->expiry_date->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika pengumuman tidak memiliki tanggal kedaluwarsa.</p>
                    @error('expiry_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_important" id="is_important" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_important', $announcement->is_important) ? 'checked' : '' }}>
                    <label for="is_important" class="ml-2 block text-sm text-gray-700">Tandai sebagai pengumuman penting</label>
                    @error('is_important')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lampiran Saat Ini</label>
                    @if($announcement->attachment)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="w-10 h-10 flex-shrink-0 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-file text-indigo-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h5 class="text-sm font-medium text-gray-900">{{ basename($announcement->attachment) }}</h5>
                                <a href="{{ Storage::url($announcement->attachment) }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-900 mt-1 inline-flex items-center">
                                    <i class="fas fa-eye mr-1"></i> Lihat Lampiran
                                </a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="remove_attachment" id="remove_attachment" value="1" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                <label for="remove_attachment" class="ml-2 block text-sm text-gray-700">Hapus lampiran saat ini</label>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm italic">Tidak ada lampiran</p>
                    @endif
                </div>

                <div>
                    <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Ganti Lampiran</label>
                    <input type="file" name="attachment" id="attachment" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-500">Format yang diterima: PDF, Word, Excel, PowerPoint, dan gambar. Ukuran maksimum: 10MB.</p>
                    @error('attachment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
