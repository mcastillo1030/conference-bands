<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="mt-10 sm:mt-0">
            <p class="text-sm">
                <a href="{{ route('registrations.dashboard') }}" class="inline-flex items-center font-semibold text-indigo-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ml-1 w-5 h-5 fill-indigo-500 origin-center -rotate-180">
                        <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                    </svg>
                    All Registrations
                </a>
            </p>

            <x-action-section class="mt-10">
                <x-slot name="title">
                    {{ __('Registration Details') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Basic event registration details.') }}
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6">
                        <h4 class="relative font-semibold mb-3">Registration ID: <span class="font-thin">{{$registration->registration_id}}</span></h4>

                        <div class="col-span-6 flex flex-col sm:flex-row sm:gap-3 gap-2.5">
                            <div class="sm:w-1/2">
                                <x-label class="text-slate-400" value="{{ __('Created on:') }}" />
                                <span class="mt-1 block w-full">{{$registration->created_at->format('m/d/Y g:i A')}}</span>
                            </div>
                            <div class="sm:w-1/2">
                                <x-label class="text-slate-400" value="{{ __('Last Update') }}" />
                                <span class="mt-1 block w-full">{{$registration->updated_at->format('m/d/Y g:i A')}}</span>
                            </div>
                        </div>
                        <div class="col-span-6 flex flex-col sm:flex-row sm:gap-3 gap-2.5">
                            <div class="sm:w-1/2">
                                <x-label class="text-slate-400" value="{{ __('Event Name') }}" />
                                <span class="mt-1 block w-full">{{$registration->name}}</span>
                            </div>
                            <div class="sm:w-1/2">
                                <x-label class="text-slate-400" value="{{ __('Customer') }}" />
                                <span class="mt-1 block w-full">{{$registration->customer->fullName()}}</span>
                            </div>
                        </div>
                        <div class="col-span-6 flex flex-col sm:flex-row sm:gap-3 gap-2.5">
                            <div class="sm:w-1/2">
                                <x-label class="text-slate-400" value="{{ __('Congregation') }}" />
                                <span class="mt-1 block w-full">{{$registration->congregation ?? '-'}}</span>
                            </div>
                            <div class="sm:w-1/2">
                                <x-label class="text-slate-400" value="{{ __('Checked In?') }}" />
                                <span class="mt-1 block w-full">{{$registration->checkedin_at ? $registration->checkedin_at->format('F j, Y h:ia') : 'No'}}</span>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-action-section>

            <x-action-section class="mt-10">
                <x-slot name="title">
                    {{ __('Registration Emails') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Emails sent for this registration.') }}
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6" wire:model="bracelet.notifications">
                        @if(count($registration->notifications) <= 0)
                            <div class="flex items-center justify-between">
                                <div class="text-gray-600">No emails have been sent for this registration yet.</div>
                            </div>
                        @else
                            <div class="-m-1 5 overflow-x-auto">
                                <div class="p-1 min-w-full inline-block align-middle">
                                    <div class="border rounded-lg overflow-hidden">
                                        <table class="border-collapse divide-y divide-gray-200 min-w-full">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($registration->notifications as $notification)
                                                <tr class="odd:bg-white even:bg-gray-100">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                                        {{ $notification->notification_type }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                                        {{ $notification->created_at->format('F j, Y h:i A') }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <x-button wire:click.prevent="$emit('resendConfirmation')">
                            {{ __(':label Confirmation', ['label' => $registration->notifications->count() > 0 ? 'Re-Send' : 'Send']) }}
                        </x-button>
                    </div>
                </x-slot>
            </x-action-section>

            <!-- Delete Bracelet -->
            <x-action-section class="mt-10">
                <x-slot name="title">
                    {{ __('Delete registration') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Permanently delete this registration.') }}
                </x-slot>

                <x-slot name="content">
                    <div class="max-w-xl text-sm text-gray-600">
                        {{ __('Once a registration is deleted, all of its data will be permanently deleted. Before deleting this order, please download any data or information regarding this team that you wish to retain.') }}
                    </div>

                    <div class="mt-5">
                        <x-danger-button wire:click="$toggle('confirmingRegistrationDeletion')" wire:loading.attr="disabled">
                            {{ __('Delete registration') }}
                        </x-danger-button>
                    </div>

                    <!-- Delete Team Confirmation Modal -->
                    <x-confirmation-modal wire:model="confirmingRegistrationDeletion">
                        <x-slot name="title">
                            {{ __('Delete registration') }}
                        </x-slot>

                        <x-slot name="content">
                            {{ __('Are you sure you want to delete this registration? Once a registration is deleted, all of its resources and data will be permanently deleted.') }}
                        </x-slot>

                        <x-slot name="footer">
                            <x-secondary-button wire:click="$toggle('confirmingRegistrationDeletion')" wire:loading.attr="disabled">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-danger-button class="ml-3" wire:click="deleteRegistration" wire:loading.attr="disabled">
                                {{ __('Delete Registration') }}
                            </x-danger-button>
                        </x-slot>
                    </x-confirmation-modal>
                </x-slot>
            </x-action-section>
        </div>
    </div>
</div>
