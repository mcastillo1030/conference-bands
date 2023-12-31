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

            @php
                $is_online = Str::contains($order->order_type, 'online', true);
            @endphp

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
                                <span class="mt-1 block w-full">{{$order->created_at->format('m/d/Y g:i A')}}</span>
                            </div>
                            <div class="grow">
                                <x-label class="text-slate-400" value="{{ __('Last Update') }}" />
                                <span class="mt-1 block w-full">{{$order->updated_at->format('m/d/Y g:i A')}}</span>
                            </div>
                        </div>
                        <div class="col-span-6 {{$is_online ? 'flex flex-col sm:flex-row sm:gap-3 gap-2.5"' : ''}}">
                            <div class="grow">
                                <x-label class="text-slate-400" value="{{ __('Order Type') }}" />
                                <span class="mt-1 block w-full">{{Str::title(Str::replace('-', ' ', $order->order_type))}}</span>
                            </div>
                            @if ($is_online)
                                <div class="grow">
                                    <x-label class="text-slate-400" value="{{ __('Order Status') }}" />
                                    <span class="mt-1 block w-full">{{Str::title($order->order_status)}}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-slot>
            </x-action-section>

            @if ($is_online)
                <x-action-section class="mt-10">
                    <x-slot name="title">
                        {{ __('Square Details') }}
                    </x-slot>

                    <x-slot name="description">
                        {{ __('Square Checkout Details.') }}
                    </x-slot>

                    <x-slot name="content">
                        <div class="space-y-6 grid grid-cols-1 sm:grid-cols-6 sm:gap-3 gap-2.5">
                            @if ($order->id_key)
                                <div class="col-span-6 flex flex-col sm:flex-row sm:gap-3 gap-2.5">
                                    <div class="w-full">
                                        <x-label class="text-slate-400" value="{{ __('Payment Link') }}" />
                                        @if ($order->payment_link)
                                            <a href="{{$order->payment_link}}" class="mt-1 block w-full">{{$order->payment_link}}</a>
                                        @else
                                            <x-button class="mt-2" wire:click.prevent="$emit('generateSquareLink')">
                                                {{ __('Generate Payment Link') }}
                                            </x-button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="col-span-6 flex flex-col sm:flex-row sm:gap-3 gap-2.5">
                                <div class="w-full sm:w-1/2">
                                    <x-label class="text-slate-400" value="{{ __('Payment Status') }}" />
                                    <span class="mt-1 block w-full">{{$order->payment_status ?? 'Link not generated.'}}</span>
                                </div>
                                <div class="w-full sm:w-1/2">
                                    <x-label class="text-slate-400" value="{{ __('Square Order ID') }}" />
                                    <span class="mt-1 block w-full">{{$order->square_order_id ?? '-'}}</span>
                                </div>
                            </div>
                            <div class="col-span-6">
                                <x-label class="text-slate-400" value="{{ __('Order Notes') }}" />
                                @php
                                    $notes_output = ['-'];

                                    if ($order->order_notes) {
                                        $notes_output = explode('|', $order->order_notes);
                                    }
                                @endphp
                                @if (count($notes_output) == 1 && $notes_output[0] == '-')
                                    <span class="mt-1 block w-full break-words">{{$notes_output[0];}}</span>
                                @else
                                    <ul class="mt-1 list-disc list-inside">
                                        @foreach ($notes_output as $note)
                                            <li class="break-words">{{$note}}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        {{-- Send Needs Payment Email --}}
                        @php
                            $can_remind = array_search('one_time_action:needs_payment', $notes_output) !== false;
                            $needs_pli  = preg_match('/payment_link_id:/', $order->order_notes) === 0;
                        @endphp
                        <div class="mt-4 flex gap-x-4">
                        @if ($needs_pli)
                            <x-button wire:click.prevent="$emit('fetchPaymentLink')">
                                {{ __('Retrieve Payment Link ID') }}
                            </x-button>
                        @endif
                        @if ($order->payment_status == 'pending' && $order->payment_link && $can_remind)
                            <x-button wire:click.prevent="$emit('sendPaymentLink')">
                                {{ __('Send Payment Reminder') }}
                            </x-button>
                        @endif
                        </div>
                    </x-slot>
                </x-action-section>
            @endif

            @can('orders:delete', $order)
                @php
                    $is_cancellable = $is_online && $order->payment_status == 'pending';
                @endphp

                @if ($is_cancellable)
                    <!-- Cancel Order (cancel Squre Link) -->
                    <x-action-section class="mt-10">
                        <x-slot name="title">
                            {{ __('Cancel Order') }}
                        </x-slot>

                        <x-slot name="description">
                            {{ __('Cancel the payment link for this order.') }}
                        </x-slot>

                        <x-slot name="content">
                            <div class="max-w-xl text-sm text-gray-600">
                                {{ __('Cancelling an online order means cancelling the Square payment link and removing all bracelets associated with this order. This can only be done while the payment is pending and cannot be undone.') }}
                            </div>

                            <div class="mt-5">
                                <x-danger-button wire:click="$toggle('confirmingOrderCancellation')" wire:loading.attr="disabled">
                                    {{ __('Cancel order') }}
                                </x-danger-button>
                            </div>

                            <x-confirmation-modal wire:model="confirmingOrderCancellation">
                                <x-slot name="title">
                                    {{ __('Cancel Order') }}
                                </x-slot>

                                <x-slot name="content">
                                    {{ __('Are you sure you want to cancel this order? This cannot be undone.') }}
                                    <x-label class="flex gap-x-2 mt-3">
                                        <x-input type="checkbox" wire:model="shouldNotify" />
                                        <span>Send notification email.</span>
                                    </x-label>
                                </x-slot>

                                <x-slot name="footer">
                                    <x-secondary-button wire:click="$toggle('confirmingOrderCancellation')" wire:loading.attr="disabled">
                                        {{ __('Go Back') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ml-3" wire:click="cancelOrder" wire:loading.attr="disabled">
                                        {{ __('Cancel Order') }}
                                    </x-danger-button>
                                </x-slot>
                            </x-confirmation-modal>
                        </x-slot>
                    </x-action-section>
                @endif
            @endcan

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
                                    {{ __('Delete Order') }}
                                </x-danger-button>
                            </x-slot>
                        </x-confirmation-modal>
                    </x-slot>
                </x-action-section>
            @endcan
        </div>
    </div>
</div>
