<?php

namespace App\Policies;

use App\Models\Bracelet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BraceletPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasTeamPermission($user->currentTeam, 'bracelets:view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Bracelet $bracelet): bool
    {
        return $user->hasTeamPermission($user->currentTeam, 'bracelets:read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasTeamPermission($user->currentTeam, 'bracelets:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bracelet $bracelet): bool
    {
        return $user->hasTeamPermission($user->currentTeam, 'bracelets:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bracelet $bracelet): bool
    {
        return $user->hasTeamPermission($user->currentTeam, 'bracelets:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Bracelet $bracelet): bool
    {
        return $user->hasTeamPermission($user->currentTeam, 'bracelets:create');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Bracelet $bracelet): bool
    {
        return $user->hasTeamPermission($user->currentTeam, 'bracelets:delete');
    }
}
