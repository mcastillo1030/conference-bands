<?php

namespace App\Providers;

use App\Events\JetstreamUserCreated;
use App\Events\OrderCancelled;
use App\Events\SquareLinkGenerated;
use App\Listeners\AttachTeamToUser;
use App\Listeners\SendNewUserPasswordEmail;
use App\Listeners\SendOrderCancelledAdmin;
use App\Listeners\SendOrderCancelledEmail;
use App\Listeners\SendSquareNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Jetstream\Events\TeamMemberAdded;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreated::class => [
            SendCustomerConfirmation::class,
            SendAdminNotification::class,
        ],
        ConfirmationResend::class => [
            ResendConfirmation::class,
        ],
        SquareLinkGenerated::class => [
            SendSquareNotification::class,
        ],
        OrderCancelled::class => [
            SendOrderCancelledEmail::class,
            SendOrderCancelledAdmin::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(
            JetstreamUserCreated::class,
            [SendNewUserPasswordEmail::class, 'handle']
        );

        Event::listen(
            TeamMemberAdded::class,
            [AttachTeamToUser::class, 'handle']
        );
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
