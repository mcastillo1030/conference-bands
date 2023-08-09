<div class="mt-10 sm:mt-0">
    <x-action-section>
        <x-slot name="title">
            {{ __('Latest Orders') }}
        </x-slot>

        <x-slot name="description">
            {{ __('These are some of the most recent orders in the system.') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6" wire:model="orders">
                @if(count($orders) <= 0)
                    <div class="flex items-center justify-between">
                        <div class="text-gray-600">No orders have been added to the system yet.</div>
                    </div>
                @else
                    <div class="border rounded-lg overflow-hidden">
                        <table class="border-collapse divide-y divide-gray-200 min-w-full">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Number</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                <tr class="odd:bg-white even:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ $order->created_at->format('m/d/Y H:m a') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ $order->number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        {{ $order->customer->fullName() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        <a href="{{ route('orders.show', $order)}}" class="ml-2 font-semibold text-indigo-700">
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-4 text-sm">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center font-semibold text-indigo-700">
                            Show All Orders

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ml-1 w-5 h-5 fill-indigo-500">
                                <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </p>
                @endif
            </div>
        </x-slot>
    </x-action-section>
</div>
