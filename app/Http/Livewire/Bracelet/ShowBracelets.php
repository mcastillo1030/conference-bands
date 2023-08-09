<?php

namespace App\Http\Livewire\Bracelet;

use App\Models\Bracelet;
use Livewire\Component;

class ShowBracelets extends Component
{
    public $bracelets;

    protected $listeners = [
        'braceletsAdded' => 'updateList',
    ];

    public function updateList()
    {
        $this->bracelets = Bracelet::orderByDesc('number')->latest()->take(10)->get();
    }

    public function mount()
    {
        $this->bracelets = Bracelet::orderByDesc('number')->latest()->take(10)->get();
    }

    public function render()
    {
        return view('livewire.bracelet.show-bracelets');
    }
}
