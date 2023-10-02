<?php

namespace App\Http\Livewire\Teams;

use App\Helpers\OptionsTableHelper;
use Livewire\Component;

class HandleAppState extends Component
{
    /**
     * The current state of the app
     * @var Boolean
     */
    public $appState;

    /**
     * The app message
     * @var String
     */
    public $appMessage;

    public function updatedAppState()
    {
        OptionsTableHelper::setOption('app_state', $this->appState);
    }

    public function mount()
    {
        $this->appState = (bool) OptionsTableHelper::getOption('app_state') ?? false;
        $this->appMessage = OptionsTableHelper::getOption('app_message') ?? '';
    }

    public function saveMessage() {
        if ($this->appMessage) {
            OptionsTableHelper::setOption('app_message', $this->appMessage);
        }

        $this->emit('messageSaved');
    }

    public function render()
    {
        return view('livewire.teams.handle-app-state');
    }
}
