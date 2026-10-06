<?php

namespace App\Policies;

use App\Models\Reunion;
use App\Models\User;

class ReunionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reunion $reunion): bool
    {
        return $user->esAdminCentral() || $reunion->organizacion_id === $user->organizacion_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, Reunion $reunion): bool
    {
        if (! in_array($user->role, User::ROLES_DIRECTIVA)) {
            return false;
        }

        return $user->esAdminCentral() || $reunion->organizacion_id === $user->organizacion_id;
    }

    public function delete(User $user, Reunion $reunion): bool
    {
        return $this->update($user, $reunion);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
