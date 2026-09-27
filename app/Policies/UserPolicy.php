<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_GESTION_USUARIOS);
    }

    public function view(User $user, User $model): bool
    {
        return in_array($user->role, User::ROLES_GESTION_USUARIOS);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_GESTION_USUARIOS);
    }

    public function update(User $user, User $model): bool
    {
        return in_array($user->role, User::ROLES_GESTION_USUARIOS);
    }

    public function delete(User $user, User $model): bool
    {
        return in_array($user->role, User::ROLES_GESTION_USUARIOS);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_GESTION_USUARIOS);
    }
}
