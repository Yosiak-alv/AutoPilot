<?php

namespace App\Http\Controllers;

use App\Exports\WorkShopsExport;
use App\Http\Requests\CreateEditWorkShopRequest;
use App\Models\WorkShop;
use App\Models\State;
use App\Traits\WorkShopTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
class WorkShopController extends Controller
{
    use WorkShopTrait;
    public function __construct()
    {
        $this->authorizeResource(WorkShop::class, 'workshop');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('WorkShops/Index',[
            'work_shops' => WorkShop::select(['id','name','telephone','district_id','deleted_at'])
            ->with(['state','town','district'])->latest('created_at')
            ->filter(request(['search','trashed']))->paginate(10)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only(['search','trashed']),
        ]);
    }
    public function excelIndexExport()
    {
        $query = WorkShop::select(['id','name','telephone','district_id','deleted_at'])
        ->with(['state','town','district'])->latest('created_at');
        
        $search = request('search');
        $trashed = request('trashed');
        
        $export = new WorkShopsExport($query, $search, $trashed);
        return Excel::download($export, 'talleres.xlsx');
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
            'workshop_repairs' => $workshop->repairs()->when(\Illuminate\Support\Facades\Request::input('search') ?? false, function($query , $search) {
                $query->where(fn($query) =>
                    $query->where('total','like','%'.$search.'%')
                    ->orWhereHas('status',fn($query) =>
                        $query->where('name','like','%'.$search.'%')
                    )->orWhereHas('car',fn($query) =>
                        $query->where('plates','like','%'.$search.'%')
                        ->orWhere('year','like','%'.$search.'%')
                        ->orWhereHas('model',fn($query) =>
                            $query->where('name','like','%'.$search.'%')
                            ->orWhereHas('brand',fn($query) =>
                                $query->where('name','like','%'.$search.'%')
                            )
                        )
                    )
                );
            })->with(['status', 'car.model.brand:id,name'])->paginate(8)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only('search'),
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
    public function destroy(Request $request,WorkShop $workshop)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $workshop->delete();

        return redirect()->route('workshops.index')->with([
            'level' => 'success',
            'message' => 'Taller Eliminado Satisfactoriamente!'
        ]);
    }
    public function restore(WorkShop $workshop)
    {
        $workshop->restore();
        return redirect()->route('workshops.show',$workshop)->with([
            'level' => 'success',
            'message' => 'Taller Restaurado Satisfactoriamente!'
        ]);
    }
}
