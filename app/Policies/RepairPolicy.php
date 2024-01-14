<?php

namespace App\Policies;

use App\Models\Repair;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RepairPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Repair $repair): bool
    {
        return $user->hasPermissionTo('ver reparacion');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return  $user->hasPermissionTo('crear reparacion');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Repair $repair): bool
    {
        return $repair->car == null || $repair->work_shop == null ? false : $user->hasPermissionTo('editar reparacion');
    }
    public function updateStatus(User $user, Repair $repair): bool
    {
        return $repair->car == null || $repair->work_shop == null ? false : $user->hasPermissionTo('editar status reparacion');
    }
    public function repairPDF(User $user, Repair $repair): bool
    {
        return $repair->car == null || $repair->work_shop == null ? false : true;
    }
    public function createFile(User $user, Repair $repair): bool
    {
        return  $repair->car == null || $repair->work_shop == null ? false : $user->hasPermissionTo('subir archivos reparacion');
    }
    public function downloadFile(User $user, Repair $repair): bool
    {
        return  $repair->car == null || $repair->work_shop == null ? false : $user->hasPermissionTo('descargar archivo reparacion');
    }

    public function destroyFile(User $user, Repair $repair): bool
    {
        return  $repair->car == null || $repair->work_shop == null ? false : $user->hasPermissionTo('eliminar archivo reparacion');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Repair $repair): bool
    {
        return $repair->car == null || $repair->work_shop == null ? false : $user->hasPermissionTo('eliminar reparacion');
    }
}
