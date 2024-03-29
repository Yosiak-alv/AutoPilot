<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Requests\CreateEditUserRequest;
use App\Models\Branch;
use App\Models\User;
use App\Notifications\TempPassword;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
class UserController extends Controller
{
    use UserTrait;
    public function __construct()
    {
        $this->authorizeResource(User::class,'user');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Users/Index',[
            'users' => User::select(['id', 'name', 'email','deleted_at','branch_id'])->with(['roles:id,name','branch:id,name'])->latest('created_at')
            ->filter(request(['search','trashed']))->paginate(8)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only(['search','trashed']),
        ]);
    }
    public function excelIndexExport()
    {
        $query = User::select(['id', 'name', 'email','deleted_at','branch_id'])->with(['roles:id,name','branch:id,name'])->latest('created_at');
        
        $search = request('search');
        $trashed = request('trashed');
        
        $export = new UsersExport($query, $search, $trashed);
        return Excel::download($export, 'usuarios.xlsx');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/CreateEditUser',[
            'roles' => Role::all('id','name')->where('name','!=','Super-Admin'),
            'branches' => Branch::all(['id','name'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEditUserRequest $request)
    {       
        $user = User::create($request->validatedUser());

        $roles = Role::whereIn('id', $request->validatedRolesIds())->get(); // Retrieve Role objects based on role IDs
        $user->syncRoles($roles);
        
        $user->notify(new TempPassword());

        return redirect()->route('users.show', $user->id)->with([
            'level' => 'success',
            'message' => 'Usuario Creado Satisfactoriamente!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('Users/Show',[
            'user' => $user->load('roles.permissions:id,name', 'branch.district.town.state')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/CreateEditUser',[
            'user' => $user,
            'user_roles' => $user->roles->pluck('id')->toArray(),
            'roles' => Role::all('id','name')->where('name','!=','Super-Admin'),
            'branches' => Branch::all(['id','name'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateEditUserRequest $request, User $user)
    {
        $user->update($request->validatedUser());
        $roles = Role::whereIn('id', $request->validatedRolesIds())->get(); // Retrieve Role objects based on role IDs
        $user->syncRoles($roles);

        return redirect()->route('users.show', $user->id)->with([
            'level' => 'success',
            'message' => 'Usuario Actualizado Satisfactoriamente!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,User $user)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user->delete();

        return redirect()->route('users.index')->with([
            'level' => 'success',
            'message' => 'Usuario Eliminado Satisfactoriamente!'
        ]); 
    }
    public function restore(User $user) 
    {
        $user->restore();

        return redirect()->route('users.show',$user)->with([
            'level' => 'success',
            'message' => 'Usuario Restaurado Satisfactoriamente!'
        ]);
    }
}
