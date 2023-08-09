<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Bracelet;
use App\Models\Order;
use App\Models\Team;
use App\Models\User;
use App\Policies\BraceletPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Bracelet::class => BraceletPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('teams:create', function(User $user, Team $team) {
            return $user->hasTeamPermission($team, 'teams:create');;
        });
        Gate::define('teams:read', function(User $user, Team $team) {
            return $user->hasTeamPermission($team, 'teams:read');
        });
        Gate::define('teams:update', function(User $user, Team $team) {
            return $user->hasTeamPermission($team, 'teams:update');
        });
        Gate::define('teams:delete', function(User $user, Team $team) {
            return $user->hasTeamPermission($team, 'teams:delete');
        });
        Gate::define('orders:create', function(User $user, Order $order) {
            return $user->hasTeamPermission($user->currentTeam, 'orders:create');
        });
        Gate::define('orders:read', function(User $user, Order $order) {
            return $user->hasTeamPermission($user->currentTeam, 'orders:read');
        });
        Gate::define('orders:update', function(User $user, Order $order) {
            return $user->hasTeamPermission($user->currentTeam, 'orders:update');
        });
        Gate::define('orders:delete', function(User $user, Order $order) {
            return $user->hasTeamPermission($user->currentTeam, 'orders:delete');
        });
        Gate::define('bracelets:create', function(User $user, Bracelet $bracelet) {
            return $user->hasTeamPermission($user->currentTeam, 'bracelets:create');
        });
        Gate::define('bracelets:read', function(User $user, Bracelet $bracelet) {
            return $user->hasTeamPermission($user->currentTeam, 'bracelets:read');
        });
        Gate::define('bracelets:view', function(User $user, Bracelet $bracelet) {
            return $user->hasTeamPermission($user->currentTeam, 'bracelets:view');
        });
        Gate::define('bracelets:update', function(User $user, Bracelet $bracelet) {
            return $user->hasTeamPermission($user->currentTeam, 'bracelets:update');
        });
        Gate::define('bracelets:delete', function(User $user, Bracelet $bracelet) {
            return $user->hasTeamPermission($user->currentTeam, 'bracelets:delete');
        });
    }
}
