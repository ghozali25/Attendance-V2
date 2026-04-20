<div class="py-6 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden relative">
            
            {{-- Header --}}
            <div class="px-5 py-4 lg:px-8 lg:py-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 relative z-10">
                <div class="flex items-center gap-3">
                    @if($showModal)
                         <button wire:click="close" class="p-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 transition">
                            <x-heroicon-o-arrow-left class="h-4 w-4 text-gray-500 dark:text-gray-300" />
                        </button>
                    @else
                        <x-secondary-button href="{{ route('home') }}" class="!rounded-xl !px-3 !py-2 border-gray-200 dark:border-gray-600 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600">
                            <x-heroicon-o-arrow-left class="h-4 w-4 text-gray-500 dark:text-gray-300" />
                        </x-secondary-button>
                    @endif
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        {{ $showModal ? __('New Request') : __('Overtime Request') }}
                    </h3>
                </div>
                
                @if(!$showModal)
                    <button wire:click="create" class="px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 font-bold text-xs uppercase tracking-widest transition shadow-lg shadow-primary-500/30 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="hidden sm:inline">{{ __('New Request') }}</span>
                    </button>
                @endif
            </div>

            <div class="p-0">
                @if($showModal)
                    {{-- Create Form --}}
                    <div class="p-6 lg:p-8">
                        <form wire:submit.prevent="store" class="space-y-6">
                            
                            {{-- Date --}}
                            <div>
                                <x-label for="date" value="{{ __('Overtime Date') }}" />
                                <x-input id="date" type="date" class="mt-1 block w-full" wire:model="date" />
                                <x-input-error for="date" class="mt-2" />
                            </div>

                            {{-- Time Range --}}
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <x-label for="start_time" value="{{ __('Start Time') }}" />
                                    <x-input id="start_time" type="time" class="mt-1 block w-full" wire:model="start_time" />
                                    <x-input-error for="start_time" class="mt-2" />
                                </div>
                                <div>
                                    <x-label for="end_time" value="{{ __('End Time') }}" />
                                    <x-input id="end_time" type="time" class="mt-1 block w-full" wire:model="end_time" />
                                    <x-input-error for="end_time" class="mt-2" />
                                </div>
                            </div>

                            {{-- Reason --}}
                            <div>
                                <x-label for="reason" value="{{ __('Reason') }}" />
                                <textarea id="reason" wire:model="reason" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 rounded-md shadow-sm" placeholder="e.g. Project Deadline"></textarea>
                                <x-input-error for="reason" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <x-secondary-button wire:click="close" wire:loading.attr="disabled">
                                    {{ __('Cancel') }}
                                </x-secondary-button>

                                <x-button wire:loading.attr="disabled">
                                    {{ __('Submit Request') }}
                                </x-button>
                            </div>
                        </form>
                    </div>

                @else
                    {{-- History List --}}
                    @if($overtimes->isEmpty())
                        <div class="p-8 text-center flex flex-col items-center justify-center min-h-[400px]">
                            <div class="w-24 h-24 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('No Overtime Requests') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm mb-6">{{ __('You haven\'t submitted any overtime requests yet.') }}</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                             @foreach($overtimes as $overtime)
                                <div class="p-4 sm:p-6 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-xl flex items-center justify-center bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                                            ⏰
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white capitalize">
                                                {{ $overtime->date->format('d M Y') }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">
                                                {{ $overtime->start_time->format('H:i') }} - {{ $overtime->end_time->format('H:i') }}
                                                <span class="mx-1">•</span>
                                                {{ $overtime->duration_text }}
                                            </p>
                                            <p class="text-[10px] text-gray-400 italic line-clamp-1">{{ $overtime->reason }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                         <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            @if($overtime->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400
                                            @elseif($overtime->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400 @endif">
                                            {{ ucfirst($overtime->status) }}
                                        </span>
                                    </div>
                                </div>
                             @endforeach
                        </div>
                        <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                            {{ $overtimes->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
