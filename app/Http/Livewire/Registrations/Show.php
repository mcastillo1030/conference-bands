<?php

namespace App\Http\Livewire\Registrations;

use App\Models\EventRegistration;
use App\Providers\EventRegistrationResend;
use Livewire\Component;

class Show extends Component
{
    /**
     * The route parameter's value.
     *
     * @var EventRegistration
     */
    public EventRegistration $registration;

    /**
     * Flag to confirm if the user wants to delete the order.
     *
     * @var bool
     */
    public $confirmingRegistrationDeletion = false;

    /**
     * Event listeners
     */
    protected $listeners = [
        'resendConfirmation' => 'emitConfirmation',
    ];

    /**
     * Emit confirmation email evetn
     */
    public function emitConfirmation()
    {
        EventRegistrationResend::dispatch($this->registration);
        $this->registration->refresh();
    }

    public function deleteRegistration()
    {

        $this->registration->delete();
        $this->confirmingRegistrationDeletion = false;
        $this->emit('orderDeleted');
        return redirect()->route('registrations.dashboard');
    }

    public function render()
    {
        return view('livewire.registrations.show');
    }
}
