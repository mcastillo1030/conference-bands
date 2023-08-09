<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="mt-10 sm:mt-0">
            <p class="text-sm">
                <a href="{{ route('bracelets.index') }}" class="inline-flex items-center font-semibold text-indigo-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ml-1 w-5 h-5 fill-indigo-500 origin-center -rotate-180">
                        <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                    </svg>
                    All Bracelets
                </a>
            </p>
            <!-- Bracelet Status -->
            <x-action-section class="mt-10">
                <x-slot name="title">
                    {{ __('Status') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Status of the bracelet.') }}
                </x-slot>

                <x-slot name="content">
                    <h4 class="relative font-semibold mb-3">Bracelet Number: <span class="font-thin">{{$bracelet->number}}</span></h4>

                    <div wire:model="status">
                        <x-label class="mb-1 relative">
                            <x-slot name="value">
                                <span>Status</span>
                                <button type="button" data-pops="bracelet-status-help" data-placement="top" class="inline-flex items-center border-transparent outline-none p-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                        <path d="M12 17h.01"/>
                                    </svg>
                                </button>
                                <x-tooltip id="bracelet-status-help">
                                    <p><strong>System:</strong> Only registered in the system. Not sold or not registered.</p>
                                    <p><strong>Reserved:</strong> Reserved for an online order.</p>
                                    <p><strong>Registered:</strong> Registered by the customer.</p>
                                </x-tooltip>
                            </x-slot>
                        </x-label>

                        <div class="flex">
                            <x-dropdown align="left" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" aria-expanded="false" class="inline-flex items-center px-3 py-2 capitalize border border-slate-500 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            {{$status}}
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-60">
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Bracelet Status') }}
                                        </div>

                                        @foreach (config('constants.bracelet_statuses') as $s)
                                            <span class="flex rounded-md">
                                                <button type="button" wire:click="$emit('updateStatus', '{{$s}}')" class="inline-flex items-center px-3 py-2 capitalize border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                    {{$s}}
                                                </button>
                                            </span>
                                        @endforeach

                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <x-button wire:click="saveStatus" :disabled="$status === $bracelet->status" :class="$status !== $bracelet->status ? '' : 'cursor-not-allowed'">
                            {{ __('Save') }}
                        </x-button>
                    </div>
                </x-slot>
            </x-action-section>

            <!-- Bracelet Details -->
            <x-form-section class="mt-10" submit="updateDetails">
                <x-slot name="title">
                    {{ __('Details') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Basic bracelet details.') }}
                </x-slot>

                <x-slot name="form">
                    <!-- Bracelet Order -->
                    <div class="col-span-6 sm:col-span-4">
                        <x-label value="{{ __('Related Order') }}" />
                        @if ($bracelet->order)
                            <a href="{{route('orders.show', $bracelet->order)}}" class="text-blue-500 hover:text-blue-700">
                                {{$bracelet->order->number}}
                            </a>
                        @else
                            <span class="text-sm text-gray-400">Not linked to an order yet.</span>
                        @endif
                    </div>

                    <!-- Bracelet Name -->
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="name" value="{{ __('Bracelet Name') }}" />

                        <x-input id="name"
                            type="text"
                            class="mt-1 block w-full"
                            wire:model.defer="bracelet.name"
                            :disabled="!Gate::check('bracelets:update', $bracelet) or $bracelet->status !== 'registered'"
                        />

                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <!-- Bracelet Group -->
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="group" value="{{ __('Bracelet Group') }}" />

                        <x-input id="group"
                            type="text"
                            class="mt-1 block w-full"
                            wire:model.defer="bracelet.group"
                            :disabled="! Gate::check('bracelets:update', $bracelet)"
                        />

                        <x-input-error for="group" class="mt-2" />
                    </div>
                </x-slot>
                @if (Gate::check('bracelets:update', $bracelet))
                    <x-slot name="actions">
                        <x-action-message class="mr-3" on="saved">
                            {{ __('Saved.') }}
                        </x-action-message>

                        <x-button>
                            {{ __('Save') }}
                        </x-button>
                    </x-slot>
                @endif
            </x-form-section>

            @can('bracelets:delete', $bracelet)
                <!-- Delete Bracelet -->
                <x-action-section class="mt-10">
                    <x-slot name="title">
                        {{ __('Delete Bracelet') }}
                    </x-slot>

                    <x-slot name="description">
                        {{ __('Permanently delete this bracelet.') }}
                    </x-slot>

                    <x-slot name="content">
                        <div class="max-w-xl text-sm text-gray-600">
                            {{ __('Once a bracelet is deleted, all of its resources and data will be permanently deleted. Before deleting this bracelet, please download any data or information regarding this team that you wish to retain.') }}
                        </div>

                        <div class="mt-5">
                            <x-danger-button wire:click="$toggle('confirmingBraceletDeletion')" wire:loading.attr="disabled">
                                {{ __('Delete Bracelet') }}
                            </x-danger-button>
                        </div>

                        <!-- Delete Team Confirmation Modal -->
                        <x-confirmation-modal wire:model="confirmingBraceletDeletion">
                            <x-slot name="title">
                                {{ __('Delete Bracelet') }}
                            </x-slot>

                            <x-slot name="content">
                                {{ __('Are you sure you want to delete this bracelet? Once a bracelet is deleted, all of its resources and data will be permanently deleted.') }}
                            </x-slot>

                            <x-slot name="footer">
                                <x-secondary-button wire:click="$toggle('confirmingBraceletDeletion')" wire:loading.attr="disabled">
                                    {{ __('Cancel') }}
                                </x-secondary-button>

                                <x-danger-button class="ml-3" wire:click="deleteBracelet" wire:loading.attr="disabled">
                                    {{ __('Delete Bracelet') }}
                                </x-danger-button>
                            </x-slot>
                        </x-confirmation-modal>
                    </x-slot>
                </x-action-section>
            @endcan
        </div>
    </div>
</div>
