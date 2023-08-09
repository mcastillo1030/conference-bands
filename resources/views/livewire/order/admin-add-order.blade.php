<div class="mt-10 sm:mt-0">
    <x-form-section submit="adminAddOrder">
        <x-slot name="title">
            {{ __('Add Order') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Add a new order to the system. This is usually not necessary but can be used for when the user is having issues.') }}
        </x-slot>

        <x-slot name="form">
            @if ($hasBracelets)
                <div class="col-span-6">
                    <div class="max-w-xl text-sm text-gray-600">
                        {{ __('Please provide either a phone number on an email address.') }}
                    </div>
                </div>

                <!-- Bracelet Customer Name -->
                <div class="col-span-6 sm:col-span-4 flex gap-x-3 sm:gap-x-2.5">
                    <div class="grow">
                        <x-label for="firstName" value="{{ __('First Name') }}" />
                        <x-input wire:model="firstName" type="text" class="mt-1 block w-full" />
                        <x-input-error for="firstName" class="mt-2" />
                    </div>
                    <div class="grow">
                        <x-label for="lastName" value="{{ __('Last Name') }}" />
                        <x-input wire:model="lastName" type="text" class="mt-1 block w-full" />
                        <x-input-error for="lastName" class="mt-2" />
                    </div>
                </div>
                <!-- Customer Email -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="email" value="{{ __('Email Address') }}" />
                    <x-input wire:model="email" id="email" type="email" class="mt-1 block w-full" />
                    <x-input-error for="email" class="mt-2" />
                </div>
                <!-- Customer Phone -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="phone" value="{{ __('Phone Number') }}" />
                    <x-input wire:model="phone" id="phone" type="text" class="mt-1 block w-full" />
                    <x-input-error for="phone" class="mt-2" />
                </div>
                <!-- Order Bracelet(s) -->
                <div class="col-span-6">
                    <x-label class="mb-3" value="{{ __('Bracelets') }}" />
                    @foreach ($bracelets as $index => $bracelet)
                        <div class="grid grid-cols-5 gap-x-4 sm:gap-x-2 items-end">
                            <div class="col-span-2 relative pb-[28px]">
                                <x-label class="text-xs text-slate-500" for="bracelets.{{$index}}.number" value="{{ __('Bracelet Number') }}" />
                                <x-input
                                    type="tel"
                                    wire:model="bracelets.{{$index}}.number"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                    maxlength="4"
                                    pattern="\d{1,4}"
                                    class="mt-1 block w-full"
                                />
                                <x-input-error for="bracelets.{{$index}}.number" class="mt-2 absolute bottom-0 w-full" />
                            </div>
                            <div class="col-span-2 relative pb-[28px]">
                                <x-label class="text-xs text-slate-500" for="bracelets.{{$index}}.name" value="{{ __('Bracelet Name (optional)') }}" />
                                <x-input type="text" wire:model="bracelets.{{$index}}.name" class="mt-1 block w-full" />
                                <x-input-error for="bracelets.{{$index}}.name" class="mt-2 absolute bottom-0 w-full" />
                            </div>
                            <div class="col-span-1 justify-self-start">
                                {{-- <x-button type="button" wire:click="removeBracelet({{ $loop->index }})" class="mb-[28px] block w-full bg-transparent border-gray-800 hover:bg-gray-300 focus:bg-gray-400" style="color: rgb(31 41 55)">
                                    <span class="sm:hidden">-</span>
                                    <span class="hidden sm:block">Remove</span>
                                </x-button> --}}
                                <x-secondary-button wire:click.prevent="removeBracelet({{ $loop->index }})" class="mb-[28px] block w-full">
                                    <span class="sm:hidden">-</span>
                                    <span class="hidden sm:block">Remove</span>
                                </x-secondary-button>
                            </div>
                        </div>
                    @endforeach
                    <div class="grid grid-cols-5 gap-x-4 sm:gap-x-2 items-end">
                        <div class="col-span-2 relative pb-[28px]">
                            <x-label class="text-xs text-slate-500" for="clone.number" value="{{ __('Bracelet Number') }}" />
                            <x-input
                                type="tel"
                                wire:model="clone.number"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                maxlength="4"
                                pattern="\d{1,4}"
                                class="mt-1 block w-full"
                            />
                            <x-input-error for="clone.number" class="mt-2 absolute bottom-0 w-full" />
                        </div>
                        <div class="col-span-2 relative pb-[28px]">
                            <x-label class="text-xs text-slate-500" for="clone.name" value="{{ __('Bracelet Name (optional)') }}" />
                            <x-input type="text" wire:model="clone.name" class="mt-1 block w-full" />
                            <x-input-error for="clone.name" class="mt-2 absolute bottom-0 w-full" />
                        </div>
                        <div class="col-span-1 justify-self-start">
                            {{-- <x-button type="button" wire:click.prevent="addBracelet" class="mb-[28px] block w-full bg-transparent border-gray-800 hover:bg-gray-300 focus:bg-gray-400" style="color: rgb(31 41 55)">
                                <span class="sm:hidden">+</span>
                                <span class="hidden sm:block">Add</span>
                            </x-button> --}}
                            <x-secondary-button wire:click.prevent="addBracelet" class="mb-[28px] block w-full">
                                <span class="sm:hidden">+</span>
                                <span class="hidden sm:block">Add</span>
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-span-6">
                    <div class="max-w-xl text-sm text-gray-600">
                        New orders cannot be created because bracelets have <strong>not</strong> been added to the system yet. <a href="{{route('bracelets.dashboard')}}" class="font-semibold text-indigo-700">Add bracelets here</a>.
                    </div>
                </div>
            @endif
        </x-slot>

        @if ($hasBracelets)
            <x-slot name="actions">
                <x-action-message class="mr-3" on="orderSaved">
                    <span class="text-indigo-700">{{ __('Order Saved.') }}</span>
                </x-action-message>

                <x-button>
                    {{ __('Save Order') }}
                </x-button>
            </x-slot>
        @endif
    </x-form-section>
</div>
