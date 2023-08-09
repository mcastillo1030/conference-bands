<?php

namespace App\Http\Livewire\Bracelet;

use App\Models\Bracelet;
use Livewire\Component;

class Show extends Component
{
    public Bracelet $bracelet;

    /**
     * To temporarily hold updated status before saving.
     *
     * @var string
     */
    public $status;

    /**
     * Flag to confirm if the user wants to delete the bracelet.
     *
     * @var bool
     */
    public $confirmingBraceletDeletion = false;

    protected $listeners = [
        'updateStatus',
        'saveStatus'
    ];

    protected $rules = [
        'bracelet.status' => 'required|string|in:system,reserved,registered',
        'bracelet.name' => 'nullable|string|max:255',
        'bracelet.group' => 'nullable|string|max:255',
    ];

    public function updateStatus(string $status)
    {
        $this->status = strtolower($status);
    }

    public function updateDetails()
    {
        $this->validate();
        $this->bracelet->save();
    }

    public function deleteBracelet()
    {
        $this->bracelet->delete();
        $this->confirmingBraceletDeletion = false;
        $this->emit('braceletDeleted');
        return redirect()->route('bracelets.index');
    }

    public function saveStatus()
    {
        $this->bracelet->update([
            'status' => $this->status
        ]);
        $this->emit('statusUpdated');
    }

    public function mount()
    {
        $this->status = $this->bracelet->status;
    }

    public function render()
    {
        return view('livewire.bracelet.show')
            ->layout('layouts.app', ['header' => 'Bracelet Details']);
    }
}
