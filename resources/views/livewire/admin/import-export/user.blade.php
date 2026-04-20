<div x-data="{ activeTab: 'export' }" class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
    
    <!-- Tabs Header -->
    <div class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/20 px-6 py-4 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            {{ __('Employee Data Management') }}
        </h3>
        
                <div class="flex bg-gray-200 dark:bg-gray-700 rounded-lg p-1">
            <button @click="activeTab = 'export'" 
                :class="activeTab === 'export' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                class="px-4 py-1.5 text-sm font-medium rounded-md transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Export
            </button>
            <button @click="activeTab = 'import'" 
                :class="activeTab === 'import' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                class="px-4 py-1.5 text-sm font-medium rounded-md transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                Import
            </button>
        </div>
    </div>

    <div class="p-6">
        
        <!-- EXPORT SECTION -->
        <div x-show="activeTab === 'export'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-xl mx-auto text-center mb-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                </div>
                <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Export Data') }}</h4>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">{{ __('Select the user groups you wish to export to Excel.') }}</p>
            </div>

            <div class="max-w-md mx-auto space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label class="relative flex items-start p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="min-w-0 flex-1 text-sm">
                            <span class="font-medium text-gray-900 dark:text-gray-100 block mb-1">{{ __('Employee') }}</span>
                            <span class="text-gray-500 text-xs">{{ __('Regular users') }}</span>
                        </div>
                        <div class="ml-3 flex items-center h-5">
                             <x-checkbox value="user" id="user" wire:model.live="groups" class="rounded-full" />
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="min-w-0 flex-1 text-sm">
                            <span class="font-medium text-gray-900 dark:text-gray-100 block mb-1">{{ __('Admin') }}</span>
                            <span class="text-gray-500 text-xs">{{ __('Managers') }}</span>
                        </div>
                        <div class="ml-3 flex items-center h-5">
                             <x-checkbox value="admin" id="admin" wire:model.live="groups" class="rounded-full" />
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="min-w-0 flex-1 text-sm">
                            <span class="font-medium text-gray-900 dark:text-gray-100 block mb-1">{{ __('Super') }}</span>
                            <span class="text-gray-500 text-xs">{{ __('Full Access') }}</span>
                        </div>
                        <div class="ml-3 flex items-center h-5">
                             <x-checkbox value="superadmin" id="superadmin" wire:model.live="groups" class="rounded-full" />
                        </div>
                    </label>
                </div>

                @error('groups')
                    <p class="text-sm text-red-600 text-center">{{ $message }}</p>
                @enderror
                
                <div class="pt-4 flex justify-center gap-3">
                     @php
                        $lockedIcon = \App\Helpers\Editions::reportingLocked() ? ' 🔒' : '';
                    @endphp
                    @if(\App\Helpers\Editions::reportingLocked()) 
                         <x-button class="w-full justify-center py-3" type="button" @click.prevent="$dispatch('feature-lock', { title: 'Export Locked', message: 'Exporting Users is an Enterprise Feature 🔒. Please Upgrade.' })">
                            {{ __('Export Selected') }} {{ $lockedIcon }}
                         </x-button>
                    @else
                        <x-button wire:click="export" class="w-full justify-center py-3 gap-2">
                             <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                             {{ __('Export Selected') }}
                        </x-button>
                    @endif
                </div>
            </div>
        </div>

        <!-- IMPORT SECTION -->
        <div x-show="activeTab === 'import'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
             
             <div class="max-w-2xl mx-auto">
                 <div class="text-center mb-8">
                    <div class="bg-purple-50 dark:bg-purple-900/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                    </div>
                    
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Import Data') }}</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">
                        {{ __('Upload an Excel file to bulk import or update users.') }} <br>
                        <a href="#" wire:click.prevent="downloadTemplate" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center gap-1 mt-1">
                            {{ __('Download Template') }}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
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
                    
                    @error('file')
                        <div class="p-2 text-sm text-red-600 bg-red-50 rounded-lg dark:bg-red-900/50 dark:text-red-400">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Progress Bar -->
                    <div wire:loading wire:target="import" class="w-full">
                         <div class="flex items-center gap-2 mb-2 text-sm text-gray-600 dark:text-gray-300">
                             <svg class="animate-spin h-4 w-4 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                             <span>Processing Import...</span>
                         </div>
                         <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                             <div class="bg-primary-600 h-2 rounded-full animate-progress-indeterminate"></div>
                         </div>
                    </div>

                    <!-- Import Button -->
                    @php
                        $lockedIcon = \App\Helpers\Editions::reportingLocked() ? ' 🔒' : '';
                    @endphp
                    @if(\App\Helpers\Editions::reportingLocked()) 
                         <x-danger-button class="w-full justify-center py-3" type="button" @click.prevent="$dispatch('feature-lock', { title: 'Import Locked', message: 'Importing Users is an Enterprise Feature 🔒. Please Upgrade.' })">
                            {{ __('Import Data') }} {{ $lockedIcon }}
                         </x-danger-button>
                    @else
                        <div x-show="file">
                             <x-danger-button class="w-full justify-center py-3" wire:loading.attr="disabled" wire:target="import">
                                {{ __('Start Import') }}
                             </x-danger-button>
                        </div>
                    @endif
                </form>

                @if(!empty($importErrors))
                <div class="mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-red-100 dark:bg-red-900/50 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-red-900 dark:text-red-100">{{ __('Import Completed with Issues') }}</h4>
                            <p class="text-sm text-red-600 dark:text-red-300">{{ count($importErrors) }} {{ __('rows were skipped due to validation errors. Valid rows were imported successfully.') }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-red-100 dark:border-red-900/50 overflow-hidden">
                        <table class="min-w-full divide-y divide-red-100 dark:divide-red-900/50">
                            <thead class="bg-red-50/50 dark:bg-red-900/20">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wider w-20">Row</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wider">Error Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-red-100 dark:divide-red-900/50">
                                @foreach($importErrors as $error)
                                <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-red-800 dark:text-red-200">
                                        Row {{ $error['row'] }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-red-600 dark:text-red-300">
                                        <ul class="list-disc list-inside">
                                            @foreach($error['errors'] as $msg)
                                                <li>{{ $msg }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
             </div>
        </div>

    </div>

    @if ($previewing && $users && $users->count() > 0)
    <div class="border-t border-gray-100 dark:border-gray-700">
        <div class="bg-gray-50/50 dark:bg-gray-800/50 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-500">{{ __('Preview Data') }}</h4>
        </div>
        <div class="overflow-x-auto">
             @php
                $thClass = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-gray-50 dark:bg-gray-700 dark:text-gray-300';
                $tdClass = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 border-b border-gray-100 dark:border-gray-700';
             @endphp
             <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                  <tr>
                    <th class="{{ $thClass }}">#</th>
                    <th class="{{ $thClass }}">NIP</th>
                    <th class="{{ $thClass }}">Name</th>
                    <th class="{{ $thClass }}">Email</th>
                    <th class="{{ $thClass }}">Group</th>
                    <th class="{{ $thClass }}">Phone</th>
                    <th class="{{ $thClass }}">Basic Salary</th>
                    <!-- Add other headers as needed, keeping it concise for preview -->
                    <th class="{{ $thClass }}">Role</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                  @foreach ($users->take(10) as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                      <td class="{{ $tdClass }} text-gray-500">{{ $loop->iteration }}</td>
                      <td class="{{ $tdClass }} font-mono text-xs">{{ $user->nip }}</td>
                      <td class="{{ $tdClass }} font-medium">{{ $user->name }}</td>
                      <td class="{{ $tdClass }} text-gray-500">{{ $user->email }}</td>
                      <td class="{{ $tdClass }}">
                          <span class="px-2 py-1 text-xs rounded-lg {{ $user->group === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                              {{ ucfirst($user->group) }}
                          </span>
                      </td>
                      <td class="{{ $tdClass }}">{{ $user->phone }}</td>
                      <td class="{{ $tdClass }} font-mono text-xs">{{ number_format($user->basic_salary, 0) }}</td>
                      <td class="{{ $tdClass }}">
                          <div class="text-xs">
                              <div class="font-medium">{{ $user->jobTitle?->name ?? '-' }}</div>
                              <div class="text-gray-500">{{ $user->division?->name ?? '-' }}</div>
                          </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
             </table>
             @if($users->count() > 10)
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-center text-xs text-gray-500 italic">
                    Showing first 10 rows of {{ $users->count() }} records...
                </div>
             @endif
        </div>
    </div>
    @endif
</div>
