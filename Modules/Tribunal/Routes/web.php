<?php

use Illuminate\Support\Facades\Route;
use Modules\Tribunal\Http\Controllers\TribunalController;
use Modules\Tribunal\Http\Controllers\DocumentTribunalController;

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

Route::middleware("auth")->prefix('tribunal')->group(function() {
    Route::get('/', [TribunalController::class,'index'])->name("tribunal.document.index");
    
    // Routes protégées pour la création/import de jugements
    Route::middleware('can:module.tribunal.jugement.create')->group(function() {
        Route::get('document/create/{type}/{id}', [TribunalController::class,'create'])->name("tribunal.document.create");
        Route::post('document/store/{type}/{id}', [TribunalController::class,'store'])->name("tribunal.document.store");
        Route::get('document/importer/{type}/{code}', [TribunalController::class, 'importDocumentTribunal'])->name('tribunal.document.importer');
    });
    
    Route::get('declarations/voir-document/{type}/{id}', [TribunalController::class,'voirDocument'])->name("tribunal.voir_document");

    // Voir le certificat venant du centre d'état civil
    Route::get('declarations/voir-certificat/{type}/{id}', [TribunalController::class,'voirCertificat'])->name('tribunal.voir_certificat');
    Route::get('detail/certificat/{type}/{id}', [TribunalController::class,'detailCertificat'])->name("tribunal.detail_certificat.show");

    // Envoyer/renvoyer le certificat au centre d'état civil
    Route::post('document/envoyer', [TribunalController::class,'envoyerCertificat'])->name('tribunal.document.envoyer');

    // Envoyer officiellement le dossier traité au centre d'état civil (après import)
    Route::post('document/envoyer-officiel', [TribunalController::class,'envoyerOfficiel'])->name('tribunal.document.envoyer_officiel');

    // Récupérer le nom de la réquisition
    Route::get('document/get-nom-requisition', [TribunalController::class,'getNomRequisition'])->name('tribunal.document.get-nom-requisition');

    // Récupérer le nom du jugement
    Route::get('document/get-nom-jugement', [TribunalController::class,'getNomJugement'])->name('tribunal.document.get-nom-jugement');

    // Route::get('declarations/traitees', [TribunalController::class, 'declarationsTraitees'])->name('tribunal.document.traites');
    Route::get('document/edit/{type}/{id}', [TribunalController::class, 'edit'])->name('tribunal.document.edit');

    Route::post('/tribunal/confirmation-document', [TribunalController::class, 'confirmerDocument'])->name('tribunal.confirmation.document');

    Route::post('/tribunal/renvoyer-certificat', [TribunalController::class, 'renvoyerCertificat'])->name('tribunal.renvoyer.certificat');

    Route::get('document/rectification', [TribunalController::class, 'dossiersRectification'])->name('tribunal.document.rectification');

    //ajouter les nouvelles routes ici
    Route::get('document/historique', [TribunalController::class, 'historique'])->name('tribunal.document.historique');
    Route::get('document/envoyes', [TribunalController::class, 'envoyes'])->name('tribunal.document.envoyes');
    Route::get('document/statistiques', [TribunalController::class, 'statistiques'])->name('tribunal.document.stats');
    Route::get('certificat/pdf/{id}', [TribunalController::class, 'certificatPdf'])->name('tribunal.certificat.pdf');


});

Route::middleware("auth")->prefix('documents')->group(function() {
    Route::get('requisitions', [DocumentTribunalController::class, 'indexRequisitions'])->name('documents.requisitions');
    Route::get('jugements', [DocumentTribunalController::class, 'indexJugements'])->name('documents.jugements');
});
