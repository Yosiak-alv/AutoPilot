<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEditUserRequest;
use App\Mail\PasswordTempMail;
use App\Models\Branch;
use App\Models\User;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Str;
use Mail;
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
            'users' => User::select(['id', 'name', 'email','deleted_at'])->with(['roles:id,name'])->latest('created_at')
            ->filter(request(['search','trashed']))->paginate(8)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only(['search','trashed']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/CreateEditUser',[
            'roles' => Role::all('id','name'),
            'branches' => Branch::all(['id','name'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEditUserRequest $request)
    {
        $validated = $request->validatedUser();
        $randomPassword = Str::random(8);
        
        $validated['password'] = Hash::make($randomPassword);   
       
        $user = User::create($validated);

        $roles = Role::whereIn('id', $request->validatedRolesIds())->get(); // Retrieve Role objects based on role IDs
        $user->syncRoles($roles);
        
        Mail::to(strtolower($validated['email']))->send(new PasswordTempMail($validated['name'], $validated['email'], $randomPassword));

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
            'user' => $user->load('roles.permissions:id,name')
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
            'roles' => Role::all('id','name'),
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
    public function forceDelete(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user->forceDelete();

        return redirect()->route('users.index')->with([
            'level' => 'success',
            'message' => 'Usuario Eliminado Permanentemente!'
        ]);
    }
}
