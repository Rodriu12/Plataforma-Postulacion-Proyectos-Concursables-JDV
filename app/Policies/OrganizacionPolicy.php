<?php

namespace App\Policies;

use App\Models\Organizacion;
use App\Models\User;

class OrganizacionPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function view(User $user, Organizacion $organizacion): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, Organizacion $organizacion): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function delete(User $user, Organizacion $organizacion): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
