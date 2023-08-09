<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Livewire\Component;

class RecentOrders extends Component
{
    /**
     * Recent orders.
     *
     * @var array
     */
    public $orders;

    protected $listeners = [
        'orderSaved' => 'updateList',
    ];

    /**
     * Update the list of recent orders.
     */
    public function updateList()
    {
        $this->orders = Order::orderByDesc('created_at')->latest()->take(10)->get();
    }

    /**
     * Mount the component.
     */
    public function mount()
    {
        $this->orders = Order::orderByDesc('created_at')->latest()->take(10)->get();
    }

    public function render()
    {
        return view('livewire.order.recent-orders');
    }
}
