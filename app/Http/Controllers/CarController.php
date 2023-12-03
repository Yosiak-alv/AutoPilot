<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEditCarRequest;
use App\Models\Branch;
use App\Models\Car;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Cars/Index',[
            'cars' => Car::with(['model.brand:id,name','branch:id,name'])->latest('created_at')
            ->filter(request(['search']))->paginate(10)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Cars/CreateEditCar',[
            'brands' => Brand::select('id','name')->with(['models'])->get(),
            'branches' => Branch::select(['id','name'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEditCarRequest $request)
    {
        $car = Car::create($request->validated());

        return redirect()->route('cars.show',$car)->with([
            'level' => 'success',
            'message' => 'Auto Creado Satisfactoriamente!'
        ]);
    }
    public function storeImage(Request $request, Car $car)
    {
        $attr = $request->validate([
            'image' => 'required|image|max:2048|mimes:jpg,jpeg,png',
        ]);

        if( $request->hasFile('image')){
            $attr['image'] = $request->file('image')->store('cars','public');
            Storage::disk('public')->delete($car->image);
        }

        $car->image = $attr['image'];

        $car->save();

        return redirect()->route('cars.show',$car)->with([
            'level' => 'success',
            'message' => 'Imagen Cargada Satisfactoriamente!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        return Inertia::render('Cars/Show',[
            'car' => $car->load(['model.brand:id,name','branch']), 
            'carImageUrl' => $car->getImageUrl(),
            'car_repairs' => $car->repairs()->when(\Illuminate\Support\Facades\Request::input('search') ?? false, function($query , $search) {
                $query->where(fn($query) =>
                    $query->where('total','like','%'.$search.'%')
                    ->orWhereHas('status',fn($query) =>
                        $query->where('name','like','%'.$search.'%')
                    )->orWhereHas('work_shop',fn($query) =>
                        $query->where('name','like','%'.$search.'%')
                    )
                );
            })->with(['status', 'work_shop:id,name'])->paginate(8)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only('search'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        return Inertia::render('Cars/CreateEditCar',[
            'car' => $car->load('model.brand:id,name'),
            'brands' => Brand::select('id','name')->with(['models'])->get(),
            'branches' => Branch::select(['id','name'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateEditCarRequest $request, Car $car)
    {
        $car->update($request->validated());

        return redirect()->route('cars.show',$car)->with([
            'level' => 'success',
            'message' => 'Auto Actualizado Satisfactoriamente!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        dd('hola');
    }
}
