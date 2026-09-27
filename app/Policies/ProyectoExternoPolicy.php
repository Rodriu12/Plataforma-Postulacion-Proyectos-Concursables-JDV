<?php

namespace App\Policies;

use App\Models\ProyectoExterno;
use App\Models\User;

class ProyectoExternoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ProyectoExterno $proyectoExterno): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function update(User $user, ProyectoExterno $proyectoExterno): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function delete(User $user, ProyectoExterno $proyectoExterno): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }

    public function deleteAny(User $user): bool
    {
        return in_array($user->role, User::ROLES_DIRECTIVA);
    }
}
