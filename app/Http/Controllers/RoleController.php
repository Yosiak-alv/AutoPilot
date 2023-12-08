<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEditRoleRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class,'role');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Roles/Index',[
            'roles' => Role::select('id','name','created_at','updated_at')->latest('created_at')->when(\Illuminate\Support\Facades\Request::input('search') ?? false, function($query , $search){
                $query->where('name','LIKE',"%{$search}%")
                    ->orWhere('id','LIKE',"%{$search}%");
            })->paginate(10)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Roles/CreateEditRole',[
            'permissions' => Permission::select('id','name')->orderBy('id')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEditRoleRequest $request)
    {
        $permissions = Permission::whereIn('id', $request->validatedPermissionsIds())->get(); 
        $role = Role::create($request->validatedRole())->syncPermissions($permissions);

        return redirect()->route('roles.show',$role->id)->with([
            'level' => 'success',
            'message' => 'Rol Creado Satisfactoriamente!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return Inertia::render('Roles/Show',[
            'role' => $role->load(['permissions' => function ($query) {
                $query->select('id','name')->orderBy('id');
            }]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return Inertia::render('Roles/CreateEditRole',[
            'role' => $role,
            'role_permissions' => $role->permissions->pluck('id')->toArray(),
            'permissions' => Permission::select('id','name')->orderBy('id')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateEditRoleRequest $request, Role $role)
    {
        $permissions = Permission::whereIn('id', $request->validatedPermissionsIds())->get(); 
        $role->update($request->validatedRole());
        $role->syncPermissions($permissions);

        return redirect()->route('roles.show',$role->id)->with([
            'level' => 'success',
            'message' => 'Rol Actualizado Satisfactoriamente!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Role $role)
    {
        $request->validate([
            'password' => ['required','current_password'],
        ]);

        $role->delete();

        return redirect()->route('roles.index')->with([
            'level' => 'success',
            'message' => 'Rol Eliminado Satisfactoriamente!'
        ]);
    }
}
