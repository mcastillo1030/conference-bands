<div>
    <form wire:submit.prevent="createOrder">
        <x-honeypot livewire-model="extraFields" />
        <div class="mt-8 grid grid-cols-6 gap-6">
            @if ($canCreate)
                <!-- Name -->
                <div class="col-span-6 flex flex-col sm:flex-row gap-2.5 sm:gap-3">
                    <div class="grow">
                        <x-guest-label for="firstName" value="{{ __('First Name')}}" />
                        <x-guest-input wire:model.defer="firstName" type="text" class="mt-1 w-full" />
                        <x-guest-input-error for="firstName" class="mt-2" />
                    </div>
                    <div class="grow">
                        <x-guest-label for="lastName" value="{{ __('Last Name')}}" />
                        <x-guest-input wire:model.defer="lastName" type="text" class="mt-1 w-full" />
                        <x-guest-input-error for="lastName" class="mt-2" />
                    </div>
                </div>
                <!-- Email -->
                <div class="col-span-6">
                    <x-guest-label for="email" value="{{ __('Email Address')}}" />
                    <x-guest-input wire:model.defer="email" required id="email" type="email" class="mt-1 w-full" />
                    <x-guest-input-error for="email" class="mt-2" />
                </div>
                <!-- Phone -->
                <div class="col-span-6">
                    <x-guest-label for="phone" value="{{ __('Phone Number')}}" />
                    <x-guest-input
                        wire:model.defer="phone"
                        id="phone"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');"
                        maxlength="12"
                        type="text"
                        class="mt-1 w-full"
                    />
                    <x-guest-input-error for="phone" class="mt-2" />
                </div>
                <div class="col-span-6">
                    <x-guest-label class="mb-3 uppercase" value="{{ __('Bracelets') }}" />
                    @foreach ($bracelets as $index => $bracelet)
                        <div class="mb-3 grid grid-cols-1 xl:grid-cols-5 xl:gap-4 gap-3 xl:items-end p-2 xl:p-3.5 rounded border-[1px] border-gravel-200 dark:border-ash-400">
                            <div class="xl:col-span-2 relative">
                                <x-guest-label for="bracelets.{{$index}}.number" value="{{ __('Bracelet Number') }}" />
                                <x-guest-input
                                    type="tel"
                                    wire:model.defer="bracelets.{{$index}}.number"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                    maxlength="4"
                                    pattern="\d{1,4}"
                                    class="mt-1 block w-full"
                                />
                                <x-guest-input-error for="bracelets.{{$index}}.number" class="mt-2 w-full" />
                            </div>
                            <div class="xl:col-span-2 relative">
                                <x-guest-label for="bracelets.{{$index}}.name" value="{{ __('Bracelet Name (optional)') }}" />
                                <x-guest-input type="text" wire:model.defer="bracelets.{{$index}}.name" class="mt-1 block w-full" />
                                <x-guest-input-error for="bracelets.{{$index}}.name" class="mt-2 w-full" />
                            </div>
                            <div class="col-span-1 justify-self-start">
                                <x-guest-button wire:click.prevent="removeBracelet({{ $loop->index }})" class="block w-full">Remove</x-guest-button>
                            </div>
                        </div>
                    @endforeach
                    <div class="grid grid-cols-1 xl:grid-cols-5 xl:gap-4 gap-3 xl:items-end p-2 xl:p-3.5 rounded border-[1px] border-gravel-200 dark:border-ash-400">
                        <div class="xl:col-span-2 relative">
                            <x-guest-label for="clone.number" value="{{ __('Bracelet Number') }}" />
                            <x-guest-input
                                type="tel"
                                wire:model.defer="clone.number"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                maxlength="4"
                                pattern="\d{1,4}"
                                class="mt-1 block w-full"
                            />
                            <x-guest-input-error for="clone.number" class="mt-2 w-full" />
                        </div>
                        <div class="xl:col-span-2 relative">
                            <x-guest-label for="clone.name" value="{{ __('Bracelet Name (optional)') }}" />
                            <x-guest-input type="text" wire:model.defer="clone.name" class="mt-1 block w-full" />
                            <x-guest-input-error for="clone.name" class="mt-2 w-full" />
                        </div>
                        <div class="mt-3 xl:mt-0 xl:col-span-1 justify-self-start">
                            <x-guest-button wire:click.prevent="addBracelet" class="block w-full ">Add</x-guest-button>
                        </div>
                    </div>
                </div>
            @else
            <div class="col-span-6">
                <p class="max-w-xl text-sm text-gravel-700 dark:text-ash-500">We cannot accept new registrations right now. Please come back later.</p>
            </div>
            @endif
        </div>

        @if ($canCreate)
            <div class="flex items-center justify-end my-5 py-3 text-right">
                <x-action-message class="mr-3" on="orderCreated">
                    <span class="text-tangerine-200 dark:text-maize-600">{{ __('Registered. Check your email for confirmation. Thanks!') }}</span>
                </x-action-message>

                <x-guest-submit>
                    {{ __('Register') }}
                </x-guest-submit>
            </div>
        @endif
    </form>
    @if ($canCreate)
        <x-guest-dialog wire:model="confirmingOrderCreation">
            <x-slot name="title">{{ __('Thanks for Registering!') }}</x-slot>
            <x-slot name="content">
                <p class="text-base text-gravel-700 dark:text-ash-500">Check your email for a confirmation. Hang on to those details for your records.</p>
                <p class="text-base mt-3 text-gravel-700 dark:text-ash-500">We look forward to seeing you!</p>
            </x-slot>
            <x-slot name="footer">
                <x-guest-submit type="button" wire:loading.attr="disabled" wire:click.prevent="$toggle('confirmingOrderCreation')">OK</x-guest-button>
            </x-slot>
        </x-guest-dialog>
    @endif
</div>
