<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $search = '';
    public $statuses = [];
    public $sortByBracelets = false;


    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        // $this->statuses = [];

        foreach(config('constants.order_statuses') as $status) {
            $this->statuses[$status] = false;
        }
    }

    public function render()
    {
        $statuses = config('constants.order_statuses');
        $filterable_statuses = array_keys(array_filter($this->statuses, fn ($s) => $s !== false));

        return view('livewire.order.search', [
            'orders' => Order::where(function (Builder $query) {
                $query->where('number', 'like', "%{$this->search}%")
                    ->orWhereRelation('customer', 'first_name', 'like', "%{$this->search}%")
                    ->orWhereRelation('customer', 'last_name', 'like', "%{$this->search}%")
                    ->orWhereRelation('customer', 'phone_number', 'like', "%{$this->search}%")
                    ->orWhereRelation('customer', 'email', 'like', "%{$this->search}%");
            })
                ->whereIn('order_status', count($filterable_statuses) ? $filterable_statuses : $statuses)
                ->when($this->sortByBracelets, function (Builder $query) {
                    $query->withCount('bracelets')->orderBy('bracelets_count', 'desc');
                }, function (Builder $query) {
                    $query->orderByDesc('created_at');
                })
                ->paginate(20),
        ]);
    }
}
