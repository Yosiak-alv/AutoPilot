<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        /* app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionsByRole = [
            'Super Admin' => ['restore posts', 'force delete posts'],
            '' => ['create a post', 'update a post', 'delete a post'],
            'viewer' => ['view all posts', 'view a post']
        ];
        
        $insertPermissions = fn ($role) => collect($permissionsByRole[$role])
            ->map(fn ($name) => DB::table('permissions')->insertGetId(['name' => $name, 'guard_name' => 'web']))
            ->toArray();
        
        $permissionIdsByRole = [
            'admin' => $insertPermissions('admin'),
            'editor' => $insertPermissions('editor'),
            'viewer' => $insertPermissions('viewer')
        ];
        
        foreach ($permissionIdsByRole as $role => $permissionIds) {
            $role = Role::whereName($role)->first();
        
            DB::table('role_has_permissions')
                ->insert(
                    collect($permissionIds)->map(fn ($id) => [
                        'role_id' => $role->id,
                        'permission_id' => $id
                    ])->toArray()
                );
        } */
        //CARS PERMISSIONS
        Permission::create(['name' => 'ver autos']);
        Permission::create(['name' => 'ver autos /all']);
        Permission::create(['name' => 'ver auto']);
        Permission::create(['name' => 'agregar imagen auto']);
        Permission::create(['name' => 'crear auto']);
        Permission::create(['name' => 'editar auto']);
        Permission::create(['name' => 'eliminar auto']);
        Permission::create(['name' => 'restaurar auto']);
        Permission::create(['name' => 'force-delete auto']);



        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'Recursos Humanos']);
        $role->givePermissionTo(['ver autos', 'ver auto', 'crear auto', 'editar auto', 'eliminar auto']);

        // or may be done by chaining
        $role = Role::create(['name' => 'horas-sociales'])
            ->givePermissionTo(['ver autos']);

        $role = Role::create(['name' => 'Super-Admin']);
        $role->givePermissionTo(Permission::all());
    }
}
