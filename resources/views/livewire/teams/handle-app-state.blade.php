<div>
    <x-section-border />
    <div class="mt-10 sm:mt-0">
        <x-form-section submit="saveMessage">
            <x-slot name="title">
                {{ __('Handle App State') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Handle whether app is avaialble to the public or not.') }}
            </x-slot>

            <x-slot name="form">
                <div class="col-span-6">
                    <div class="max-w-xl text-sm text-gray-600">
                        {{ __('If enabled, allows bracelet registration. Otherwise, displays the message below.') }}
                    </div>
                </div>

                <!-- App State Toggle -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="appState" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">App {{$appState ? 'Enabled' : 'Disabled'}}</span>
                    </x-label>
                    <x-label class="relative mt-4">
                        <span class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Message</span>
                        <textarea id="message" wire:model="appMessage" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here..."></textarea>
                    </x-label>
                </div>

                <!-- Role -->
                {{-- @if (count($this->roles) > 0)
                    <div class="col-span-6 lg:col-span-4">
                        <x-label for="role" value="{{ __('Role') }}" />
                        <x-input-error for="role" class="mt-2" />

                        <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                            @foreach ($this->roles as $index => $role)
                                <button type="button" class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 {{ $index > 0 ? 'border-t border-gray-200 focus:border-none rounded-t-none' : '' }} {{ ! $loop->last ? 'rounded-b-none' : '' }}"
                                                wire:click="$set('addTeamMemberForm.role', '{{ $role->key }}')">
                                    <div class="{{ isset($addTeamMemberForm['role']) && $addTeamMemberForm['role'] !== $role->key ? 'opacity-50' : '' }}">
                                        <!-- Role Name -->
                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-600 {{ $addTeamMemberForm['role'] == $role->key ? 'font-semibold' : '' }}">
                                                {{ $role->name }}
                                            </div>

                                            @if ($addTeamMemberForm['role'] == $role->key)
                                                <svg class="ml-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </div>

                                        <!-- Role Description -->
                                        <div class="mt-2 text-xs text-gray-600 text-left">
                                            {{ $role->description }}
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif --}}
            </x-slot>

            <x-slot name="actions">
                <x-action-message class="mr-3" on="messageSaved">
                    {{ __('Message Saved.') }}
                </x-action-message>

                <x-button>
                    {{ __('Save Message') }}
                </x-button>
            </x-slot>
        </x-form-section>
    </div>
</div>
