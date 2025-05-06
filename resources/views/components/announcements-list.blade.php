<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    @forelse($announcements as $announcement)
        <div class="p-6 {{ !$loop->first ? 'border-t border-gray-100' : '' }} {{ $announcement->is_important ? 'bg-red-50' : '' }}">
            <div class="flex justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        @if($announcement->is_important)
                            <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                <i class="fas fa-exclamation-circle mr-1"></i> Penting
                            </span>
                        @endif
                        <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                    </div>
                    <p class="text-gray-600 mb-4">{{ Str::limit($announcement->content, 150) }}</p>
                    <div class="flex flex-wrap text-sm text-gray-500 gap-x-4 gap-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle mr-1"></i>
                            {{ $announcement->author->name ?? 'Unknown' }}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $announcement->publish_date->format('d M Y') }}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users mr-1"></i>
                            <span>
                                @switch($announcement->audience)
                                    @case('all')
                                        Semua
                                        @break
                                    @case('teachers')
                                        Guru
                                        @break
                                    @case('students')
                                        Siswa
                                        @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2 ml-4">
                    <a href="{{ route($routePrefix . '.announcements.show', $announcement) }}" class="text-xs text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded hover:bg-indigo-100 transition-colors inline-flex items-center">
                        <i class="fas fa-eye mr-1"></i> Detail
                    </a>
                    @if(auth()->user()->isAdmin() || auth()->id() === $announcement->author_id)
                        @if(Route::has($routePrefix . '.announcements.edit'))
                        <a href="{{ route($routePrefix . '.announcements.edit', $announcement) }}" class="text-xs text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded hover:bg-blue-100 transition-colors inline-flex items-center">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        @endif
                    @endif
                </div>
            </div>
            
            @if($announcement->attachment)
                <div class="mt-4 bg-gray-50 p-3 rounded-lg border border-gray-100 flex items-center text-sm">
                    <i class="fas fa-paperclip text-gray-500 mr-2"></i>
                    <span class="text-gray-700">Attachment: </span>
                    @if(Route::has($routePrefix . '.announcements.download'))
                    <a href="{{ route($routePrefix . '.announcements.download', $announcement) }}" class="ml-2 text-indigo-600 hover:text-indigo-800">
                        {{ basename($announcement->attachment) }}
                    </a>
                    @else
                    <span class="ml-2 text-gray-500">{{ basename($announcement->attachment) }}</span>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <i class="fas fa-bullhorn text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pengumuman</h3>
            <p class="text-gray-500 mb-6">Belum ada pengumuman yang dibuat saat ini.</p>
            @if(Route::has($routePrefix . '.announcements.create') && (auth()->user()->isAdmin() || auth()->user()->isGuru()))
            <a href="{{ route($routePrefix . '.announcements.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Buat Pengumuman
            </a>
            @endif
        </div>
    @endforelse
    
    @if($announcements->count() > 0 && method_exists($announcements, 'links'))
        <div class="px-6 py-3 border-t border-gray-100">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
