<?php

namespace App\Policies;

use App\Models\InventarioMovimiento;
use App\Models\User;

class InventarioMovimientoPolicy
{
    protected function puedeGestionar(User $user, InventarioMovimiento $movimiento): bool
    {
        if (! in_array($user->role, User::ROLES_DIRECTIVA)) {
            return false;
        }

        return $user->esAdminCentral() || $movimiento->item?->organizacion_id === $user->organizacion_id;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function view(User $user, InventarioMovimiento $movimiento): bool
    {
        return $this->puedeGestionar($user, $movimiento);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, InventarioMovimiento $movimiento): bool
    {
        return $this->puedeGestionar($user, $movimiento);
    }

    public function delete(User $user, InventarioMovimiento $movimiento): bool
    {
        return $this->puedeGestionar($user, $movimiento);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
