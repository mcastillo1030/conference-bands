<?php

namespace App\Listeners;

use Laravel\Jetstream\Events\TeamMemberAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AttachTeamToUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TeamMemberAdded $event): void
    {
        $event->user->switchTeam($event->team);
    }
}
