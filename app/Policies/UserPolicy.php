<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function viewAny(User $user): bool
    {
        return $user->type === 'admin';
    }

    public function view(User $user, User $model): bool
    {
        return $user->type === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->type === 'admin';
    }

    public function update(User $user, User $model): bool
    {
        return $user->type === 'admin';
    }

    public function delete(User $user, User $model): bool
    {
        return $user->type === 'admin';
    }
}
