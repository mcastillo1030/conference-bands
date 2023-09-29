<?php

namespace App\Http\Livewire\Bracelet;

use App\Models\Bracelet;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $search = '';
    public $statuses = [];
    public $groups = [];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        foreach(config('constants.bracelet_statuses') as $status) {
            $this->statuses[$status] = false;
        }

        foreach(array_map(fn ($b) => $b['group'], Bracelet::select('group')->distinct()->get()->toArray()) as $group) {
            $this->groups[$group] = false;
        }
    }

    public function render()
    {
        $statuses = config('constants.bracelet_statuses');
        $filterable_statuses = array_keys(array_filter($this->statuses, fn ($s) => $s !== false));
        $groups = array_keys($this->groups);
        $filterable_groups = array_keys(array_filter($this->groups, fn ($g) => $g !== false));

        return view('livewire.bracelet.search', [
            'bracelets' => Bracelet::where(function (Builder $query) {
                    $query->where('number', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                })
                ->whereIn('status', count($filterable_statuses) ? $filterable_statuses : $statuses)
                ->whereIn('group', count($filterable_groups) ? $filterable_groups : $groups)
                ->orderBy('number')
                ->paginate(20),
        ]);
    }
}
