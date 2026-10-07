<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    protected function puedeGestionar(User $user, User $model): bool
    {
        if (! in_array($user->role, User::ROLES_GESTION_USUARIOS)) {
            return false;
        }

        return $user->esAdminCentral() || $model->organizacion_id === $user->organizacion_id;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_GESTION_USUARIOS);
    }

    public function view(User $user, User $model): bool
    {
        return $this->puedeGestionar($user, $model);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_GESTION_USUARIOS);
    }

    public function update(User $user, User $model): bool
    {
        return $this->puedeGestionar($user, $model);
    }

    public function delete(User $user, User $model): bool
    {
        return $this->puedeGestionar($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_GESTION_USUARIOS);
    }
}
