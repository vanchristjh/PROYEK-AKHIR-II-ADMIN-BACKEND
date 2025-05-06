@props(['activeTab' => 1])

<div x-data="{ activeTab: {{ $activeTab }} }" class="tabs-component">
    <!-- Tab Navigation -->
    <div class="border-b border-secondary-200">
        <nav class="flex -mb-px space-x-6 overflow-x-auto" aria-label="Tabs">
            {{ $tabHeaders }}
        </nav>
    </div>
    
    <!-- Tab Content -->
    <div class="mt-4">
        {{ $tabContents }}
    </div>
</div>

<!-- Tab headers and contents are passed as slots -->
<!-- Use like this:
<x-tabs>
    <x-slot name="tabHeaders">
        <button @click="activeTab = 1" :class="{ 'text-primary-600 border-primary-500': activeTab === 1 }" 
                class="py-2 px-1 font-medium text-sm border-b-2 border-transparent text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 whitespace-nowrap">
            Tab 1
        </button>
        <button @click="activeTab = 2" :class="{ 'text-primary-600 border-primary-500': activeTab === 2 }" 
                class="py-2 px-1 font-medium text-sm border-b-2 border-transparent text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 whitespace-nowrap">
            Tab 2
        </button>
    </x-slot>
    
    <x-slot name="tabContents">
        <div x-show="activeTab === 1">Content for tab 1</div>
        <div x-show="activeTab === 2">Content for tab 2</div>
    </x-slot>
</x-tabs>
-->