<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentification\Http\Controllers\UserController;
use Modules\Authentification\Http\Controllers\FonctionController;
use Modules\Authentification\Http\Controllers\FonctionnaliteController;
use Modules\Authentification\Http\Controllers\ModuleController;


Route::middleware('auth')->prefix('utilisateur')->group(function() {
    Route::get('/', [UserController::class, 'index'])->name('utilisateur.index');//->middleware("can:module.users.users");
    Route::get('create', [UserController::class, 'create'])->name('utilisateur.create');//->middleware("can:module.users.create");
    Route::post('store', [UserController::class, 'store'])->name('utilisateur.store');//->middleware("can:module.users.store");
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('utilisateur.edit');//->middleware("can:module.users.edit");
    Route::get('{id}/profile', [UserController::class, 'profile'])->name('utilisateur.profile');//->middleware("can:module.users.profile");
    Route::put('{id}/profile/signature', [UserController::class, 'signature'])->name('utilisateur.signature');//->middleware("can:module.users.edit");
    Route::put('{id}/profile/sceau', [UserController::class, 'sceau'])->name('utilisateur.sceau');//->middleware("can:module.users.edit");
    Route::put('{id}/update', [UserController::class, 'update'])->name('utilisateur.update');//->middleware("can:module.users.edit");
    Route::delete('{id}/destroy', [UserController::class, 'destroy'])->name('utilisateur.destroy');//->middleware("can:module.users.delete");
    Route::get('search-user', [UserController::class,'searchUser'])->name('utilisateur.search');

    Route::get('getdistricts', [UserController::class,'SearDistrict'])->name("utilisateur.getDistricts");
    Route::get('getcommunes', [UserController::class,'SearCommune'])->name("utilisateur.getCommunes");
    Route::get('getinstitution', [UserController::class,'SearInstitution'])->name("utilisateur.getinstitution");

    Route::get('{id}/assigner-permission', [UserController::class,'assignerFonctionnalite'])->name("utilisateur.assigner.permission");
    Route::post('{id}/assigner-permission', [FonctionController::class, 'storeAssigner'])->name('utilisateur.assigner.store');

});
Route::middleware('auth')->prefix('fonctionnalite')->group(function() {
    Route::get('/', [FonctionnaliteController::class, 'index'])->name('fonctionnalite.index'); //->middleware("can:module.fonctionnalites.fonctionnalites");
    Route::get('create', [FonctionnaliteController::class, 'create'])->name('fonctionnalite.create');
    Route::post('store', [FonctionnaliteController::class, 'store'])->name('fonctionnalite.store');
    Route::get('{id}/edit', [FonctionnaliteController::class, 'edit'])->name('fonctionnalite.edit');
    Route::put('{id}/update', [FonctionnaliteController::class, 'update'])->name('fonctionnalite.update');
    Route::delete('{id}/destroy', [FonctionnaliteController::class, 'destroy'])->name('fonctionnalite.destroy');
});


Route::middleware('auth')->prefix('fonction')->group(function() {
    Route::get('/', [FonctionController::class, 'index'])->name('fonction.index'); //->middleware("can:module.users.fonctions.fonctions");
    Route::get('create', [FonctionController::class, 'create'])->name('fonction.create');
    Route::get('{id}/assigner', [FonctionController::class, 'assigner'])->name('fonction.assigner');
    Route::post('{id}/assigner', [FonctionController::class, 'storeAssigner'])->name('fonction.assigner.store');
    Route::post('store', [FonctionController::class, 'store'])->name('fonction.store');
    Route::put('{id}/update', [FonctionController::class, 'update'])->name('fonction.update');
    Route::delete('{id}/destroy', [FonctionController::class, 'destroy'])->name('fonction.destroy');
});
Route::middleware('auth')->prefix('module')->group(function() {
    Route::get('/', [ModuleController::class, 'index'])->name('module.index');
    Route::get('create', [ModuleController::class, 'create'])->name('module.create');
    Route::post('store', [ModuleController::class, 'store'])->name('module.store');
    Route::get('{id}/edit', [ModuleController::class, 'edit'])->name('module.edit');
    Route::get('{id}/fonctionnalites', [ModuleController::class, 'fonctionnalites'])->name('module.fonctionnalites');
    Route::put('{id}/update', [ModuleController::class, 'update'])->name('module.update');
    Route::delete('{id}/destroy', [ModuleController::class, 'destroy'])->name('module.destroy');
});




