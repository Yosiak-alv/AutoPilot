<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkShopController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard',[
        'brands' => \App\Models\Brand::count(),
        'cars' => \App\Models\Car::count(),
        'branches' => \App\Models\Branch::count(),
        'workshops' => \App\Models\WorkShop::count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function() {
    Route::resource('cars',CarController::class);
    Route::post('/cars/{car}/image', [CarController::class, 'storeImage'])->name('cars.storeImage');
    Route::patch('/cars/{car}/restore', [CarController::class, 'restore'])->name('cars.restore');
    Route::delete('/cars/{car}/forceDelete', [CarController::class, 'forceDelete'])->name('cars.forceDelete');

    Route::resource('repairs',RepairController::class)->except('index');
    Route::patch('/repairs/{repair}/status', [RepairController::class, 'updateStatus'])->name('repairs.updateStatus');
    Route::get('/repairs/{repair}/pdf', [RepairController::class, 'repairPDF'])->name('repairs.repairPDF');
    
    Route::resource('branches',BranchController::class);
    Route::patch('/branches/{branch}/restore', [BranchController::class, 'restore'])->name('branches.restore');

    Route::resource('workshops',WorkShopController::class);
    Route::patch('/workshops/{workshop}/restore', [WorkShopController::class, 'restore'])->name('workshops.restore');
    Route::delete('/workshops/{workshop}/forceDelete', [WorkShopController::class, 'forceDelete'])->name('workshops.forceDelete');

    Route::resource('brands',BrandController::class)->except('create','edit');
    Route::get('/brands/model/{model}', [BrandController::class, 'showModel'])->name('brands.showModel');
    Route::post('/brands/{brand}/models', [BrandController::class, 'storeModel'])->name('brands.storeModel');
    Route::patch('/brands/models/{model}/edit', [BrandController::class, 'updateModel'])->name('brands.updateModel');
    Route::delete('/brands/models/{model}/delete', [BrandController::class, 'destroyModel'])->name('brands.destroyModel');

    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{user}/forceDelete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

    Route::resource('roles', RoleController::class);
});

require __DIR__.'/auth.php';
