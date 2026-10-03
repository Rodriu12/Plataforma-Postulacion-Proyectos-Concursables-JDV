<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voluntario;

class VoluntarioPolicy
{
    protected function puedeGestionar(User $user, Voluntario $voluntario): bool
    {
        if (! in_array($user->role, User::ROLES_DIRECTIVA)) {
            return false;
        }

        return $user->esAdminCentral() || $voluntario->organizacion_id === $user->organizacion_id;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Voluntario $voluntario): bool
    {
        return $this->puedeGestionar($user, $voluntario) || $voluntario->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, Voluntario $voluntario): bool
    {
        return $this->puedeGestionar($user, $voluntario) || $voluntario->user_id === $user->id;
    }

    public function delete(User $user, Voluntario $voluntario): bool
    {
        return $this->puedeGestionar($user, $voluntario);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
