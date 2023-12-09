<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEditCarRequest;
use App\Models\Branch;
use App\Models\Car;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Traits\CarTrait;
class CarController extends Controller
{
    use CarTrait;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->authorizeResource(Car::class,'car');
    }
    public function index()
    {
        return Inertia::render('Cars/Index',[
            'cars' => Car::select(['id','plates','year','model_id','branch_id','deleted_at'])
            ->with(['model.brand:id,name','branch:id,name'])->latest('created_at')
            ->filter(request(['search','trashed']))->paginate(10)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only(['search','trashed']),
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
            'car' => $car->load(['model.brand:id,name','branch.district.town.state']), 
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
    public function destroy(Request $request, Car $car)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $car->delete();

        return redirect()->route('cars.show',$car)->with([
            'level' => 'success',
            'message' => 'Auto Eliminado Satisfactoriamente!'
        ]);
    }

    public function restore(Car $car) 
    {
        $car->restore();
        return redirect()->route('cars.show',$car)->with([
            'level' => 'success',
            'message' => 'Auto Restaurado Satisfactoriamente!'
        ]);
    }
    public function forceDelete(Request $request, Car $car)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        DB::transaction(function () use ($car) {
            if($car->image){
                Storage::disk('public')->delete($car->image);
            };
            foreach ($car->repairs as $repair) {
                $repair->details()->delete();
            }
            $car->repairs()->delete();
            $car->forceDelete();
        });

        return redirect()->route('cars.index')->with([
            'level' => 'success',
            'message' => 'Auto Eliminado Permanentemente!'
        ]);
    }
}
