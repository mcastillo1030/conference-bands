<?php

namespace App\Http\Livewire\Bracelet;

use App\Models\Bracelet;
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
        return view('livewire.bracelet.search', [
            'bracelets' => Bracelet::where('number', 'like', "%{$this->search}%")
                ->orWhere('name', 'like', "%{$this->search}%")
                ->orWhere('group', 'like', "%{$this->search}%")
                ->orderByDesc('number')
                ->paginate(20),
        ]);
    }
}
