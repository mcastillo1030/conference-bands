<?php

namespace App\Http\Livewire\Bracelet;

use App\Models\Bracelet;
use Livewire\Component;

class AddBraceletForm extends Component
{
    public $start_number;
    public $end_number;
    public $group;

    protected $rules = [
        'start_number' => 'required|numeric|digits_between:1,4',
        'end_number' => 'nullable|numeric|digits_between:1,4|gte:start_number',
        'group' => 'nullable|string|max:255',
    ];

    protected $listeners = [
        'braceletsAdded' => 'clearForm',
    ];

    public function clearForm()
    {
        $this->reset();
    }

    public function addBracelets()
    {
        $this->validate();
        $max = $this->end_number ?? $this->start_number;
        $bracelets = [];
        for ($i = $this->start_number; $i <= $max; $i++) {
            $id = sprintf('%04d', $i);
            // if bracelet number already exists, skip it
            if (Bracelet::where('number', $id)->exists()) {
                continue;
            }

            $new_bracelet = Bracelet::create([
                'number' => $id,
                'group' => $this->group,
            ]);

            $bracelets[] = $new_bracelet->id;
        }

        $this->emit('braceletsAdded', $bracelets);
    }

    public function render()
    {
        return view('livewire.bracelet.add-bracelet-form');
    }
}
