<?php

namespace App\Policies;

use App\Models\User;

class ModelPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('ver modelo');
    }
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear modelo');
    }
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('editar modelo');
    }
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('eliminar modelo');
    }
}
