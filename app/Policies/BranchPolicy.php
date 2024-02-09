<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BranchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver centros');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('ver centro');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear centro');
    }
    public function excelExport(User $user): bool
    {
        return $user->hasPermissionTo('exportar a excel');
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Branch $branch): bool
    {
        if($branch->id === 1){
            return false;
        }
        return $branch->trashed() ? false : $user->hasPermissionTo('editar centro');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Branch $branch): bool
    {
        if($branch->main == 1 || $branch->id == 1){
            return false;
        }
        return $branch->trashed() ? false : $user->hasPermissionTo('eliminar centro');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Branch $branch): bool
    {
        return $branch->trashed() ? $user->hasPermissionTo('restaurar centro') : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    /* public function forceDelete(User $user, Branch $branch): bool
    {
        //
    } */
}
