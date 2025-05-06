@props(['headers', 'striped' => false, 'hoverable' => true, 'bordered' => true, 'rounded' => true, 'compact' => false])

<div class="bg-white {{ $rounded ? 'rounded-xl' : '' }} shadow-sm {{ $bordered ? 'border border-gray-100/60' : '' }} overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    @foreach($headers as $header)
                    <th scope="col" class="{{ $compact ? 'px-4 py-2' : 'px-6 py-3' }} text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $loop->first && $rounded ? 'rounded-tl-lg' : '' }} {{ $loop->last && $rounded ? 'rounded-tr-lg' : '' }}">
                        {{ $header }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="{{ $striped ? 'divide-y divide-gray-100' : 'divide-y divide-gray-100' }}">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if(isset($pagination))
    <div class="px-6 py-3 border-t border-gray-100 bg-gray-50">
        {{ $pagination }}
    </div>
    @endif
</div>

<style>
    /* Row hover effect */
    tr.data-row {
        @apply transition-all duration-150;
    }
    
    @if($hoverable)
    tr.data-row:hover {
        @apply bg-gray-50;
    }
    @endif
    
    /* Alternating row colors for striped tables */
    @if($striped)
    tr.data-row:nth-child(even) {
        @apply bg-gray-50/50;
    }
    @endif
</style>
