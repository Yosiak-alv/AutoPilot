<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Brand::class);
        return Inertia::render('Brands/Index',[
            'brands' => Brand::select(['*'])->latest('created_at')
            ->filter(request(['search']))->paginate(10)->withQueryString(),
            'filters' => \Illuminate\Support\Facades\Request::only(['search']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Brand::class);
        $validated = $request->validate([
            'name' => 'required|unique:brands,name',
        ]);
        
        $brand = Brand::create($validated);
        return redirect()->route('brands.show',$brand->id)->with([
            'level' => 'success',
            'message' => 'Marca Creado Satisfactoriamente!'
        ]);
    }

    public function storeModel(Request $request, Brand $brand)
    {
        $this->authorize('create', Model::class);
        $validated = $request->validate([
            'name' => 'required|unique:models,name',
        ]);

        $brand->models()->create($validated);

        return redirect()->route('brands.show',$brand->id)->with([
            'level' => 'success',
            'message' => 'Modelo Creado Satisfactoriamente!'
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        $this->authorize('view', $brand);
        return Inertia::render('Brands/Show',[
            'brand' => $brand->load('models'),
        ]);
    }
    public function showModel(Model $model)
    {
        $this->authorize('view', $model);
        return Inertia::render('Brands/ModelShow',[
            'model' => $model,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $this->authorize('update', $brand);
        $validated = $request->validate([
            'name' => ['required','unique:brands,name,'.$brand->id],
        ]);

        $brand->update($validated);

        return redirect()->route('brands.show',$brand->id)->with([
            'level' => 'success',
            'message' => 'Marca Actualizada Satisfactoriamente!'
        ]);
    }
    public function updateModel(Request $request, Model $model)
    {
        $this->authorize('update', $model);
        $validated = $request->validate([
            'name' => ['required','unique:models,name,'.$model->id],
        ]);

        $model->update($validated);

        return redirect()->route('brands.showModel',$model->id)->with([
            'level' => 'success',
            'message' => 'Modelo Actualizado Satisfactoriamente!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request ,Brand $brand)
    {
        $this->authorize('delete', $brand);
        
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
        
        if($brand->models()->count() > 0){
            return redirect()->route('brands.show',$brand->id)->with([
                'level' => 'error',
                'message' => 'No se puede eliminar la marca porque tiene modelos asociados!'
            ]);
        }

        DB::transaction(function () use ($brand) {
            $brand->models()->delete();
            $brand->delete();
        });

        return redirect()->route('brands.index')->with([
            'level' => 'success',
            'message' => 'Marca Eliminada Satisfactoriamente!'
        ]);
    }
    public function destroyModel(Request $request, Model $model)
    {
        $this->authorize('delete', $model);
        
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        if($model->cars()->count() > 0){
            return redirect()->route('brands.showModel',$model->id)->with([
                'level' => 'error',
                'message' => 'No se puede eliminar el modelo porque tiene autos asociados!'
            ]);
        }

        $model->delete();

        return redirect()->route('brands.show',$model->brand_id)->with([
            'level' => 'success',
            'message' => 'Modelo Eliminado Satisfactoriamente!'
        ]);
    }
}
