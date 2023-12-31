<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6 lg:p-8">
                <div class="flex">
                    {{-- <a href="" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Bracelet(s)</a> --}}
                    <x-button-link href="{{ route('orders.dashboard') }}">Add Order(s)</x-button-link>
                </div>
            </div>
        </div>

        <div class="mt-12 sm:mt-8 max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @livewire('order.search')
            </div>
        </div>
    </div>
</x-app-layout>
