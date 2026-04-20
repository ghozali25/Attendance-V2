<x-app-layout>
    <div class="py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-2 relative z-[60]">
                 <a href="{{ route('home') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-gray-500 dark:text-gray-400">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Scan Attendance') }}</h1>
                
                <div class="w-10"></div>
            </div>

            {{-- Main Content --}}
            @livewire('scan-component')
        </div>
    </div>
</x-app-layout>
