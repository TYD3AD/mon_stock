<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PharmacieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProduitsController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pharmacie', [PharmacieController::class, 'index'])->name('pharmacie.index');
    Route::post('/pharmacie', [PharmacieController::class, 'index'])->name('pharmacie.index');

    Route::get('/produits/{produit}/edit', [ProduitsController::class, 'edit'])->name('produit.edit');
    Route::get('/produits/{produit}/update', [ProduitsController::class, 'update'])->name('produit.update');

});

require __DIR__.'/auth.php';
