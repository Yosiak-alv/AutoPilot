<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEditRepairRequest;
use App\Models\Repair;
use App\Models\RepairStatus;
use App\Models\WorkShop;
use App\Traits\RepairTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'cars' => Car::select(['id','plates','model_id'])->with(['model.brand:id,name'])->get(),
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
            'sub_total' => $request->sumPrices()['sub_total'],
            'taxes' => $request->sumPrices()['taxes'],
            'total' => $request->sumPrices()['total_with_taxes'],
        ]);

        $repair->details()->createMany($request->validated()['details']);

        return redirect()->route('repairs.show',$repair->id)->with([
            'level' => 'success',
            'message' => 'Reparacion Creada Satisfactoriamente!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Repair $repair)
    {
        return Inertia::render('Repairs/Show',[
            'repair' => $repair->load(['details','car.model.brand','status','work_shop.district','work_shop.town','work_shop.state']),
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
            'cars' => Car::select(['id','plates','model_id'])->with(['model.brand:id,name'])->get(),
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
            'repair_status_id' => $request->validated()['repair_status_id'],
            'work_shop_id' => $request->validated()['work_shop_id'],
            'sub_total' => $request->sumPrices()['sub_total'],
            'taxes' => $request->sumPrices()['taxes'],
            'total' => $request->sumPrices()['total_with_taxes'],
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
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Repair $repair)
    {
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
    }
}
