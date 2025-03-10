<?php

namespace App\Policies;

use App\Models\Dishe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DishePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dishe $dishe): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->can('create dishes') ? Response::allow() : Response::deny('You cannot create dishes');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dishe $dishe): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dishe $dishe): Response
    {
        return $user->can('create dishes') ? Response::allow() : Response::deny('You cannot delete dishes');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dishe $dishe): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dishe $dishe): bool
    {
        return false;
    }
}
