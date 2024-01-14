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
        Permission::create(['name' => 'ver auto']);
        Permission::create(['name' => 'agregar imagenes auto']);
        Permission::create(['name' => 'crear auto']);
        Permission::create(['name' => 'editar auto']);
        Permission::create(['name' => 'eliminar auto']);
        
        Permission::create(['name' => 'subir archivos auto']);
        Permission::create(['name' => 'eliminar archivo auto']);
        Permission::create(['name' => 'descargar archivo auto']);

        Permission::create(['name' => 'restaurar auto']);

        //Repairs PERMISSIONS
        Permission::create(['name' => 'ver reparacion']);
        Permission::create(['name' => 'crear reparacion']);
        Permission::create(['name' => 'editar reparacion']);
        Permission::create(['name' => 'editar status reparacion']);

        Permission::create(['name' => 'subir archivos reparacion']);
        Permission::create(['name' => 'eliminar archivo reparacion']);
        Permission::create(['name' => 'descargar archivo reparacion']);

        Permission::create(['name' => 'eliminar reparacion']);

        //Brand - Model PERMISSIONS
        Permission::create(['name' => 'ver marcas']);
        Permission::create(['name' => 'ver marca']);
        Permission::create(['name' => 'crear marca']);
        Permission::create(['name' => 'editar marca']);
        Permission::create(['name' => 'eliminar marca']);
        Permission::create(['name' => 'ver modelo']);
        Permission::create(['name' => 'crear modelo']);
        Permission::create(['name' => 'editar modelo']);
        Permission::create(['name' => 'eliminar modelo']);

        //Workshop PERMISSIONS
        Permission::create(['name' => 'ver talleres']);
        Permission::create(['name' => 'ver taller']);
        Permission::create(['name' => 'crear taller']);
        Permission::create(['name' => 'editar taller']);
        Permission::create(['name' => 'eliminar taller']);
        Permission::create(['name' => 'restaurar taller']);

        //Branch PERMISSIONS
        Permission::create(['name' => 'ver centros']);
        Permission::create(['name' => 'ver centro']);
        Permission::create(['name' => 'crear centro']);
        Permission::create(['name' => 'editar centro']);
        Permission::create(['name' => 'eliminar centro']);
        Permission::create(['name' => 'restaurar centro']);

        //User PERMISSIONS
        Permission::create(['name' => 'ver usuarios']);
        Permission::create(['name' => 'ver usuario']);
        Permission::create(['name' => 'crear usuario']);
        Permission::create(['name' => 'editar usuario']);
        Permission::create(['name' => 'eliminar usuario']);
        Permission::create(['name' => 'restaurar usuario']);

        //Role PERMISSIONS
        Permission::create(['name' => 'ver roles']);
        Permission::create(['name' => 'ver rol']);
        Permission::create(['name' => 'crear rol']);
        Permission::create(['name' => 'editar rol']);
        Permission::create(['name' => 'eliminar rol']);

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
