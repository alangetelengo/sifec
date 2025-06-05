<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaiementDocumentController;
use Modules\EtatCivilRDC\Http\Controllers\EtatCivilRDCController;

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

//gestion d'Etat civil contexte RDC
Route::middleware('auth')->prefix('paiement_document')->group(function(){
    Route::get('/', [PaiementDocumentController::class,'index'])->name("paiement_document.index");
    Route::post('transmanager', [PaiementDocumentController::class,'transManager'])->name("paiement_document.transmanager");
    Route::post('store', [PaiementDocumentController::class,'store'])->name("paiement_document.payement");

    //route affichage de l'état de recocuvrement des Recettes relatives aux authentifications des actes par les administrations
Route::get('etatRecouvrement',[PaiementDocumentController::class,"etatRecouvrement"])->name('paiement_document.etatRecouvement');
});

//Fin de gestion d'Etat civil contexte RDC


Route::middleware('auth')->prefix('dashboard_gouv')->group(function(){
    Route::get("/", [EtatCivilRDCController::class, 'index'])->name("dashboard_gouv.index");
    Route::get("detaildashboard", [EtatCivilRDCController::class, 'detailDashboard'])->name("dashboard_gouv.detail");
    Route::get("getrecettecec/{id}", [EtatCivilRDCController::class, 'getRectteCec'])->name("dashboard_gouv.recette.cec");
    Route::get("getrecettemois/{id}", [EtatCivilRDCController::class, 'getRectteMois'])->name("dashboard_gouv.recette.mois");
    Route::get("reloaddetaildashboard", [EtatCivilRDCController::class, 'recetteAnnuelle'])->name("dashboard_gouv.recette.annuelle");
    Route::get("statcarte", [EtatCivilRDCController::class, 'statCarte'])->name("dashboard_gouv.stat.carte");


    Route::get("getcommuneprovince/{id}", [EtatCivilRDCController::class, 'getlocaCommuneProvince'])->name("dashboard_gouv.getCommune.province");
    Route::get("getArrondissementCommune/{id}", [EtatCivilRDCController::class, 'getArrondissementCommune'])->name("dashboard_gouv.getArrondissement.commune");

    Route::get  ("faitsdashboard", [EtatCivilRDCController::class, 'faitStatIndex'])->name("dashboard_gouv.fait.stats");
    Route::get  ("faitsNaissancesdashboard", [EtatCivilRDCController::class, 'faitsStat'])->name("dashboard_gouv.fait.stats.get");

});
