<?php

namespace App\Policies;

use App\Models\Organizacion;
use App\Models\User;

class OrganizacionPolicy
{

    protected function puedeGestionar(User $user, Organizacion $organizacion): bool
    {
        if (! in_array($user->role, User::ROLES_DIRECTIVA)) {
            return false;
        }

        return $user->esAdminCentral() || $organizacion->id === $user->organizacion_id;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function view(User $user, Organizacion $organizacion): bool
    {
        return $this->puedeGestionar($user, $organizacion);
    }

    public function create(User $user): bool
    {
        return $user->esAdminCentral();
    }

    public function update(User $user, Organizacion $organizacion): bool
    {
        return $this->puedeGestionar($user, $organizacion);
    }

    public function delete(User $user, Organizacion $organizacion): bool
    {
        return $user->esAdminCentral();
    }

    public function deleteAny(User $user): bool
    {
        return $user->esAdminCentral();
    }
}
