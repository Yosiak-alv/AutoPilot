<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEditBranchRequest;
use App\Models\Branch;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Branches/Index',[
            'branches' => Branch::select(['id','name','main','telephone','district_id'])
            ->with(['state','town','district'])->latest('created_at')
            ->filter(request(['search']))->paginate(10)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only(['search']),
        ]);
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

        return redirect()->route('branches.show',$branch)->with([
            'level' => 'success',
            'message' => 'Centro Actualizado Satisfactoriamente!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        dd('hola');
    }
}
