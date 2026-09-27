<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voluntario;

class VoluntarioPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Voluntario $voluntario): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA) || $voluntario->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, Voluntario $voluntario): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA) || $voluntario->user_id === $user->id;
    }

    public function delete(User $user, Voluntario $voluntario): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
