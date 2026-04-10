<?php

use Illuminate\Support\Facades\Route;
use Modules\Referentiel\Http\Controllers\LocaliteController;
use Modules\Referentiel\Http\Controllers\TypeLocaliteController;
use Modules\Referentiel\Http\Controllers\RegistreController;
use Modules\Referentiel\Http\Controllers\ReligionController;
use Modules\Referentiel\Http\Controllers\CausedecesController;
use Modules\Referentiel\Http\Controllers\ProfessionController;
use Modules\Referentiel\Http\Controllers\InstitutionController;
use Modules\Referentiel\Http\Controllers\NationaliteController;
use Modules\Referentiel\Http\Controllers\FeuilleRegistreController;
use Modules\Referentiel\Http\Controllers\TypeInstitutionController;
use Modules\Referentiel\Http\Controllers\TypeCategorieInstitutionController;
use Modules\Referentiel\Http\Controllers\InstitutionSecondaireController;
use Modules\Referentiel\Http\Controllers\AppareilController;
use Modules\Referentiel\Http\Controllers\RetraitActeController;

Route::middleware('auth')->prefix('causedeces')->group(function() {
    Route::get('/', [CausedecesController::class,'index'])->name('causedeces.index');
    Route::post('filter', [CausedecesController::class,'filterCauseDeces'])->name('causedeces.filter');
    Route::post('store', [CausedecesController::class,'store'])->name('causedeces.store');
    Route::put('{id}/update', [CausedecesController::class,'update'])->name('causedeces.update');
    Route::delete('{id}/destroy', [CausedecesController::class,'destroy'])->name('causedeces.destroy');

});
Route::middleware('auth')->prefix('profession')->group(function() {
    Route::get('/', [ProfessionController::class,'index'])->name('profession.index');
    Route::post('filter', [ProfessionController::class,'filterProfessions'])->name('profession.filter');
    Route::post('store', [ProfessionController::class,'store'])->name('profession.store');
    Route::put('{id}/update', [ProfessionController::class,'update'])->name('profession.update');
    Route::delete('{id}/destroy', [ProfessionController::class,'destroy'])->name('profession.destroy');
});
Route::middleware('auth')->prefix('religion')->group(function() {
    Route::get('/', [ReligionController::class,'index'])->name('religion.index');
    Route::post('filter', [ReligionController::class,'filterReligions'])->name('religion.filter');
    Route::post('store', [ReligionController::class,'store'])->name('religion.store');
    Route::put('{id}/update', [ReligionController::class,'update'])->name('religion.update');
    Route::delete('{id}/destroy', [ReligionController::class,'destroy'])->name('religion.destroy');
});


Route::middleware('auth')->prefix('institution')->group(function() {
    Route::get('/', [InstitutionController::class,'index'])->name('institution.index');
    Route::post('filter', [InstitutionController::class,'filterInstitutions'])->name('institution.filter');
    Route::get('create', [InstitutionController::class,'create'])->name('institution.create');
    Route::post('store', [InstitutionController::class,'store'])->name('institution.store');
    Route::get('{id}/edit', [InstitutionController::class,'edit'])->name('institution.edit');
    Route::get('getInstitution', [InstitutionController::class,'getInstitution'])->name('institution.get.institution');
    Route::put('{id}/update', [InstitutionController::class,'update'])->name('institution.update');
    Route::delete('{id}/destroy', [InstitutionController::class,'destroy'])->name('institution.destroy');
    Route::get('getLocalite', [InstitutionController::class,'getLocalite'])->name('institution.get.localite');

    // Routes pour la hiérarchie dynamique
    Route::get('available-parents/{id?}', [InstitutionController::class, 'getAvailableParents'])->name('institution.available.parents');
    Route::get('available-parents-by-type/{codeTypeInstitution}', [InstitutionController::class, 'getAvailableParentsByType'])->name('institution.available.parents.by.type');
});

Route::middleware('auth')->prefix('institutionSecondaire')->group(function() {
    Route::get('/', [InstitutionSecondaireController::class,'index'])->name('institutionSecondaire.index');
    Route::get('data/ajax', [InstitutionSecondaireController::class,'intitutionData'])->name('institutionSecondaire.data.ajax');
    Route::post('store', [InstitutionSecondaireController::class,'store'])->name('institutionSecondaire.store');
    Route::put('{id}/update', [InstitutionSecondaireController::class,'update'])->name('institutionSecondaire.update');
    Route::delete('{id}/destroy', [InstitutionSecondaireController::class,'destroy'])->name('institutionSecondaire.destroy');
});
Route::middleware('auth')->prefix('typeInstitution')->group(function() {
    Route::get('/', [TypeInstitutionController::class,'index'])->name('typeInstitution.index');
    Route::post('filter', [TypeInstitutionController::class,'filterTypeInstitutions'])->name('typeInstitution.filter');
    Route::get('create', [TypeInstitutionController::class,'create'])->name('typeInstitution.create');
    Route::post('store', [TypeInstitutionController::class,'store'])->name('typeInstitution.store');
    Route::get('{id}/edit', [TypeInstitutionController::class,'edit'])->name('typeInstitution.edit');
    Route::put('{id}/update', [TypeInstitutionController::class,'update'])->name('typeInstitution.update');
    Route::delete('{id}/destroy', [TypeInstitutionController::class,'destroy'])->name('typeInstitution.destroy');
});

Route::middleware('auth')->prefix('typeCategorieInstitution')->group(function() {
    Route::get('/', [TypeCategorieInstitutionController::class,'index'])->name('typeCategorieInstitution.index');
    Route::post('store', [TypeCategorieInstitutionController::class,'store'])->name('typeCategorieInstitution.store');
    Route::put('{id}/update', [TypeCategorieInstitutionController::class,'update'])->name('typeCategorieInstitution.update');
    Route::delete('{id}/destroy', [TypeCategorieInstitutionController::class,'destroy'])->name('typeCategorieInstitution.destroy');
});


Route::middleware('auth')->prefix('nationalite')->group(function(){
    Route::get('/', [NationaliteController::class,'index'])->name('nationalite.index');
    Route::post('filter', [NationaliteController::class,'filterNationalites'])->name('nationalite.filter');
    Route::post('store', [NationaliteController::class, 'store'])->name('nationalite.store');
    Route::put('{id}/update', [NationaliteController::class, 'update'])->name('nationalite.update');
    Route::delete('{id}/destroy',[NationaliteController::class,'destroy'])->name('nationalite.destroy');
});

Route::middleware("auth")->prefix("registre")->group(function() {
    Route::get("/", [RegistreController::class, 'index'])->name("registre.index");
    Route::get("registre-tribunal", [RegistreController::class, 'registresTribunal'])->name("registre.tribunal");
    Route::post("store", [RegistreController::class, 'store'])->name("registre.store");
    Route::put("{id}/update", [RegistreController::class, 'update'])->name("registre.update");
    Route::get("{id}/send-otp", [RegistreController::class,'sendOtp'])
        ->middleware('throttle:registre-send-otp')
        ->name("registre.send.otp");
    Route::post("validate-otp", [RegistreController::class,'validateOtp'])
        ->middleware('throttle:registre-validate-otp')
        ->name("registre.validate.otp");
    Route::post("close-registre", [RegistreController::class,'cloturerRegistre'])->name("registre.cloture");
    Route::delete("{id}/destroy", [RegistreController::class, 'destroy'])->name("registre.destroy");

    Route::get('{id}/registre-naissance', [RegistreController ::class,'registreNaissance'])->name("registre.naissance");
    Route::get('{id}/feuillet-registre-naissance', [RegistreController ::class,'feuilletRN'])->name("registre.feuillet.registre.naissance");
    Route::get('{id}/registre-mariage', [RegistreController ::class,'registreMariage'])->name("registre.mariage");
    Route::get('{id}/feuillet-registre-mariage', [RegistreController ::class,'feuilletRM'])->name("registre.feuillet.registre.mariage");
    Route::get('{id}/registre-deces', [RegistreController ::class,'registreDeces'])->name("registre.deces");
    Route::get('{id}/feuillet-registre-deces', [RegistreController ::class,'feuilletRD'])->name("registre.feuillet.registre.deces");

    Route::post("registre-add-feuillets", [RegistreController::class,'AddFeuilletRegistre'])->name("registre.add.feuillets");
});

Route::middleware('auth')->prefix('retrait')->group(function() {
    Route::get('/', [RetraitActeController::class,'index'])->name('retrait.index');
    // Route::post("search/acte/retire", [RetraitActeController::class, 'searchActeRetire'])->name("retrait.search.acte");
    Route::post("/", [RetraitActeController::class, 'searchActeRetire'])->name("retrait.search.acte");
});

// Type de Localité
Route::middleware('auth')->prefix('typelocalite')->group(function() {
    Route::get('/', [TypeLocaliteController::class, 'index'])->name('typelocalite.index');
    Route::post('store', [TypeLocaliteController::class, 'store'])->name('typelocalite.store');
    Route::put('{id}/update', [TypeLocaliteController::class, 'update'])->name('typelocalite.update');
    Route::delete('{id}/destroy', [TypeLocaliteController::class, 'destroy'])->name('typelocalite.destroy');
});

// Appareils autorisés
Route::middleware('auth')->prefix('appareil')->group(function () {
    Route::get('/', [AppareilController::class, 'index'])->name('appareil.index');
    Route::post('filter', [AppareilController::class, 'filterAppareils'])->name('appareil.filter');
    Route::post('store', [AppareilController::class, 'store'])->name('appareil.store');
    Route::put('{id}/update', [AppareilController::class, 'update'])->name('appareil.update');
    Route::patch('{id}/toggle-statut', [AppareilController::class, 'toggleStatut'])->name('appareil.toggle.statut');
    Route::delete('{id}/destroy', [AppareilController::class, 'destroy'])->name('appareil.destroy');
});

// Localités (référentiel unique avec TypeLocalite)
Route::middleware('auth')->prefix('localite')->group(function() {
    Route::get('/', [LocaliteController::class, 'index'])->name('localite.index');
    Route::get('enfants/{code}', [LocaliteController::class, 'children'])->name('localite.children');
    Route::post('store', [LocaliteController::class, 'store'])->name('localite.store');
    Route::put('{id}/update', [LocaliteController::class, 'update'])->name('localite.update');
    Route::delete('{id}/destroy', [LocaliteController::class, 'destroy'])->name('localite.destroy');
    Route::post('filter', [LocaliteController::class, 'filterLocalites'])->name('localite.filter');
    Route::get('available-parents/{id?}', [LocaliteController::class, 'getAvailableParents'])->name('localite.available.parents');
    Route::get('available-parents-by-type/{codeTypeLocalite}', [LocaliteController::class, 'getAvailableParentsByType'])->name('localite.available.parents.by.type');
});



