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
            <div>
                {{-- <x-label value="{{ __('Group') }}" /> --}}
                <span class="text-gray-400 text-sm">Group</span>
                {{-- group checkboxes --}}
                <div class="mt-1 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach ($groups as $group => $checked)
                        <label class="inline-flex items-center">
                            <input wire:model="groups.{{$group}}" type="checkbox" value="{{$group}}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <span class="ml-2 text-gray-700 text-sm">{{$group}}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 lg:p-8 bg-white border-b border-gray-200">
        {{-- @livewire('bracelet.show-listing', ['bracelets' => $bracelets]) --}}
        @if(count($bracelets) <= 0)
            <div class="flex items-center justify-between">
                <div class="text-gray-600">No bracelets have been added to the system yet. <a href="{{route('bracelets.dashboard')}}" class="font-semibold text-indigo-700">Add bracelets here</a> </div>
            </div>
        @else
            <div class="mb-8">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden">
                            <table class="border-collapse divide-y divide-gray-200 min-w-full">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Number</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Group</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bracelets as $bracelet)
                                    <tr class="odd:bg-white even:bg-gray-100">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $bracelet->number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap capitalize text-sm font-medium text-gray-800">
                                            {{ $bracelet->status }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            @if ($bracelet->status === 'system')
                                                {{ __('-') }}
                                            @else
                                                {{ $bracelet->name ?? $bracelet->order->customer->fullName() }}
                                            @endif
                                            {{-- {{ $bracelet->status === 'system' : '' name  }} --}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $bracelet->group ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            <a href="{{ route('bracelets.show', $bracelet)}}" class="ml-2 font-semibold text-indigo-700">
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
        {{$bracelets->links()}}
    </div>
</div>
