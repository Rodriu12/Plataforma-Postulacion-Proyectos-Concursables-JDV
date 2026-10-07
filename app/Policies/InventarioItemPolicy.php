<?php

namespace App\Policies;

use App\Models\InventarioItem;
use App\Models\User;

class InventarioItemPolicy
{
    protected function puedeGestionar(User $user, InventarioItem $item): bool
    {
        if (! in_array($user->role, User::ROLES_DIRECTIVA)) {
            return false;
        }

        return $user->esAdminCentral() || $item->organizacion_id === $user->organizacion_id;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function view(User $user, InventarioItem $item): bool
    {
        return $this->puedeGestionar($user, $item);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, InventarioItem $item): bool
    {
        return $this->puedeGestionar($user, $item);
    }

    public function delete(User $user, InventarioItem $item): bool
    {
        return $this->puedeGestionar($user, $item);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
