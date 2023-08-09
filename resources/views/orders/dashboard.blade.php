<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @can('viewAny', (new App\Models\Order))
                @livewire('order.recent-orders')

                <x-section-border />
            @endcan

            @can('create', (new App\Models\Order))
                @livewire('order.admin-add-order')

                <x-section-border />
            @endcan
        </div>
    </div>
</x-app-layout>
