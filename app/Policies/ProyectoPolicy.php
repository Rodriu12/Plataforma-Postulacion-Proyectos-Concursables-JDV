<?php

namespace App\Policies;

use App\Models\Proyecto;
use App\Models\User;

class ProyectoPolicy
{
    protected function puedeGestionar(User $user, Proyecto $proyecto): bool
    {
        if (! in_array($user->role, User::ROLES_DIRECTIVA)) {
            return false;
        }

        return $user->esAdminCentral() || $proyecto->organizacion_id === $user->organizacion_id;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function view(User $user, Proyecto $proyecto): bool
    {
        return $this->puedeGestionar($user, $proyecto);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, Proyecto $proyecto): bool
    {
        return $this->puedeGestionar($user, $proyecto);
    }

    public function delete(User $user, Proyecto $proyecto): bool
    {
        return $this->puedeGestionar($user, $proyecto);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
