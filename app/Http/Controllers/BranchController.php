<?php

namespace App\Http\Controllers;

use App\Exports\BranchesExport;
use App\Http\Requests\CreateEditBranchRequest;
use App\Models\Branch;
use App\Models\State;
use App\Traits\BranchTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
class BranchController extends Controller
{
    use BranchTrait;
    public function __construct()
    {
        $this->authorizeResource(Branch::class, 'branch');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Branches/Index',[
            'branches' => Branch::select(['id','name','main','telephone','district_id','deleted_at'])
            ->with(['state','town','district'])->latest('created_at')
            ->filter(request(['search','trashed']))->paginate(10)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only(['search','trashed']),
        ]);
    }
    public function excelIndexExport()
    {
        $query = Branch::select(['id','name','main','telephone','district_id','deleted_at'])
        ->with(['state','town','district'])->latest('created_at');
        
        $search = request('search');
        $trashed = request('trashed');
        
        $export = new BranchesExport($query, $search, $trashed);
        return Excel::download($export, 'centros.xlsx');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Branches/CreateEditBranch',[
            'states' => State::with('towns.districts')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEditBranchRequest $request)
    {
        $branch = Branch::create($request->validated());

        return redirect()->route('branches.show',$branch)->with([
            'level' => 'success',
            'message' => 'Centro Creado Satisfactoriamente!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        return Inertia::render('Branches/Show',[
            'branch' => $branch->load(['state','town','district']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return Inertia::render('Branches/CreateEditBranch',[
            'branch' => $branch->load(['state','town']),
            'states' => State::with('towns.districts')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateEditBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

        return redirect()->route('branches.show',$branch->id)->with([
            'level' => 'success',
            'message' => 'Centro Actualizado Satisfactoriamente!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch, Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
        
        $branch->delete();

        if ($branch->id == request()->user()->branch_id) {
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect('/')->with([
                'level' => 'success',
                'message' => 'Centro en Papelera Satisfactoriamente!'
            ]);
        }

        return redirect()->route('branches.index')->with([
            'level' => 'success',
            'message' => 'Centro en Papelera Satisfactoriamente!'
        ]);
    }
    public function restore(Branch $branch)
    {
        $branch->restore();

        return redirect()->route('branches.show',$branch->id)->with([
            'level' => 'success',
            'message' => 'Centro Restaurado Satisfactoriamente!'
        ]);
    }
}
