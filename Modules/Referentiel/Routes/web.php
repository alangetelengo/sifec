<?php

use Illuminate\Support\Facades\Route;
use Modules\Referentiel\Http\Controllers\VilleController;
use Modules\Referentiel\Http\Controllers\CommuneController;
use Modules\Referentiel\Http\Controllers\LocaliteController;
use Modules\Referentiel\Http\Controllers\RegistreController;
use Modules\Referentiel\Http\Controllers\ReligionController;
use Modules\Referentiel\Http\Controllers\TribunalController;
use Modules\Referentiel\Http\Controllers\CourAppelController;
use Modules\Referentiel\Http\Controllers\CausedecesController;
use Modules\Referentiel\Http\Controllers\ProfessionController;
use Modules\Referentiel\Http\Controllers\DepartementController;
use Modules\Referentiel\Http\Controllers\InstitutionController;
use Modules\Referentiel\Http\Controllers\NationaliteController;
use Modules\Referentiel\Http\Controllers\ArrondissementController;
use Modules\Referentiel\Http\Controllers\SubDepartementController;
use Modules\Referentiel\Http\Controllers\FeuilleRegistreController;
use Modules\Referentiel\Http\Controllers\TypeInstitutionController;
use Modules\Referentiel\Http\Controllers\CommunauteUrbaineController;
use Modules\Referentiel\Http\Controllers\SubCommuneDistrictController;
use Modules\Referentiel\Http\Controllers\InstitutionSecondaireController;
use Modules\Referentiel\Http\Controllers\RetraitActeController;
use Modules\Referentiel\Http\Controllers\SubArrondissementComUrbaineController;

Route::middleware('auth')->prefix('causedeces')->group(function() {
    Route::get('/', [CausedecesController::class,'index'])->name('causedeces.index');
    Route::post('store', [CausedecesController::class,'store'])->name('causedeces.store');
    Route::put('{id}/update', [CausedecesController::class,'update'])->name('causedeces.update');
    Route::delete('{id}/destroy', [CausedecesController::class,'destroy'])->name('causedeces.destroy');

});
Route::middleware('auth')->prefix('profession')->group(function() {
    Route::get('/', [ProfessionController::class,'index'])->name('profession.index');
    Route::post('store', [ProfessionController::class,'store'])->name('profession.store');
    Route::put('{id}/update', [ProfessionController::class,'update'])->name('profession.update');
    Route::delete('{id}/destroy', [ProfessionController::class,'destroy'])->name('profession.destroy');
});
Route::middleware('auth')->prefix('religion')->group(function() {
    Route::get('/', [ReligionController::class,'index'])->name('religion.index');
    Route::post('store', [ReligionController::class,'store'])->name('religion.store');
    Route::put('{id}/update', [ReligionController::class,'update'])->name('religion.update');
    Route::delete('{id}/destroy', [ReligionController::class,'destroy'])->name('religion.destroy');
});


Route::middleware('auth')->prefix('institution')->group(function() {
    Route::get('/', [InstitutionController::class,'index'])->name('institution.index');

    Route::get('create', [InstitutionController::class,'create'])->name('institution.create');
    Route::post('store', [InstitutionController::class,'store'])->name('institution.store');
    Route::get('{id}/edit', [InstitutionController::class,'edit'])->name('institution.edit');
    Route::get('getInstitution', [InstitutionController::class,'getInstitution'])->name('institution.get.institution');
    Route::put('{id}/update', [InstitutionController::class,'update'])->name('institution.update');
    Route::delete('{id}/destroy', [InstitutionController::class,'destroy'])->name('institution.destroy');

    Route::get('getLocalite', [InstitutionController::class,'getLocalite'])->name('institution.get.localite');
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
    Route::get('create', [TypeInstitutionController::class,'create'])->name('typeInstitution.create');
    Route::post('store', [TypeInstitutionController::class,'store'])->name('typeInstitution.store');
    Route::get('{id}/edit', [TypeInstitutionController::class,'edit'])->name('typeInstitution.edit');
    Route::put('{id}/update', [TypeInstitutionController::class,'update'])->name('typeInstitution.update');
    Route::delete('{id}/destroy', [TypeInstitutionController::class,'destroy'])->name('typeInstitution.destroy');
});

Route::middleware('auth')->prefix('nationalite')->group(function(){
    Route::get('/', [NationaliteController::class,'index'])->name('nationalite.index');
    Route::get('create', [NationaliteController::class,'create'])->name('nationalite.create');
    Route::post('store', [NationaliteController::class, 'store'])->name('nationalite.store');
    Route::get('{id}/edit', [NationaliteController::class,'edit'])->name('nationalite.edit');
    Route::put('{id}/update', [NationaliteController::class, 'update'])->name('nationalite.update');
    Route::delete('{id}/destroy',[NationaliteController::class,'destroy'])->name('nationalite.destroy');
});

Route::middleware("auth")->prefix("registre")->group(function() {
    Route::get("/", [RegistreController::class, 'index'])->name("registre.index");
    Route::get("registre-tribunal", [RegistreController::class, 'registresTribunal'])->name("registre.tribunal");
    Route::post("store", [RegistreController::class, 'store'])->name("registre.store");
    Route::put("{id}/update", [RegistreController::class, 'update'])->name("registre.update");
    Route::get("{id}/send-otp", [RegistreController::class,'sendOtp'])->name("registre.send.otp");
    Route::post("validate-otp", [RegistreController::class,'validateOtp'])->name("registre.validate.otp");
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

///MODIFICATION REFERENTIEL ECLATEMENT DES LOCALITES Alange par rapport model localite-typelocalite
Route::middleware("auth")->prefix("departement")->group(function() {
    Route::get("/", [DepartementController::class, 'index'])->name("departement.index");
    Route::post("store", [DepartementController::class, 'store'])->name("departement.store");
    Route::put("{id}/update", [DepartementController::class, 'update'])->name("departement.update");
    Route::delete("{id}/destroy", [DepartementController::class, 'destroy'])->name("departement.destroy");
});
Route::middleware("auth")->prefix("communedistrict")->group(function() {
    Route::get("/", [SubDepartementController::class, 'index'])->name("communedistrict.index");
    Route::post("store", [SubDepartementController::class, 'store'])->name("communedistrict.store");
    Route::put("{id}/update", [SubDepartementController::class, 'update'])->name("communedistrict.update");
    Route::delete("{id}/destroy", [SubDepartementController::class, 'destroy'])->name("communedistrict.destroy");
});
Route::middleware("auth")->prefix("arrondissementCommunauteUrbaine")->group(function() {
    Route::get("/", [SubCommuneDistrictController::class, 'index'])->name("arrondissementCommunauteUrbaine.index");
    Route::post("store", [SubCommuneDistrictController::class, 'store'])->name("arrondissementCommunauteUrbaine.store");
    Route::put("{id}/update", [SubCommuneDistrictController::class, 'update'])->name("arrondissementCommunauteUrbaine.update");
    Route::delete("{id}/destroy", [SubCommuneDistrictController::class, 'destroy'])->name("arrondissementCommunauteUrbaine.destroy");
});


Route::middleware('auth')->prefix('quartierVillage')->group(function() {
    Route::get('/', [SubArrondissementComUrbaineController::class,'index'])->name('quartierVillage.index');
    Route::get('create', [SubArrondissementComUrbaineController::class,'create'])->name('quartierVillage.create');
    Route::post('store', [SubArrondissementComUrbaineController::class,'store'])->name('quartierVillage.store');
    Route::get('{id}/edit', [SubArrondissementComUrbaineController::class,'edit'])->name('quartierVillage.edit');
    Route::put('{id}/update', [SubArrondissementComUrbaineController::class,'update'])->name('quartierVillage.update');
    Route::delete('{id}/destroy', [SubArrondissementComUrbaineController::class,'destroy'])->name('quartierVillage.destroy');
});


Route::middleware('auth')->prefix('retrait')->group(function() {
    Route::get('/', [RetraitActeController::class,'index'])->name('retrait.index');
    // Route::post("search/acte/retire", [RetraitActeController::class, 'searchActeRetire'])->name("retrait.search.acte");
    Route::post("/", [RetraitActeController::class, 'searchActeRetire'])->name("retrait.search.acte");
});

// Localités
Route::middleware('auth')->prefix('localite')->group(function() {

    //localite.get.sub-departement
    Route::get('{id}/commune-district', [LocaliteController::class, 'getSubDepartement'])->name('localite.commune.district');
    //localite.get.sub-commune-district
    Route::get('{id}/arrondissement-communaute', [LocaliteController::class, 'getSubCommuneDistrict'])->name('localite.arrondissement.communaute');
    //localite.get.sub-arrondissement-communaute-urbaine
    Route::get('{id}/quartier-village', [LocaliteController::class, 'getSubArrondissementComUrbaine'])->name('localite.quartier.village');
});



