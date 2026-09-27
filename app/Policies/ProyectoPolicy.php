<?php

namespace App\Policies;

use App\Models\Proyecto;
use App\Models\User;

class ProyectoPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function view(User $user, Proyecto $proyecto): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, Proyecto $proyecto): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function delete(User $user, Proyecto $proyecto): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
