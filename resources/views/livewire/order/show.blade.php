<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="mt-10 sm:mt-0">
            <p class="text-sm">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center font-semibold text-indigo-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ml-1 w-5 h-5 fill-indigo-500 origin-center -rotate-180">
                        <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                    </svg>
                    All Orders
                </a>
            </p>

            <x-action-section class="mt-10">
                <x-slot name="title">
                    {{ __('Order Details') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Basic Order Details.') }}
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6">
                        <h4 class="relative font-semibold mb-3">Order Number: <span class="font-thin">{{$order->number}}</span></h4>

                        <div class="col-span-6 flex flex-col sm:flex-row sm:gap-3 gap-2.5">
                            <div class="grow">
                                <x-label class="text-slate-400" value="{{ __('Order Placed') }}" />
                                <span class="mt-1 block w-full">{{$order->created_at->format('m/d/Y H:i A')}}</span>
                            </div>
                            <div class="grow">
                                <x-label class="text-slate-400" value="{{ __('Last Update') }}" />
                                <span class="mt-1 block w-full">{{$order->updated_at->format('m/d/Y H:i A')}}</span>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-action-section>

            <x-action-section class="mt-10">
                <x-slot name="title">
                    {{ __('Bracelet Details') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Details for bracelet(s) in this order.') }}
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6">
                        <div class="col-span-6 flex flex-col sm:flex-row sm:gap-3 gap-2.5">
                            <div class="grow">
                                <x-label class="text-slate-400" value="{{ __('First Name') }}" />
                                <span class="mt-1 block w-full">{{$order->customer->first_name}}</span>
                            </div>
                            <div class="grow">
                                <x-label class="text-slate-400" value="{{ __('Last Name') }}" />
                                <span class="mt-1 block w-full">{{$order->customer->last_name}}</span>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <x-label class="text-slate-400" value="{{ __('Email') }}" />
                            <span class="mt-1 block w-full">
                                @empty($order->customer->email)
                                    -
                                @else
                                    <a href="mailto:{{$order->customer->email}}" class="text-indigo-600 hover:text-indigo-900">{{$order->customer->email}}</a>
                                @endempty
                            </span>
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <x-label class="text-slate-400" value="{{ __('Phone') }}" />
                            <span class="mt-1 block w-full">
                                @empty($order->customer->phone_number)
                                    -
                                @else
                                    <a href="tel:+1{{$order->customer->phone_number}}" class="text-indigo-600 hover:text-indigo-900">{{$order->customer->phone()}}</a>
                                @endempty
                            </span>
                        </div>
                    </div>
                </x-slot>
            </x-action-section>

            <!-- Bracelet Details -->
            <x-action-section class="mt-10" submit="updateDetails">
                <x-slot name="title">
                    {{ __('Bracelets') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Details for the bracelets in this order.') }}
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6" wire:model="order.bracelets">
                        @if(count($order->bracelets) <= 0)
                            <div class="flex items-center justify-between">
                                <div class="text-gray-600">This order does not have any linked bracelets.</div>
                            </div>
                        @else
                            <div class="-m-1 5 overflow-x-auto">
                                <div class="p-1 min-w-full inline-block align-middle">
                                    <div class="border rounded-lg overflow-hidden">
                                        <table class="border-collapse divide-y divide-gray-200 min-w-full">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Number</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Group</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->bracelets as $bracelet)
                                                <tr class="odd:bg-white even:bg-gray-100">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                                        {{ $bracelet->number }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                                        {{ $bracelet->name ?? '-' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                                        {{ $bracelet->group ?? '-' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 flex gap-x-4">
                                                        @can('orders:update', $order)
                                                            <a href="{{ route('bracelets.show', $bracelet)}}" class="font-semibold text-indigo-700">
                                                                {{ __('View/Edit') }}
                                                            </a>
                                                            <a wire:click.prevent="$emit('unlinkInterstitial', '{{$bracelet->id}}')"  class="font-semibold text-red-500 cursor-pointer">
                                                                {{ __('Unlink') }}
                                                            </a>
                                                        @else
                                                            N/A
                                                        @endcan
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @can('orders:update', $order)
                            <x-button wire:click.prevent="$emit('showLinkBraceletModal')">
                                {{ __('Link a Bracelet') }}
                            </x-button>
                        @endcan
                    </div>
                    @can('orders:update', $order)
                        @livewire('order.link-bracelet-modal', ['order' => $order])
                        <!-- Bracelet Unlink Confirmation -->
                        <x-confirmation-modal wire:model="confirmingBraceletUnlink">
                            <x-slot name="title">
                                {{ __('Unlink Bracelet') }}
                            </x-slot>

                            <x-slot name="content">
                                {{ __('Are you sure you want to remove this bracelet from this order? You\'ll have to manually re-attach this bracelet once unlinked.') }}
                            </x-slot>

                            <x-slot name="footer">
                                <x-secondary-button wire:click="$toggle('confirmingBraceletUnlink')" wire:loading.attr="disabled">
                                    {{ __('Cancel') }}
                                </x-secondary-button>

                                <x-danger-button class="ml-3" wire:click="confirmUnlinkBracelet" wire:loading.attr="disabled">
                                    {{ __('Unlink Bracelet') }}
                                </x-danger-button>
                            </x-slot>
                        </x-confirmation-modal>
                    @endcan
                </x-slot>
            </x-action-section>

            <x-action-section class="mt-10" submit="updateDetails">
                <x-slot name="title">
                    {{ __('Order Notificatioins') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Notifications (emails) sent for this order.') }}
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6" wire:model="order.notifications">
                        @if(count($order->notifications) <= 0)
                            <div class="flex items-center justify-between">
                                <div class="text-gray-600">No emails have been sent for this order yet.</div>
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
                                                @foreach ($order->notifications as $notification)
                                                <tr class="odd:bg-white even:bg-gray-100">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                                        {{ $notification->type }}
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

                        @can('orders:update', $order)
                            <x-button wire:click.prevent="$emit('resendConfirmation')">
                                {{ __(':label Confirmation', ['label' => $order->notifications->count() > 0 ? 'Re-Send' : 'Send']) }}
                            </x-button>
                        @endcan
                    </div>
                    @can('orders:update', $order)
                        @livewire('order.link-bracelet-modal', ['order' => $order])
                        <!-- Bracelet Unlink Confirmation -->
                        <x-confirmation-modal wire:model="confirmingBraceletUnlink">
                            <x-slot name="title">
                                {{ __('Unlink Bracelet') }}
                            </x-slot>

                            <x-slot name="content">
                                {{ __('Are you sure you want to remove this bracelet from this order? You\'ll have to manually re-attach this bracelet once unlinked.') }}
                            </x-slot>

                            <x-slot name="footer">
                                <x-secondary-button wire:click="$toggle('confirmingBraceletUnlink')" wire:loading.attr="disabled">
                                    {{ __('Cancel') }}
                                </x-secondary-button>

                                <x-danger-button class="ml-3" wire:click="confirmUnlinkBracelet" wire:loading.attr="disabled">
                                    {{ __('Unlink Bracelet') }}
                                </x-danger-button>
                            </x-slot>
                        </x-confirmation-modal>
                    @endcan
                </x-slot>
            </x-action-section>

            @can('orders:delete', $order)
                <!-- Delete Bracelet -->
                <x-action-section class="mt-10">
                    <x-slot name="title">
                        {{ __('Delete Order') }}
                    </x-slot>

                    <x-slot name="description">
                        {{ __('Permanently delete this order.') }}
                    </x-slot>

                    <x-slot name="content">
                        <div class="max-w-xl text-sm text-gray-600">
                            {{ __('Once an order is deleted, all of its data will be permanently deleted. Before deleting this order, please download any data or information regarding this team that you wish to retain.') }}
                        </div>

                        <div class="mt-5">
                            <x-danger-button wire:click="$toggle('confirmingOrderDeletion')" wire:loading.attr="disabled">
                                {{ __('Delete order') }}
                            </x-danger-button>
                        </div>

                        <!-- Delete Team Confirmation Modal -->
                        <x-confirmation-modal wire:model="confirmingOrderDeletion">
                            <x-slot name="title">
                                {{ __('Delete Order') }}
                            </x-slot>

                            <x-slot name="content">
                                {{ __('Are you sure you want to delete this order? Once aN order is deleted, all of its resources and data will be permanently deleted.') }}
                            </x-slot>

                            <x-slot name="footer">
                                <x-secondary-button wire:click="$toggle('confirmingOrderDeletion')" wire:loading.attr="disabled">
                                    {{ __('Cancel') }}
                                </x-secondary-button>

                                <x-danger-button class="ml-3" wire:click="deleteOrder" wire:loading.attr="disabled">
                                    {{ __('Delete Oorder') }}
                                </x-danger-button>
                            </x-slot>
                        </x-confirmation-modal>
                    </x-slot>
                </x-action-section>
            @endcan
        </div>
    </div>
</div>
