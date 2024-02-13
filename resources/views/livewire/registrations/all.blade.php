<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('All Bracelets') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="mt-12 sm:mt-8 max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-4 sm:p-6 lg:p-8 bg-white border-b border-gray-200">
                {{-- @livewire('bracelet.show-listing', ['bracelets' => $registrations]) --}}
                @if(count($registrations) <= 0)
                    <div class="flex items-center justify-between">
                        <div class="text-gray-600">No event registrations have been added to the system yet. </div>
                    </div>
                @else
                    <div class="mb-8">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1 min-w-full inline-block align-middle">
                                <div class="border rounded-lg overflow-hidden">
                                    <table class="border-collapse divide-y divide-gray-200 min-w-full">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration ID</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"># Guests</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($registrations as $registration)
                                            <tr class="odd:bg-white even:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                                    {{ $registration->registration_id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap capitalize text-sm font-medium text-gray-800">
                                                    {{ $registration->customer->fullName() }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap capitalize text-sm font-medium text-gray-800">
                                                    {{ $registration->name ?? __('-') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap capitalize text-sm font-medium text-gray-800">
                                                    {{ $registration->guests }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                                    <a href="{{ route('registrations.show', $registration)}}" class="ml-2 font-semibold text-indigo-700">
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
                {{$registrations->links()}}
            </div>
        </div>
    </div>
</div>
