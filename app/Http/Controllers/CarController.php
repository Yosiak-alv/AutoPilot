<?php

namespace App\Http\Controllers;

use App\Exports\CarRepairsExport;
use App\Exports\CarsExport;
use App\Http\Requests\CreateEditCarRequest;
use App\Models\Branch;
use App\Models\Car;
use App\Models\Brand;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Traits\CarTrait;
use Maatwebsite\Excel\Facades\Excel;
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
        $query = Car::select(['id','plates','year','model_id','branch_id','deleted_at'])
            ->with(['model.brand:id,name','branch:id,name'])->latest('created_at')
            ->filter(request(['search','trashed']));
        
        if (request()->user()->branch->main != 1) {
            $query->where('branch_id', request()->user()->branch->id);
        }
        $cars = $query->paginate(10)->withQueryString();

        return Inertia::render('Cars/Index',[
            'cars' => $cars,
            'filters' => \Illuminate\Support\Facades\Request::only(['search','trashed']),
        ]);
    }
    public function excelIndexExport()
    {
        $query = Car::select(['id','plates','year','model_id','branch_id','deleted_at'])
        ->with(['model.brand:id,name','branch:id,name'])->latest('created_at');
        
        if (request()->user()->branch->main != 1) {
            $query->where('branch_id', request()->user()->branch->id);
        }
        
        $search = request('search');
        $trashed = request('trashed');
        
        $export = new CarsExport($query, $search, $trashed);
        return Excel::download($export, 'autos.xlsx');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Cars/CreateEditCar',[
            'brands' => Brand::select('id','name')->with(['models'])->get(),
            'branches' => request()->user()->branch->main === 1 ? Branch::select(['id','name'])->get() 
            : Branch::select(['id','name'])->where('id',request()->user()->branch->id)->get() ,
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
            'images' => 'array|required|max:5|min:1',
            'images.*' => 'image|max:2048|mimes:jpg,jpeg,png',
        ]);

        // Delete current images cascadeOnDelete deattach images from car
        $car->images()->each(function ($currentImage) {
            Storage::disk('public')->delete($currentImage->name);
            $currentImage->delete(); 
        });

        // Store new images
        $car->images()->createMany(
            collect($attr['images'])->map(function ($image) {
                return ['name' => $image->store('cars_images','public')];
            })->all()
        );
    
        return redirect()->route('cars.show',$car)->with([
            'level' => 'success',
            'message' => 'Imagen Cargada Satisfactoriamente!'
        ]); 
    }
    public function createFile(Car $car)
    {
        return Inertia::render('Cars/Partials/CreateEditCarFile',[
            'carId' => $car->id, 
        ]);
    }
    public function storeUpdateFile(Request $request, Car $car)
    {
        $attr = $request->validate([
            'files' => 'array|required|max:5|min:1',
            'files.*' => 'file|mimes:pdf,png,jpg,jpeg|max:10240',
        ]);
        // Store new files
        $car->files()->createMany(
            collect($attr['files'])->map(function ($file) {
                return [
                    'name' => $file->store('cars_files','public'), 
                    'original_name' => $file->getClientOriginalName(), 
                    'ext'=> $file->getClientOriginalExtension()
                ];
            })->all()
        );
    
        return redirect()->route('cars.show',$car)->with([
            'level' => 'success',
            'message' => 'Archivo Cargado Satisfactoriamente!'
        ]); 
    }
    public function downloadFile(Car $car, File $file)
    {
        return Storage::disk('public')->download($file->name,$file->original_name);
    }
    public function destroyFile(Car $car, File $file)
    {
        Storage::disk('public')->delete($file->name);
        $file->delete();
        
        return back()->with([
            'level' => 'success',
            'message' => 'Archivo Eliminado Satisfactoriamente!'
        ]); 
    }
    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        return Inertia::render('Cars/Show',[
            'car' => $car->load(['model.brand:id,name','branch.district.town.state','images','files']), 
            'car_repairs' => $car->repairs()->when(\Illuminate\Support\Facades\Request::input('search') ?? false, function($query , $search) {
                $query->where(fn($query) =>
                    $query->where('total','like','%'.$search.'%')
                    ->orWhereHas('status',fn($query) =>
                        $query->where('name','like','%'.$search.'%')
                    )->orWhereHas('work_shop',fn($query) =>
                        $query->where('name','like','%'.$search.'%')
                    )
                );
            })->when(\Illuminate\Support\Facades\Request::input('start_date') ?? false , function($query, $start_date) {
                $query->where('repair_date','>=',$start_date);
            })->when(\Illuminate\Support\Facades\Request::input('end_date') ?? false , function($query, $end_date) {
                $query->where('repair_date','<=',$end_date);
            })->with(['status', 'work_shop:id,name'])->paginate(8)->withQueryString(),

            'filters' => \Illuminate\Support\Facades\Request::only(['search','start_date','end_date']),
        ]);
    }
    public function excelRepairsExport(Car $car)
    {
        $query = $car->repairs()->select(['id','car_id','work_shop_id','repair_status_id','total','created_at'])
            ->with(['status','work_shop:id,name']);
        
        $search = request('search');
        $start_date = request('start_date');
        $end_date = request('end_date');

        $export = new CarRepairsExport($query, $search, $start_date, $end_date);
        return Excel::download($export, $car->plates.'_reparaciones.xlsx');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        return Inertia::render('Cars/CreateEditCar',[
            'car' => $car->load('model.brand:id,name'),
            'brands' => Brand::select('id','name')->with(['models'])->get(),
            'branches' => request()->user()->branch->main === 1 ? Branch::select(['id','name'])->get() 
            : Branch::select(['id','name'])->where('id',request()->user()->branch->id)->get() ,
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

        return redirect()->route('cars.index')->with([
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
}
