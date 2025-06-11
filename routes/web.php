<?php

use App\Http\Controllers\AccesAntenneController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GestionAntenneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/produits/create', [ProduitsController::class, 'create'])->name('produit.create');
    Route::post('/produits/store', [ProduitsController::class, 'store'])->name('produit.store');
    Route::get('/produits/{produit}/edit', [ProduitsController::class, 'edit'])->name('produit.edit');
    Route::delete('/produits/{produit}/delete', [ProduitsController::class, 'delete'])->name('produit.delete');
    Route::put('/produits/{id}/update', [ProduitsController::class, 'update'])->name('produits.update');
    Route::get('/produits/{produit}/update', [ProduitsController::class, 'update'])->name('produit.update'); // à supprimer ?
    Route::get('/produits/{produit}/transferView', [ProduitsController::class, 'transferView'])->name('produit.transferView');
    Route::put('/produits/{produit}/transfertUpdate', [ProduitsController::class, 'transfertUpdate'])->name('produit.transfertUpdate');

    Route::get('/produits/listAcess/{antenne}/{categorie}', [ProduitsController::class, 'listAccess'])->name('produits.listAccess');
    Route::get('/produits/listAcess/{antenne}/{categorie}/{id?}', [ProduitsController::class, 'listAccess'])->name('produits.listAccess');

    Route::get('/commandes/store', [CommandeController::class, 'store'])->name('commandes.store');

    Route::get('/gestion-antenne/store', [GestionAntenneController::class, 'store'])->name('gestion-antenne.store');

    Route::post('/antennes/{antenne}/utilisateurs', [AccesAntenneController::class, 'ajouterUtilisateur'])->name('antennes.utilisateurs.ajouter');
    Route::post('/antennes/{antenne}/utilisateurs/{user}/toggle-responsable', [AccesAntenneController::class, 'toggleResponsable'])->name('antennes.utilisateurs.toggle-responsable');
    Route::post('/antennes/{antenne}/users/{user}/toggle-responsable', [AccesAntenneController::class, 'toggleResponsable']);



});

require __DIR__.'/auth.php';
