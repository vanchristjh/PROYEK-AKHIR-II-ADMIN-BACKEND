@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')

@section('header', 'Manajemen Pengguna')

@section('navigation')
    <li>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-tachometer-alt text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}" class="sidebar-item sidebar-active flex items-center rounded-lg px-4 py-3 text-white">
            <i class="fas fa-users text-lg w-6"></i>
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
        <a href="{{ route('admin.announcements.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-bullhorn text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengumuman</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-item flex items-center rounded-lg px-4 py-3 text-indigo-100 hover:text-white transition-all duration-200">
            <i class="fas fa-cog text-lg w-6 text-indigo-300"></i>
            <span class="ml-3">Pengaturan</span>
        </a>
    </li>
@endsection

@section('content')
    <!-- Header with animation -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white relative overflow-hidden animate-fade-in">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fas fa-users text-9xl"></i>
        </div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-36 h-36 bg-purple-300/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Manajemen Pengguna</h2>
            <p class="text-purple-100">Kelola semua pengguna sistem termasuk administrator, guru, dan siswa.</p>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg mr-3">
                <i class="fas fa-users text-purple-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">Daftar Pengguna</h3>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-colors shadow-md hover:shadow-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md animate-fade-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100/50 transform transition hover:shadow-md">
        <div class="bg-gray-50 p-4 border-b border-gray-100 flex flex-wrap md:flex-nowrap items-center justify-between gap-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                    <i class="fas fa-filter text-purple-600"></i>
                </div>
                <h4 class="text-base font-medium text-gray-800">Filter & Pencarian</h4>
            </div>
            <div class="flex items-center flex-grow md:justify-end gap-3">
                <div class="relative flex-grow max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input id="userSearch" type="text" placeholder="Cari pengguna..." class="pl-10 py-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                </div>
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex-grow md:flex-grow-0">
                    <select name="role" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 py-2 w-full">
                        <option value="">Semua Peran</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->slug }}" {{ $roleFilter === $role->slug ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pengguna
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Username
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Peran
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors user-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->avatar)
                                            <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-semibold text-lg">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $user->username }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->role->slug === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                      ($user->role->slug === 'guru' ? 'bg-blue-100 text-blue-800' : 
                                       'bg-green-100 text-green-800') }}">
                                    {{ $user->role->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->classroom ? $user->classroom->name : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 p-2 rounded-md transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}? Tindakan ini tidak dapat diurungkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-2 rounded-md transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                                        <i class="fas fa-users-slash text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak Ada Pengguna</h3>
                                    <p class="text-gray-500 max-w-md">
                                        Belum ada pengguna yang terdaftar atau sesuai dengan filter yang Anda pilih.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($users->hasPages())
            <div class="border-t border-gray-200 px-4 py-3 bg-gray-50">
                <div class="pagination-container">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Hidden no results message template -->
    <template id="no-results-template">
        <tr id="no-results-row">
            <td colspan="6" class="px-6 py-10 text-center">
                <div class="flex flex-col items-center">
                    <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                        <i class="fas fa-search text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak Ada Hasil</h3>
                    <p class="text-gray-500 max-w-md">
                        Tidak ada pengguna yang cocok dengan pencarian Anda.
                    </p>
                </div>
            </td>
        </tr>
    </template>
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
    
    .highlight-search {
        animation: highlight 1s ease-in-out;
    }
    
    @keyframes highlight {
        0% {
            background-color: rgba(139, 92, 246, 0.1);
        }
        100% {
            background-color: transparent;
        }
    }
    
    /* Pagination styling to match the design */
    .pagination {
        display: flex;
        list-style-type: none;
        justify-content: center;
    }
    
    .pagination li {
        display: inline-flex;
    }
    
    .pagination li a, .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        font-weight: 500;
        color: #4B5563;
        background-color: #ffffff;
        border: 1px solid #D1D5DB;
        transition: all 0.2s ease-in-out;
    }
    
    .pagination .active span {
        color: #ffffff;
        background-color: #8B5CF6;
        border-color: #8B5CF6;
    }
    
    .pagination a:hover {
        color: #1F2937;
        background-color: #F3F4F6;
    }
    
    .pagination .disabled span {
        color: #9CA3AF;
        pointer-events: none;
        background-color: #F3F4F6;
    }
    
    .pagination li:first-child a,
    .pagination li:first-child span {
        margin-left: 0;
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }
    
    .pagination li:last-child a,
    .pagination li:last-child span {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success alert after 5 seconds
        const successAlert = document.querySelector('.bg-green-100');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                successAlert.style.transition = 'opacity 0.5s ease-in-out';
                setTimeout(() => {
                    successAlert.remove();
                }, 500);
            }, 5000);
        }
        
        // User search functionality
        const userSearch = document.getElementById('userSearch');
        const userRows = document.querySelectorAll('.user-row');
        const userTableBody = document.getElementById('userTableBody');
        const noResultsTemplate = document.getElementById('no-results-template');
        
        userSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let foundResults = false;
            
            // Remove existing no-results row if it exists
            const existingNoResults = document.getElementById('no-results-row');
            if (existingNoResults) {
                existingNoResults.remove();
            }
            
            userRows.forEach(row => {
                const name = row.querySelector('td:nth-child(1) .text-gray-900').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(2) .text-gray-500').textContent.toLowerCase();
                const username = row.querySelector('td:nth-child(3) .text-gray-500').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm) || username.includes(searchTerm)) {
                    row.classList.remove('hidden');
                    foundResults = true;
                    
                    // Add highlight animation
                    row.classList.add('highlight-search');
                    setTimeout(() => {
                        row.classList.remove('highlight-search');
                    }, 1000);
                } else {
                    row.classList.add('hidden');
                }
            });
            
            // Show no results message if no matches found
            if (!foundResults && searchTerm !== '') {
                userTableBody.insertAdjacentHTML('beforeend', noResultsTemplate.innerHTML);
            }
        });
    });
</script>
@endpush
