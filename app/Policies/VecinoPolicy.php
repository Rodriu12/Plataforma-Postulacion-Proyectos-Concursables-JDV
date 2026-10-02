<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vecino;

class VecinoPolicy
{

    protected function puedeGestionar(User $user, Vecino $vecino): bool
    {
        if (! in_array($user->role, User::ROLES_DIRECTIVA)) {
            return false;
        }

        return $user->esAdminCentral() || $vecino->user?->organizacion_id === $user->organizacion_id;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Vecino $vecino): bool
    {
        return $this->puedeGestionar($user, $vecino) || $vecino->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, Vecino $vecino): bool
    {
        return $this->puedeGestionar($user, $vecino) || $vecino->user_id === $user->id;
    }

    public function delete(User $user, Vecino $vecino): bool
    {
        return $this->puedeGestionar($user, $vecino);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
