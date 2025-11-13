<?php

use Illuminate\Support\Facades\Route;
use Modules\Mariage\Http\Controllers\MariageController;
use Modules\Mariage\Http\Controllers\DispenseController;
use Modules\Mariage\Http\Controllers\ActeMariageController;
use Modules\Mariage\Http\Controllers\PublicationController;
use Modules\Mariage\Http\Controllers\EtatsMariageController;
use Modules\Mariage\Http\Controllers\RegistreMariageController;

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

Route::middleware('auth')->prefix('declarationMariage')->group(function() {
    Route::get('/', [MariageController::class,'index'])->name("declarationMariage.index");
    Route::get('create', [MariageController::class,'create'])->name("declarationMariage.create");
    Route::post('store', [MariageController::class,'store'])->name("declarationMariage.store");
    Route::get('{id}/edit', [MariageController::class,'edit'])->name("declarationMariage.edit");
    Route::get('{id}/show', [MariageController::class,'show'])->name("declarationMariage.show");
    Route::put('{id}/update', [MariageController::class,'update'])->name("declarationMariage.update");
    Route::delete('{id}/destroy', [MariageController::class,'destroy'])->name("declarationMariage.destroy");
    Route::get('{id}/etat', [MariageController::class,'etat'])->name("declarationMariage.etat");
	Route::get('verification', [MariageController::class,'verification'])->name("declarationMariage.verification");
	Route::post('recherchePersonne', [MariageController::class,'recherchePersonne'])->name('declarationMariage.recherchePersonne');
	Route::post('rechercheTemoin', [MariageController::class,'rechercheTemoin'])->name('declarationMariage.rechercheTemoin');
    Route::get('regime', [MariageController::class, 'getRegime'])->name('declarationMariage.regime');

    // Routes pour les mouvements
    Route::post('{id}/envoyer-tribunal', [MariageController::class, 'envoyerAuTribunal'])->name('declarationMariage.envoyerTribunal');
    Route::post('{id}/confirmer', [MariageController::class, 'confirmerDossier'])->name('declarationMariage.confirmer');
    Route::post('{id}/renvoyer-centre', [MariageController::class, 'renvoyerAuCentre'])->name('declarationMariage.renvoyerCentre');
    Route::post('{id}/rejeter', [MariageController::class, 'rejeterDeclaration'])->name('declarationMariage.rejeter');
    Route::post('{id}/publier-ban', [MariageController::class, 'publierBanMariage'])->name('declarationMariage.publierBan');
    Route::post('{id}/celebrer', [MariageController::class, 'celebrerMariage'])->name('declarationMariage.celebrer');
    Route::get('{id}/historique-mouvements', [MariageController::class, 'historiqueMovements'])->name('declarationMariage.historique');
    Route::get('{id}/verifier-envoi-tribunal', [MariageController::class, 'verifierEnvoiTribunal'])->name('declarationMariage.verifierEnvoiTribunal');

    // Route pour l'upload des pièces
    Route::post('{id}/piece/{type}', [MariageController::class, 'storePiece'])->name('declarationMariage.piece.store');

    // Route pour les mouvements
    Route::post('mouvement', [MariageController::class,'mouvement'])->name("declarationMariage.mouvement");

});

Route::middleware('auth')->prefix('publicationMariage')->group(function() {
    Route::get('/', [PublicationController::class,'index'])->name("publicationMariage.index");
    Route::get('{id}/show', [PublicationController::class,'show'])->name("publicationMariage.show");
    Route::delete('{id}/destroy', [PublicationController::class,'destroy'])->name("publicationMariage.destroy");
});

Route::middleware('auth')->prefix('acteMariage')->group(function() {
    Route::get('/', [ActeMariageController::class,'index'])->name("acteMariage.index");
    Route::get("acte/search/{id}", [ActeMariageController::class,'searchActe'])->name('acteMariage.search');
    Route::put('{id}/acte/mariage/approuver', [ActeMariageController::class,'mariageApprouver'])->name('acteMariage.mariage.approuver');

    // Routes pour la gestion des OTP et validation
    Route::post("send-otp", [ActeMariageController::class,'sendOtp'])->name("acteMariage.send.otp");
    Route::post("send-otp-bulk", [ActeMariageController::class,'sendOtpBulk'])->name("acteMariage.send.otp.bulk");
    Route::post("validate-otp", [ActeMariageController::class,'validateOtp'])->name("acteMariage.validate.otp");
    Route::post("validate-otp-bulk", [ActeMariageController::class,'validateOtpBulk'])->name("acteMariage.validate.otp.bulk");

    // Routes pour la génération des actes
    Route::post('generate-single', [ActeMariageController::class,'generateActe'])->name('acteMariage.generate.single');
    Route::post('generate-bulk', [ActeMariageController::class,"generateActeBulk"])->name('acteMariage.generate.bulk');

    // Routes pour la confirmation et le renvoi
    Route::post('confirmer', [ActeMariageController::class,'confirmer'])->name('acteMariage.confirmer');
    Route::post('confirmer-bulk', [ActeMariageController::class,'confirmerBulk'])->name('acteMariage.confirmer.bulk');
    Route::post('renvoyer', [ActeMariageController::class,'renvoyer'])->name('acteMariage.renvoyer');
    Route::post('renvoyer-bulk', [ActeMariageController::class,'renvoyerBulk'])->name('acteMariage.renvoyer.bulk');

    // Route pour le retrait d'acte
    Route::post('retrait', [ActeMariageController::class,'retrait'])->name('acteMariage.retrait');

    // Routes pour l'annulation des actes
    Route::post('annuler', [ActeMariageController::class,'annuler'])->name('acteMariage.annuler');
    Route::post('annuler-bulk', [ActeMariageController::class,'annulerBulk'])->name('acteMariage.annuler.bulk');

    // Routes pour les copies et extraits
    Route::get('{id}/copie', [ActeMariageController::class,'copie'])->name('acteMariage.copie');
    Route::get('{id}/extrait', [ActeMariageController::class,'displayExtrait'])->name('acteMariage.displayExtrait');

    // Route pour l'envoi au tribunal
    Route::post('envoyer-tribunal', [ActeMariageController::class,'envoyerTribunal'])->name('acteMariage.envoyer.tribunal');

    // Routes pour le répertoire
    Route::get('acte/repertoire',[ActeMariageController::class,"repertoire"])->name('acteMariage.repertoire');
    Route::get('etat/repertoire',[ActeMariageController::class,"repertoireetat"])->name('acteMariage.repertoireetat');

    // Routes d'impression pour les notifications
    Route::get('{id}/print/acte',[ActeMariageController::class,"printActe"])->name('acteMariage.print.acte');
});

Route::middleware('auth')->prefix('etatMariage')->group(function() {
    Route::get('{id}/declaration', [EtatsMariageController ::class,'declaration'])->name("etatMariage.declaration");
    Route::get('{id}/etatMariage', [EtatsMariageController ::class,'ActeMariage'])->name("etatMariage.acte");
    Route::get('{id}/generate', [EtatsMariageController ::class,'displayActe'])->name("acteMariage.display");
    Route::post('generate',[EtatsMariageController::class,"generateActe"])->name('acteMariage.generate');
    Route::get('certifCoutume', [EtatsMariageController ::class,'certifCoutume'])->name("etatMariage.certifCoutume");
    Route::get('publication', [EtatsMariageController ::class,'publication'])->name("etatMariage.publication");
    Route::get('attestationdote', [EtatsMariageController ::class,'attestationdote'])->name("etatMariage.attestationdote");
    Route::get('{id}/display/requisition', [EtatsMariageController ::class,'displayRequisition'])->name("etatMariage.displayRequisition");
    Route::get('requisition', [EtatsMariageController ::class,'requisition'])->name("etatMariage.requisition");
    Route::put('{id}/generate/requisition',[EtatsMariageController::class,'generateRequisition'])->name("etatMariage.generateRequisition");

    Route::get('livrets',[EtatsMariageController::class,'livretFamilles'])->name("etatMariage.livretFamilles");
    Route::get('{id}/livret',[EtatsMariageController::class,'livretFamille'])->name("etatMariage.livretFamille");
});



