<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\File;
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
    public function excelExport(User $user): bool
    {
        return $user->hasPermissionTo('exportar a excel');
    }
    /**
     * Determine whether the user can update the model.
     */
    public function storeImage(User $user, Car $car): bool
    {
        return ($car->trashed() ?  false : $user->hasPermissionTo('agregar imagenes auto'));
    }
    public function createFile(User $user, Car $car): bool
    {
        return ($car->trashed() ?  false : $user->hasPermissionTo('subir archivos auto'));
    }
    public function downloadFile(User $user, Car $car): bool
    {
        return ($car->trashed() ?  false :  $user->hasPermissionTo('descargar archivo auto'));
    }

    public function destroyFile(User $user, Car $car): bool
    {
        return ($car->trashed() ?  false :  $user->hasPermissionTo('eliminar archivo auto'));
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
