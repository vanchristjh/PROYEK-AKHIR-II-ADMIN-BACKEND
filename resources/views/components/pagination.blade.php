@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <!-- Mobile view -->
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-secondary-400 bg-white border border-secondary-200 rounded-md cursor-default">
                    <i class="fas fa-chevron-left mr-1 text-xs"></i> 
                    {{ __('Previous') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-secondary-700 bg-white border border-secondary-200 rounded-md hover:text-primary-600 hover:border-primary-300 transition-colors">
                    <i class="fas fa-chevron-left mr-1 text-xs"></i> 
                    {{ __('Previous') }}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-secondary-700 bg-white border border-secondary-200 rounded-md hover:text-primary-600 hover:border-primary-300 transition-colors">
                    {{ __('Next') }}
                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-secondary-400 bg-white border border-secondary-200 rounded-md cursor-default">
                    {{ __('Next') }}
                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </span>
            @endif
        </div>

        <!-- Desktop view -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-secondary-600">
                    {{ __('Showing') }}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-secondary-400 bg-white border border-secondary-200 rounded-l-md cursor-default" aria-hidden="true">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-secondary-600 bg-white border border-secondary-200 rounded-l-md hover:text-primary-600 hover:border-primary-300 transition-colors" aria-label="{{ __('pagination.previous') }}">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-secondary-600 bg-white border border-secondary-200 cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-primary-50 text-primary-700 border border-primary-200 cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-secondary-600 bg-white border border-secondary-200 hover:text-primary-600 hover:border-primary-300 transition-colors" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-secondary-600 bg-white border border-secondary-200 rounded-r-md hover:text-primary-600 hover:border-primary-300 transition-colors" aria-label="{{ __('pagination.next') }}">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-secondary-400 bg-white border border-secondary-200 rounded-r-md cursor-default" aria-hidden="true">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif