<?php

namespace App\Policies;

use App\Models\InventarioPrestamo;
use App\Models\User;

class InventarioPrestamoPolicy
{
    protected function puedeGestionar(User $user, InventarioPrestamo $prestamo): bool
    {
        if (! in_array($user->role, User::ROLES_DIRECTIVA)) {
            return false;
        }

        return $user->esAdminCentral() || $prestamo->item?->organizacion_id === $user->organizacion_id;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function view(User $user, InventarioPrestamo $prestamo): bool
    {
        return $this->puedeGestionar($user, $prestamo);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, InventarioPrestamo $prestamo): bool
    {
        return $this->puedeGestionar($user, $prestamo);
    }

    public function delete(User $user, InventarioPrestamo $prestamo): bool
    {
        return $this->puedeGestionar($user, $prestamo);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
