<?php

namespace App\Http\Livewire\Registrations;

use App\Models\EventRegistration;
use Livewire\Component;

class All extends Component
{
    public function render()
    {
        return view('livewire.registrations.all')
            ->layout('layouts.app', ['title' => 'All Registrations'])
            ->with('registrations', EventRegistration::orderByDesc('created_at')->paginate(20));
    }
}
