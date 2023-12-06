<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairController;
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
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function() {
    Route::resource('cars',CarController::class);
    Route::post('/cars/{car}/image', [CarController::class, 'storeImage'])->name('cars.storeImage');
    Route::post('/cars/{car}/restore', [CarController::class, 'restore'])->name('cars.restore');
    Route::delete('/cars/{car}/forceDelete', [CarController::class, 'forceDelete'])->name('cars.forceDelete');

    Route::resource('repairs',RepairController::class)->except('index');
    Route::patch('/repairs/{repair}/status', [RepairController::class, 'updateStatus'])->name('repairs.updateStatus');

    Route::resource('branches',BranchController::class);

    Route::resource('workshops',WorkShopController::class);

    Route::resource('brands',BrandController::class)->except('create','edit');
    Route::get('/brands/{model}/show', [BrandController::class, 'showModel'])->name('brands.showModel');
    Route::post('/brands/{brand}/models', [BrandController::class, 'storeModel'])->name('brands.storeModel');
    Route::patch('/brands/models/{model}/edit', [BrandController::class, 'updateModel'])->name('brands.updateModel');
    Route::delete('/brands/models/{model}/delete', [BrandController::class, 'destroyModel'])->name('brands.destroyModel');
});

require __DIR__.'/auth.php';
