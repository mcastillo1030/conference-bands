<div>
    <div class="p-4 sm:p-6 lg:p-8 bg-white border-b border-gray-200">
        <x-label for="search" value="{{ __('Search') }}" />
        <x-input wire:model="search" id="search" type="text" class="mt-1 block w-full" />
        <x-input-error for="search" class="mt-2" />
        <div class="pt-4 flex flex-wrap items-start gap-x-9 sm:gap-x-20">
            <x-label class="w-full mb-1" value="{{ __('Filters') }}" />
            <div>
                <span class="text-gray-400 text-sm">Status</span>
                {{-- status checkboxes --}}
                <div class="mt-1 grid grid-cols-1 gap-2">
                    @foreach ($statuses as $status => $checked)
                        <label class="inline-flex items-center">
                            <input wire:model="statuses.{{$status}}" type="checkbox" value="{{$status}}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <span class="ml-2 text-gray-700 text-sm">{{Str::title($status)}}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 lg:p-8 bg-white border-b border-gray-200">
        @if(count($orders) <= 0)
            <div class="flex items-center justify-between">
                <div class="text-gray-600">No orders have been added to the system yet.</div>
            </div>
        @else
            <div class="mb-8">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden">
                            <table class="border-collapse divide-y divide-gray-200 min-w-full">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Number</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left">
                                            <button type="button" wire:click="$toggle('sortByBracelets')" class="text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-indigo-700 flex gap-x-1 items-center">
                                                Bracelets <span class="{{$sortByBracelets ? 'block' : 'hidden'}} w-2 h-2 border-l-[0.25rem] border-solid border-l-transparent border-r-[0.25rem] border-r-transparent border-t-[.5rem] border-t-[currentColor]"></span>
                                            </button>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    <tr class="odd:bg-white even:bg-gray-100">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $order->created_at->format('m/d/Y H:m a') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap capitalize text-sm font-medium text-gray-800">
                                            {{ $order->number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap capitalize text-sm font-medium text-gray-800">
                                            {{ Str::title($order->order_status) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $order->customer->fullName() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $order->bracelets->count() }}
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
                    </div>
                </div>
            </div>
        @endif
        {{ $orders->links()}}
    </div>
</div>
