<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEditWorkShopRequest;
use App\Models\WorkShop;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;
class WorkShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('WorkShops/Index',[
            'work_shops' => WorkShop::select(['id','name','telephone','district_id'])
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
        return Inertia::render('WorkShops/CreateEditWorkShop',[
            'states' => State::with('towns.districts')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEditWorkShopRequest $request)
    {
        $workshop = WorkShop::create($request->validated());

        return redirect()->route('workshops.show',$workshop)->with([
            'level' => 'success',
            'message' => 'Taller Creado Satisfactoriamente!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkShop $workshop)
    {
        return Inertia::render('WorkShops/Show',[
            'workshop' => $workshop->load(['state','town','district']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkShop $workshop)
    {
        return Inertia::render('WorkShops/CreateEditWorkShop',[
            'workshop' => $workshop->load(['state','town']),
            'states' => State::with('towns.districts')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateEditWorkShopRequest $request, WorkShop $workshop)
    {
        $workshop->update($request->validated());

        return redirect()->route('workshops.show',$workshop)->with([
            'level' => 'success',
            'message' => 'Taller Actualizado Satisfactoriamente!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkShop $workshop)
    {
        dd('hola');
    }
}
