@extends('layouts.dashboard')

@section('title', 'Kelola Pengumuman')

@section('header', 'Kelola Pengumuman')

@section('navigation')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-users text-lg w-6 text-indigo-300"></i>
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
        <a href="{{ route('admin.announcements.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-bullhorn text-lg w-6"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Pengumuman</h2>
        <a href="{{ route('admin.announcements.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Buat Pengumuman
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-4 bg-gray-50 border-b">
            <form action="{{ route('admin.announcements.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-grow min-w-[200px]">
                    <label for="importance" class="block text-sm font-medium text-gray-700 mb-1">Filter Prioritas</label>
                    <select name="importance" id="importance" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="this.form.submit()">
                        <option value="all" {{ request('importance') == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="important" {{ request('importance') == 'important' ? 'selected' : '' }}>Penting</option>
                        <option value="regular" {{ request('importance') == 'regular' ? 'selected' : '' }}>Reguler</option>
                    </select>
                </div>
                <div class="flex-grow min-w-[200px]">
                    <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Filter Pembuat</label>
                    <select name="author" id="author" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="this.form.submit()">
                        <option value="">Semua Pembuat</option>
                        @foreach($authors ?? [] as $author)
                            <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                {{ $author->name }} ({{ $author->role->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                @if(request()->anyFilled(['importance', 'author']))
                    <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    @component('components.announcements-list', ['announcements' => $announcements, 'routePrefix' => 'admin'])
    @endcomponent
@endsection
