<div x-data="{ activeTab: 'export' }" class="space-y-6">
    
    <div>
        <!-- Tabs Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ __('Attendance Data Management') }}
            </h3>
            
            <div class="flex w-full sm:w-auto justify-center bg-gray-200 dark:bg-gray-700 rounded-lg p-1">
                <button @click="activeTab = 'export'" 
                    :class="activeTab === 'export' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                    class="px-4 py-1.5 text-sm font-medium rounded-md transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    {{ __('Export') }}
                </button>
                <button @click="activeTab = 'import'" 
                    :class="activeTab === 'import' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                    class="px-4 py-1.5 text-sm font-medium rounded-md transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                    {{ __('Import') }}
                </button>
            </div>
        </div>

        <div>
            <!-- EXPORT SECTION -->
            <div x-show="activeTab === 'export'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="max-w-3xl mx-auto">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 mb-4 ring-8 ring-indigo-50/50 dark:ring-indigo-900/10">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ __('Export Data') }}</h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">{{ __('Download attendance records in Excel format.') }}</p>
                    </div>

                    <form wire:submit.prevent="export" class="rounded-2xl p-4 sm:p-0 space-y-8">
                        
                        <!-- Period Selector -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ __('Select Period') }}
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Start Date') }}</label>
                                    <input type="date" id="start_date" wire:model.live="start_date" class="form-input w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-shadow cursor-pointer">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('End Date') }}</label>
                                    <input type="date" id="end_date" wire:model.live="end_date" class="form-input w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-shadow cursor-pointer">
                                </div>
                            </div>
                            @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Advanced Filters Trigger -->
                        <div x-data="{ expanded: false }" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <button type="button" @click="expanded = !expanded" class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors focus:outline-none">
                                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                {{ __('Advanced Filters') }}
                            </button>

                            <div x-show="expanded" x-collapse class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div class="space-y-1.5">
                                     <label for="division" class="text-xs font-semibold text-gray-500 uppercase block">{{ __('Division') }}</label>
                                     <select id="division" wire:model.live="division" class="form-select w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-shadow cursor-pointer text-sm">
                                        <option value="">{{ __('All Divisions') }}</option>
                                        @foreach($divisions as $div)
                                            <option value="{{ $div->id }}">{{ $div->name }}</option>
                                        @endforeach
                                     </select>
                                </div>
                                <div class="space-y-1.5">
                                     <label for="jobTitle" class="text-xs font-semibold text-gray-500 uppercase block">{{ __('Job Title') }}</label>
                                     <select id="jobTitle" wire:model.live="job_title" class="form-select w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-shadow cursor-pointer text-sm">
                                        <option value="">{{ __('All Job Titles') }}</option>
                                        @foreach($jobTitles as $job)
                                            <option value="{{ $job->id }}">{{ $job->name }}</option>
                                        @endforeach
                                     </select>
                                </div>
                                <div class="space-y-1.5">
                                     <label for="education" class="text-xs font-semibold text-gray-500 uppercase block">{{ __('Education') }}</label>
                                     <select id="education" wire:model.live="education" class="form-select w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-shadow cursor-pointer text-sm">
                                        <option value="">{{ __('All Educations') }}</option>
                                        @foreach($educations as $edu)
                                            <option value="{{ $edu->id }}">{{ $edu->name }}</option>
                                        @endforeach
                                     </select>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4">
                             @if($previewing && $mode == 'export')
                                <x-secondary-button type="button" wire:click="preview" class="w-full sm:w-auto justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ __('Preview Data') }}
                                </x-secondary-button>
                            @endif
                            <x-button class="w-full sm:w-auto justify-center px-8 py-3 text-base shadow-lg shadow-indigo-500/20" wire:loading.attr="disabled">
                                {{ __('Export to Excel') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- IMPORT SECTION -->
            <div x-show="activeTab === 'import'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <div class="max-w-2xl mx-auto">
                    <div class="text-center mb-8">
                        <div class="bg-purple-50 dark:bg-purple-900/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Import Attendance Data') }}</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">
                            {{ __('Upload an Excel file to bulk import attendance records.') }} <br>
                            <button type="button" wire:click="downloadTemplate" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center gap-1 mt-1 outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 rounded-sm">
                                {{ __('Download Template') }}
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </button>
                        </p>
                    </div>

                    <form x-data="{ file: null, dragging: false }" 
                        @drop.prevent="dragging = false; file = $event.dataTransfer.files[0]; $refs.file.files = $event.dataTransfer.files; $wire.upload('file', file)"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        wire:submit.prevent="import" class="space-y-6">
                        
                        <!-- File Dropzone -->
                        <div class="relative group">
                             <div :class="{'border-primary-500 bg-primary-50 dark:bg-primary-900/10': dragging, 'border-gray-300 dark:border-gray-600': !dragging}"
                                  class="border-2 border-dashed rounded-xl p-8 text-center transition-all duration-200 cursor-pointer hover:border-primary-400 dark:hover:border-primary-500"
                                  @click="$refs.file.click()">
                                  
                                <input type="file" class="hidden" x-ref="file" wire:model.live="file" 
                                       x-on:change="file = $refs.file.files[0]">
                                
                                <template x-if="!file">
                                    <div>
                                        <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Click to upload or drag and drop') }}</p>
                                        <p class="text-xs text-gray-400 mt-1">XLSX, CSV (Max 10MB)</p>
                                    </div>
                                </template>
                                
                                <template x-if="file">
                                    <div>
                                        <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <p class="mt-2 text-sm font-medium text-gray-900 dark:text-white" x-text="file.name"></p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="(file.size / 1024).toFixed(2) + ' KB'"></p>
                                        <button type="button" @click.stop="file = null; $refs.file.value = null; $wire.set('file', null)" class="text-red-500 text-xs mt-2 hover:underline">{{ __('Remove file') }}</button>
                                    </div>
                                </template>
                             </div>
                        </div>

                        <!-- Import Button -->
                        @php
                            $lockedIcon = \App\Helpers\Editions::reportingLocked() ? ' 🔒' : '';
                        @endphp
                        @if(\App\Helpers\Editions::reportingLocked()) 
                             <x-danger-button class="w-full justify-center py-3" type="button" @click.prevent="$dispatch('feature-lock', { title: 'Import Locked', message: 'Importing Attendance is an Enterprise Feature 🔒. Please Upgrade.' })">
                                {{ __('Import Data') }} {{ $lockedIcon }}
                             </x-danger-button>
                        @else
                            <div x-show="file">
                                 <x-danger-button class="w-full justify-center py-3" wire:click="import" wire:loading.attr="disabled" wire:target="import">
                                    <svg class="w-4 h-4 mr-2" wire:loading.remove wire:target="import" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <svg class="w-4 h-4 mr-2 animate-spin" wire:loading wire:target="import" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ __('Submit Import') }}
                                 </x-danger-button>
                            </div>
                        @endif
                    </form>
                </div>

                @if(!empty($importErrors))
                    <div class="max-w-2xl mx-auto mt-6">
                        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-r-md">
                            <h3 class="text-sm font-bold text-red-800 dark:text-red-200 mb-2">{{ __('Import Errors') }}:</h3>
                            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1 max-h-48 overflow-y-auto">
                                @foreach($importErrors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Import Result Summary --}}
    @if($importResult)
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 shadow-lg rounded-2xl overflow-hidden mt-6 border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('Import Result') }}
                    </h4>
                    <button type="button" wire:click="$set('importResult', null)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    {{-- Success Count --}}
                    <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl p-6 text-center text-white shadow-lg">
                        <div class="text-4xl font-bold">{{ $importResult['imported'] }}</div>
                        <div class="text-sm opacity-90 mt-1">{{ __('Success') }}</div>
                    </div>
                    
                    {{-- Failed Count --}}
                    <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-xl p-6 text-center text-white shadow-lg">
                        <div class="text-4xl font-bold">{{ $importResult['skipped'] }}</div>
                        <div class="text-sm opacity-90 mt-1">{{ __('Skipped') }}</div>
                    </div>
                </div>

                @if(!empty($importErrors))
                    <details class="mt-6 group">
                        <summary class="cursor-pointer text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 flex items-center gap-2 select-none">
                            <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            {{ __('Show Error Details') }} ({{ count($importErrors) }})
                        </summary>
                        <ul class="mt-3 list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1 max-h-40 overflow-y-auto bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                            @foreach($importErrors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </details>
                @endif
            </div>
        </div>
    @endif

    @if ($mode && $previewing)
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="bg-gray-50/50 dark:bg-gray-800/50 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-500">{{ __('Preview') . ' ' . $mode }}</h4>
        </div>
        
        @if($mode == 'import' && $skippedRows > 0)
            <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 mx-6 mt-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                            {{ __('Warning') }}: <span class="font-bold">{{ $skippedRows }}</span> {{ __('rows were skipped (Invalid NIP or Duplicate Date).') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-scroll">
            @php
                $thClass = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-gray-50 dark:bg-gray-700 dark:text-gray-300';
                $tdClass = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 border-b border-gray-100 dark:border-gray-700';
            @endphp
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="{{ $thClass }}">{{ __('No.') }}</th>
                        <th class="{{ $thClass }}">{{ __('Date') }}</th>
                        <th class="{{ $thClass }}">{{ __('Name') }}</th>
                        <th class="{{ $thClass }}">{{ __('NIP') }}</th>
                        <th class="{{ $thClass }} text-nowrap">{{ __('Time In') }}</th>
                        <th class="{{ $thClass }} text-nowrap">{{ __('Time Out') }}</th>
                        <th class="{{ $thClass }}">{{ __('Shift') }}</th>
                        <th class="{{ $thClass }} text-nowrap">{{ __('Barcode Id') }}</th>
                        <th class="{{ $thClass }}">{{ __('Coordinates') }}</th>
                        <th class="{{ $thClass }}">{{ __('Status') }}</th>
                        <th class="{{ $thClass }}">{{ __('Note') }}</th>
                        <th class="{{ $thClass }}">{{ __('Attachment') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($attendances as $attendance)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="{{ $tdClass }} text-gray-500 text-center">{{ $loop->iteration }}</td>
                            <td class="{{ $tdClass }} text-nowrap">{{ $attendance->date?->format('Y-m-d') }}</td>
                            <td class="{{ $tdClass }} font-medium">{{ $attendance->user?->name }}</td>
                            <td class="{{ $tdClass }} font-mono text-xs">{{ $attendance->user?->nip }}</td>
                            <td class="{{ $tdClass }} font-mono text-xs">{{ $attendance->time_in?->format('H:i:s') }}</td>
                            <td class="{{ $tdClass }} font-mono text-xs">{{ $attendance->time_out?->format('H:i:s') }}</td>
                            <td class="{{ $tdClass }} text-nowrap">{{ $attendance->shift?->name }}</td>
                            <td class="{{ $tdClass }} font-mono text-xs">{{ $attendance->barcode_id }}</td>
                            <td class="{{ $tdClass }}">
                                @if($attendance->latitude_in && $attendance->longitude_in)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $attendance->latitude_in }},{{ $attendance->longitude_in }}" target="_blank" class="text-blue-600 hover:text-blue-900 underline text-xs font-semibold">IN</a>
                                @endif
                                @if($attendance->latitude_out && $attendance->longitude_out)
                                    <span class="text-gray-300 mx-1">|</span>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $attendance->latitude_out }},{{ $attendance->longitude_out }}" target="_blank" class="text-blue-600 hover:text-blue-900 underline text-xs font-semibold">OUT</a>
                                @endif
                            </td>
                            <td class="{{ $tdClass }}">
                                <span class="px-2 py-1 rounded text-xs {{ 
                                    $attendance->status === 'present' ? 'bg-green-100 text-green-700' : 
                                    ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') 
                                }}">
                                    {{ __($attendance->status) }}
                                </span>
                            </td>
                            <td class="{{ $tdClass }}">
                                <div class="w-48 truncate" title="{{ $attendance->note }}">{{ $attendance->note }}</div>
                            </td>
                            <td class="{{ $tdClass }}">
                                @if ($attendance->attachment_url && is_string($attendance->attachment_url))
                                    <a href="{{ $attendance->attachment_url }}" target="_blank" class="block w-10 h-10 rounded overflow-hidden border border-gray-200 hover:border-blue-400 transition-colors">
                                        <img src="{{ $attendance->attachment_url }}" class="w-full h-full object-cover">
                                    </a>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4 p-4">
            @foreach ($attendances as $attendance)
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-600">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $attendance->user?->name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $attendance->user?->nip }}</p>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-bold {{ 
                            $attendance->status === 'present' ? 'bg-green-100 text-green-700' : 
                            ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-700') 
                        }}">
                            {{ __($attendance->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                        <div>
                            <p class="text-gray-500 text-xs uppercase">{{ __('Date') }}</p>
                            <p class="font-medium dark:text-gray-200">{{ $attendance->date?->format('Y-m-d') }}</p>
                        </div>
                        <div>
                             <p class="text-gray-500 text-xs uppercase">{{ __('Shift') }}</p>
                             <p class="font-medium dark:text-gray-200">{{ $attendance->shift?->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs mb-3 border-t border-gray-200 dark:border-gray-600 pt-3">
                        <div>
                            <span class="text-gray-500">IN:</span>
                            <span class="font-mono font-semibold text-gray-700 dark:text-gray-300 ml-1">{{ $attendance->time_in?->format('H:i') ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">OUT:</span>
                             <span class="font-mono font-semibold text-gray-700 dark:text-gray-300 ml-1">{{ $attendance->time_out?->format('H:i') ?? '-' }}</span>
                        </div>
                    </div>
                    
                    @if($attendance->note || $attendance->attachment_url)
                        <div class="flex items-center gap-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                            @if($attendance->note)
                                <p class="text-xs text-gray-600 dark:text-gray-400 italic truncate flex-1">{{ $attendance->note }}</p>
                            @endif
                            @if ($attendance->attachment_url && is_string($attendance->attachment_url))
                                <a href="{{ $attendance->attachment_url }}" target="_blank" class="text-blue-500 hover:text-blue-600 text-xs font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                    {{ __('View') }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
