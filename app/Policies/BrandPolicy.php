<?php

namespace App\Policies;

use App\Models\User;

class BrandPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver marcas');
    }
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('ver marca');
    }
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear marca');
    }
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('editar marca');
    }
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('eliminar marca');
    }
}
