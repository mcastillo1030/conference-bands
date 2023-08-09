<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.order.search', [
            'orders' => Order::where('number', 'like', "%{$this->search}%")
                ->orWhereRelation('customer', 'first_name', 'like', "%{$this->search}%")
                ->orWhereRelation('customer', 'last_name', 'like', "%{$this->search}%")
                ->orWhereRelation('customer', 'phone_number', 'like', "%{$this->search}%")
                ->orWhereRelation('customer', 'email', 'like', "%{$this->search}%")
                ->orderByDesc('created_at')
                ->paginate(20),
        ]);
    }
}
