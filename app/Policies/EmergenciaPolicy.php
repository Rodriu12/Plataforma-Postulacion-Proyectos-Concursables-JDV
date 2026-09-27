<?php

namespace App\Policies;

use App\Models\Emergencia;
use App\Models\User;

class EmergenciaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Emergencia $emergencia): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA)
            || $emergencia->vecino?->user_id === $user->id
            || $emergencia->voluntario?->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Emergencia $emergencia): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA)
            || $emergencia->vecino?->user_id === $user->id
            || $emergencia->voluntario?->user_id === $user->id;
    }

    public function delete(User $user, Emergencia $emergencia): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
