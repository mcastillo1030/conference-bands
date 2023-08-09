<div class="mt-10 sm:mt-0">
    <x-form-section submit="addBracelets">
        <x-slot name="title">
            {{ __('Add Bracelets') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Add a new bracelet to the system so users can register it.') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6">
                <div class="max-w-xl text-sm text-gray-600">
                    {{ __('Please provide the starting bracelet number. If you\'re only adding one bracelet, leave the "Ending Number" blank. Also note that existing bracelet numbers will be skipped.') }}
                </div>
            </div>

            <!-- Bracelet Starting Number -->
            <div class="col-span-6 sm:col-span-4 flex gap-x-3 sm:gap-x-2.5">
                <div class="w-1/2">
                    <x-label for="start_number" value="{{ __('Start #') }}" />
                    <x-input wire:model="start_number" id="start_number" type="number" min="1" max="9999" class="mt-1 block w-full" />
                    <x-input-error for="start_number" class="mt-2" />
                </div>
                <div class="w-1/2">
                    <x-label for="end_number" value="{{ __('End #') }}" />
                    <x-input wire:model="end_number" id="end_number" type="number" min="1" max="9999" class="mt-1 block w-full" />
                    <x-input-error for="end_number" class="mt-2" />
                </div>
            </div>
            <!-- Bracelet Group -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="group" value="{{ __('Group (optional)') }}" />
                <x-input wire:model="group" id="group" type="text" class="mt-1 block w-full" />
                <x-input-error for="group" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="mr-3" on="braceletsAdded">
                <span class="text-indigo-700">{{ __('Added Bracelets.') }}</span>
            </x-action-message>

            <x-button>
                {{ __('Add') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
