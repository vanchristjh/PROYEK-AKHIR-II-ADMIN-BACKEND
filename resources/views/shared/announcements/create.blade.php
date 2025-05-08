@php
    // Determine the route prefix based on current route
    $prefix = '';
    if (request()->route()->getPrefix() === 'admin') {
        $prefix = 'admin';
    } elseif (request()->route()->getPrefix() === 'guru') {
        $prefix = 'guru';
    }
@endphp

@extends('layouts.dashboard')

@section('title', 'Buat Pengumuman')

@section('header', 'Buat Pengumuman Baru')

@section('navigation')
    <!-- Set appropriate navigation links based on user role -->
    @if($prefix === 'admin')
        @include('admin.partials.navigation', ['active' => 'announcements'])
    @else
        @include('guru.partials.navigation', ['active' => 'announcements'])
    @endif
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Buat Pengumuman Baru</h2>
        <a href="{{ route($prefix.'.announcements.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md">
            <p class="font-bold">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route($prefix.'.announcements.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        @csrf

        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Pengumuman</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman <span class="text-red-600">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>

                <!-- Is Important -->
                <div>
                    <label for="is_important" class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                    <select name="is_important" id="is_important" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="0" {{ old('is_important') == '0' ? 'selected' : '' }}>Reguler</option>
                        <option value="1" {{ old('is_important') == '1' ? 'selected' : '' }}>Penting</option>
                    </select>
                </div>

                <!-- Audience -->
                <div>
                    <label for="audience" class="block text-sm font-medium text-gray-700 mb-1">Ditujukan Untuk <span class="text-red-600">*</span></label>
                    <select name="audience" id="audience" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="all" {{ old('audience') == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="administrators" {{ old('audience') == 'administrators' ? 'selected' : '' }}>Admin</option>
                        <option value="teachers" {{ old('audience') == 'teachers' ? 'selected' : '' }}>Guru</option>
                        <option value="students" {{ old('audience') == 'students' ? 'selected' : '' }}>Siswa</option>
                    </select>
                </div>
                
                <!-- Publish Date -->
                <div>
                    <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                    <input type="datetime-local" name="publish_date" id="publish_date" value="{{ old('publish_date', now()->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan untuk publikasi langsung</p>
                </div>
                
                <!-- Expiry Date -->
                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kedaluwarsa</label>
                    <input type="datetime-local" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batas waktu.</p>
                </div>
                
                <!-- Attachment -->
                <div>
                    <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">File Lampiran</label>
                    <input type="file" name="attachment" id="attachment" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Maks 10MB. Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG</p>
                </div>

                <!-- Content -->
                <div class="col-span-2">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman <span class="text-red-600">*</span></label>
                    <textarea name="content" id="content" rows="8" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('content') }}</textarea>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i> Publikasikan
            </button>
        </div>
    </form>

    <div class="bg-blue-50 rounded-lg p-4 text-sm text-blue-800 border border-blue-200 flex items-start">
        <i class="fas fa-info-circle text-blue-500 mr-3 mt-0.5"></i>
        <div>
            <p class="font-medium mb-1">Informasi pengumuman:</p>
            <ul class="list-disc list-inside space-y-1 text-blue-700">
                <li>Pengumuman dengan prioritas "Penting" akan ditampilkan dengan penanda khusus</li>
                <li>Anda dapat menentukan target audiens pengumuman untuk mengontrol siapa yang dapat melihatnya</li>
                <li>Anda dapat melampirkan file untuk memberikan informasi lebih detail</li>
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#content'))
        .catch(error => {
            console.error(error);
        });
</script>
@endpush
