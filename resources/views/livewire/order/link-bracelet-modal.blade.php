<div>
    <x-dialog-modal id="order-link-bracelet" max-width="lg" wire:model="showModal">
        <x-slot name="title">
            {{ __('Link New Bracelet') }}
        </x-slot>

        <x-slot name="content">
            <div class="sm:grid sm:grid-cols-4 gap-4 sm:p-2 p-0">
                <x-label class="mb-3 w-full sm:col-span-4" value="{{ __('Bracelet Info') }}" />
                <div class="col-span-2 relative pb-[28px]">
                    <x-label class="text-xs text-slate-500" for="number" value="{{ __('Bracelet Number') }}" />
                    <x-input
                        type="tel"
                        wire:model="number"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                        maxlength="4"
                        pattern="\d{1,4}"
                        class="mt-1 block w-full"
                    />
                    <x-input-error for="number" class="mt-2 w-full" />
                </div>
                <div class="col-span-2 relative pb-[28px]">
                    <x-label class="text-xs text-slate-500" for="name" value="{{ __('Bracelet Name (optional)') }}" />
                    <x-input type="text" wire:model="name" class="mt-1 block w-full" />
                    <x-input-error for="name" class="mt-2 w-full" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button type="button" wire:click.prevent="cancelClick">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button type="button" class="ml-3" wire:click.prevent="linkBracelet">
                {{ __('Link') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
