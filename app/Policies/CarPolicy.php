<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CarPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver autos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Car $car): bool
    {
        return $user->hasPermissionTo('ver auto');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear auto');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function storeImage(User $user, Car $car): bool
    {
        return ($car->trashed() ?  false : $user->hasPermissionTo('agregar imagen auto'));
    }

    public function update(User $user, Car $car): bool
    {
       return ($car->trashed() ?  false : $user->hasPermissionTo('editar auto'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Car $car): bool
    {
        return ($car->trashed() ?  false :  $user->hasPermissionTo('eliminar auto'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Car $car): bool
    {
        return ($car->trashed() ?  $user->hasPermissionTo('restaurar auto'): false);
    }
}
