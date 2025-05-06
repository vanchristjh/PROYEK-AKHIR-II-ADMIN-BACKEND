@extends('layouts.dashboard')

@section('title', 'User Management')

@section('header', 'User Management')

@section('navigation')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">User Management</h2>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            <i class="fas fa-plus mr-2"></i> Add New User
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md animate-fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="p-4 bg-gray-50 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-700 flex items-center">
                    <i class="fas fa-users text-blue-500 mr-2"></i>
                    <span>All Users</span>
                </h3>
                <div class="relative">
                    <input type="text" id="userSearch" placeholder="Search users..." 
                        class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 pl-10">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
