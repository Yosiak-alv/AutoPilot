<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEditRepairRequest;
use App\Models\File;
use App\Models\Repair;
use App\Models\RepairStatus;
use App\Models\WorkShop;
use App\Traits\RepairTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Car;
class RepairController extends Controller
{
    use RepairTrait;
    public function __construct()
    {
        $this->authorizeResource(Repair::class,'repair');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // no
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //dd(Car::with(['model.brand'])->get(['id','model_id']));
        return Inertia::render('Repairs/CreateEditRepair',[
            'cars' => request()->user()->branch->main === 1 ? Car::select(['id','plates','model_id'])->with(['model.brand:id,name'])->get() : 
                Car::select(['id','plates','model_id'])->where('branch_id',request()->user()->branch->id)->with(['model.brand:id,name'])->get(),
            'repair_status' => RepairStatus::all(),
            'work_shops' => WorkShop::select('id','name','district_id')->with(['district','town','state'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEditRepairRequest $request)
    {
        $repair = Repair::create([
            'car_id' => $request->validated()['car_id'],
            'repair_status_id' => $request->validated()['repair_status_id'],
            'work_shop_id' => $request->validated()['work_shop_id'],
            'repair_date' => $request->validated()['repair_date'],  
            'total' => $request->sumPrices()['total'],
        ]);

        $repair->details()->createMany($request->validated()['details']);

        return redirect()->route('repairs.show',$repair->id)->with([
            'level' => 'success',
            'message' => 'Reparacion Creada Satisfactoriamente!'
        ]);
    }
    public function createFile(Repair $repair)
    {
        return Inertia::render('Repairs/Partials/CreateRepairFile',[
           'repairId' => $repair->id,
        ]);
    }
    public function storeFile(Repair $repair, Request $request)
    {
        $attr = $request->validate([
            'files' => 'array|required|max:5|min:1',
            'files.*' => 'file|mimes:pdf,png,jpg,jpeg|max:2048',
        ]);
        // Determine the allowed number of additional files
        //$allowedAdditionalFiles = 2 - $repair->files()->count();
        // Check if the request exceeds the allowed number of additional files
       /*  if (count($attr['files']) > $allowedAdditionalFiles) {
            return back()->with([
                'level' => 'error',
                'message' => 'Su peticion excede el numero de archivos, Puede agregar un '. $allowedAdditionalFiles .' mas.'
            ]);
        } */
        // Store new files
        $repair->files()->createMany(
            collect($attr['files'])->map(function ($file) {
                return [
                    'name' => $file->store('repairs_files','public'), 
                    'original_name' => $file->getClientOriginalName(), 
                    'ext'=> $file->getClientOriginalExtension()
                ];
            })->all()
        );
    
        return redirect()->route('repairs.show',$repair->id)->with([
            'level' => 'success',
            'message' => 'Archivo Cargado Satisfactoriamente!'
        ]); 
    }
    public function downloadFile(Repair $repair, File $file)
    {
        return Storage::disk('public')->download($file->name,$file->original_name);
    }
    public function destroyFile(Repair $repair, File $file)
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
    public function show(Repair $repair)
    {
        return Inertia::render('Repairs/Show',[
            'repair' => $repair->load(['details','car.model.brand','status','work_shop.district','work_shop.town','work_shop.state','files']),
            'repair_status' => RepairStatus::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Repair $repair)
    {
        return Inertia::render('Repairs/CreateEditRepair',[
            'repair' => $repair->load(['details']),
            'cars' => request()->user()->branch->main === 1 ? Car::select(['id','plates','model_id'])->with(['model.brand:id,name'])->get() : 
                Car::select(['id','plates','model_id'])->where('branch_id',request()->user()->branch->id)->with(['model.brand:id,name'])->get(),
            'repair_status' => RepairStatus::all(),
            'work_shops' => WorkShop::with(['district','town','state'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateEditRepairRequest $request, Repair $repair)
    {
        $repair->update([
            'car_id' => $request->validated()['car_id'],
            'work_shop_id' => $request->validated()['work_shop_id'],
            'repair_date' => $request->validated()['repair_date'],  
            'total' => $request->sumPrices()['total'],
        ]);
        $repair->details()->delete();
        $repair->details()->createMany($request->validated()['details']);

        return redirect()->route('repairs.show',$repair->id)->with([
            'level' => 'success',
            'message' => 'Reparacion Actualizada Satisfactoriamente!'
        ]);
    }
    public function updateStatus(Request $request, Repair $repair)
    {
        $attr = $request->validate([
            'repair_status_id' => 'required|exists:repair_statuses,id'
        ]);
        $repair->repair_status_id = $attr['repair_status_id'];
        $repair->save();
        return redirect()->route('repairs.show',$repair->id)->with([
            'level' => 'success',
            'message' => 'El Estado de Reparacion Actualizado Satisfactoriamente!'
        ]);
    }
    public function repairPDF(Repair $repair)
    {
        $repair->load(['details','car.model.brand','status','work_shop.district','work_shop.town','work_shop.state']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('repairs.repair-details', ['repair' => $repair]);
        return $pdf->download('reparacion-'.$repair->id.'.pdf');
    }
    /**
     * Remove the specified resource from storage. NO SE USA
     */
    //public function destroy(Request $request, Repair $repair)
    /* {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        DB::transaction(function () use ($repair) {
            $repair->details()->delete();
            $repair->delete();
        });

        return redirect()->route('cars.index')->with([
            'level' => 'success',
            'message' => 'Reparacion Eliminada Satisfactoriamente!'
        ]);
    } */
}
