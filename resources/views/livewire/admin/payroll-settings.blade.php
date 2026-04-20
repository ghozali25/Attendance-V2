<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Payroll Configurations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    


                    {{-- Header Actions --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                        <div class="w-full sm:w-auto">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Components') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage allowances, deductions, and tax rules.') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                             <x-input type="text" wire:model.live="search" placeholder="{{ __('Search...') }}" class="text-sm" />
                             <x-button wire:click="create">
                                {{ __('Add Component') }}
                             </x-button>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Type') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Calculation') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Value') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Active') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @forelse ($components as $component)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $component->name }}</div>
                                            @if($component->is_taxable)
                                                <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">{{ __('Taxable') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $component->type === 'allowance' ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-red-50 text-red-700 ring-red-600/20' }}">
                                                {{ __(ucfirst($component->type)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ str_replace('_', ' ', ucfirst($component->calculation_type)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 font-mono">
                                            @if($component->calculation_type == 'percentage_basic')
                                                {{ $component->percentage }}%
                                            @else
                                                Rp {{ number_format($component->amount, 0, ',', '.') }}
                                                @if($component->calculation_type == 'daily_presence')
                                                    <span class="text-xs text-gray-500">/{{ __('day') }}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button wire:click="toggleActive({{ $component->id }})" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $component->is_active ? 'bg-indigo-600' : 'bg-gray-200' }}">
                                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $component->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2">
                                                <button wire:click="edit({{ $component->id }})" class="text-gray-400 hover:text-blue-600 transition-colors" title="{{ __('Edit') }}">
                                                    <x-heroicon-m-pencil-square class="h-5 w-5" />
                                                </button>
                                                <button wire:click="confirmDelete({{ $component->id }})" class="text-gray-400 hover:text-red-600 transition-colors" title="{{ __('Delete') }}">
                                                    <x-heroicon-m-trash class="h-5 w-5" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">{{ __('No components found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $components->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    <x-dialog-modal wire:model.live="showModal">
        <x-slot name="title">
            {{ $selectedId ? __('Edit Component') : __('Add New Component') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                {{-- Name --}}
                <div class="col-span-2">
                    <x-label for="name" value="{{ __('Component Name') }}" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="{{ __('e.g. Uang Makan') }}" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                {{-- Type --}}
                <div>
                    <x-label for="type" value="{{ __('Type') }}" />
                    <select id="type" wire:model.live="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="allowance">{{ __('Allowance (+)') }}</option>
                        <option value="deduction">{{ __('Deduction (-)') }}</option>
                    </select>
                </div>

                {{-- Calculation Type --}}
                <div>
                    <x-label for="calculation_type" value="{{ __('Calculation Method') }}" />
                    <select id="calculation_type" wire:model.live="calculation_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="fixed">{{ __('Fixed Amount') }}</option>
                        <option value="daily_presence">{{ __('Daily Rate (x Attendance)') }}</option>
                        <option value="percentage_basic">{{ __('% of Basic Salary') }}</option>
                    </select>
                </div>

                {{-- Amount / Percentage --}}
                <div class="col-span-2">
                    @if($calculation_type === 'percentage_basic')
                        <x-label for="percentage" value="{{ __('Percentage (%)') }}" />
                        <div class="relative mt-1">
                            <x-input id="percentage" type="number" step="0.01" class="block w-full pr-12" wire:model="percentage" placeholder="5.00" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        <x-input-error for="percentage" class="mt-2" />
                    @else
                        <x-label for="amount" value="{{ __('Amount (Rp)') }}" />
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <x-input id="amount" type="number" class="block w-full pl-12" wire:model="amount" placeholder="0" />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $calculation_type === 'daily_presence' ? __('Multiplied by days present.') : __('Fixed amount per month.') }}</p>
                        <x-input-error for="amount" class="mt-2" />
                    @endif
                </div>

                {{-- Taxable Toggle --}}
                <div class="col-span-2 flex items-center">
                    <x-checkbox id="is_taxable" wire:model="is_taxable" />
                    <div class="ml-2">
                        <x-label for="is_taxable" value="{{ __('Is Taxable Income?') }}" />
                        <p class="text-xs text-gray-500">{{ __('Enable if this component should be included in PPh 21 calculation base (Not fully implemented yet).') }}</p>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showModal', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    {{-- Delete Confirmation --}}
    <x-confirmation-modal wire:model.live="confirmingDeletion">
        <x-slot name="title">
            {{ __('Delete Component') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this component? This will not affect past payroll records, but will be removed from future calculations.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingDeletion', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
