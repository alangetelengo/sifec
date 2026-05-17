<?php

use Illuminate\Support\Facades\Route;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Http\Controllers\JugementController;
use Modules\Naissance\Http\Controllers\NaissanceController;
use Modules\Naissance\Http\Controllers\RequisitionController;
use Modules\Naissance\Http\Controllers\ActeNaissanceController;
use Modules\Naissance\Http\Controllers\LivretNaissanceController;
use Modules\Naissance\Http\Controllers\MouvementNaissanceController;
use Modules\Naissance\Http\Controllers\RequisitionTardiveController;
use Modules\Naissance\Http\Controllers\CertificatDestructionController;
use Modules\Naissance\Http\Controllers\CertificatTranscriptionController;
use Modules\Naissance\Http\Controllers\CertificatNonInscriptionController;
use Modules\Naissance\Http\Controllers\RequisitionTranscriptionController;
use Modules\Naissance\Http\Controllers\DeclarationNaissanceController;

Route::middleware('auth')->prefix('declarationNaissance')->group(function() {
    Route::get('/', [NaissanceController::class,'index'])->name("declarationNaissance.index");
    Route::get('create', [NaissanceController::class,'create'])->name("declarationNaissance.create");
    Route::get('certificat/non/inscription', [NaissanceController::class,'certificatNonInscription'])->name("declarationNaissance.certificat");
    Route::get('create/certificat', [NaissanceController::class,'createCertificat'])->name("declarationNaissance.certificat.create");
    Route::get('affaire-sociale/create', [NaissanceController::class,'affaireSociale'])->name("declarationNaissance.as");
    Route::post('affaire-sociale/store', [NaissanceController::class,'store'])->name("declarationNaissance.as.store");
    Route::post('recherchePersonne', [NaissanceController::class,'recherchePersonne'])->name('declarationNaissance.recherchePersonne');
    Route::post('store', [NaissanceController::class,'store'])->name("declarationNaissance.store");
    Route::get('{id}/edit', [NaissanceController::class,'edit'])->name("declarationNaissance.edit");
    Route::get('{id}/show', [NaissanceController::class,'show'])->name("declarationNaissance.show");
    Route::get('{id}/joindre/document', [NaissanceController::class,'joindreDocument'])->name("declarationNaissance.joindre.document");
    Route::post('mouvement', [NaissanceController::class,'mouvement'])->name("declarationNaissance.mouvement");
    Route::put('{id}/mouvement', [NaissanceController::class,'mouvementEdit'])->name("declarationNaissance.mouvement.edit");
    Route::delete('{id}/mouvement/destroy', [NaissanceController::class,'mouvementDelete'])->name("declarationNaissance.mouvement.delete");
    Route::put('{id}/update', [NaissanceController::class,'update'])->name("declarationNaissance.update");
    Route::delete('{id}/destroy', [NaissanceController::class,'destroy'])->name("declarationNaissance.destroy");
    Route::get('{id}/etat', [NaissanceController::class,'etat'])->name("declarationNaissance.etat");
    Route::get('{id}/voir-etat', [NaissanceController::class,'voirEtat'])->name("declarationNaissance.voir.etat");
    Route::get('searcharrcomurb', [NaissanceController::class,'searchArrondissement'])->name('declarationNaissance.search.arrond');
    Route::get('searchquartier', [NaissanceController::class,'searchQuartier'])->name('declarationNaissance.search.quartier');
    Route::get('searchquartiervillage/{id}', [NaissanceController::class,'searchLocalite'])->name('declarationNaissance.search.quartier.village');
    Route::get('enfantTrouve/create', [NaissanceController::class,'enfantTrouve'])->name("declarationNaissance.enfantTrouve");
    Route::get('searchInstitution', [NaissanceController::class,'searchInstitution'])->name('declarationNaissance.search.institution');
    Route::get('tardive', [NaissanceController::class,'tardive'])->name("declarationNaissance.tardive");
    Route::get('paternite', [NaissanceController::class,'paternite'])->name("declarationNaissance.paternite");
    Route::post("scannerpdf", [NaissanceController::class,'storeScanner'])->name("declarationNaissance.store.scannerpdf");
    Route::post("importerpdf", [NaissanceController::class,'storeImporter'])->name("declarationNaissance.store.importer");
    Route::get('{id}/get-document', [NaissanceController::class, 'getDocument'])->name("declarationNaissance.get.document");
    Route::get('{id}/document', [NaissanceController::class, 'showDocument'])->name("declarationNaissance.show.document");
    Route::delete('{id}/destroy/document', [NaissanceController::class, 'deleteDocument'])->name("declarationNaissance.destroy.document");

    Route::get('create/declaration/jugement/{id}', [NaissanceController::class,'createDeclarationJugement'])->name("declarationNaissance.jugement");


    Route::post('declaration-naissance/{code}/piece/{type}', [NaissanceController::class, 'storePiece'])
        ->name('declarationNaissance.piece.store');


});

Route::middleware('auth')->prefix('acteNaissance')->group(function() {
    Route::get('/', [ActeNaissanceController::class,'index'])->name("acteNaissance.index");
    Route::post('filter-documents', [ActeNaissanceController::class,'filterDocuments'])->name("acteNaissance.filter.documents");
    Route::post('filter-actes', [ActeNaissanceController::class,'filterActes'])->name("acteNaissance.filter.actes");
    Route::post("send-otp", [ActeNaissanceController::class,'sendOtp'])->name("acteNaissance.send.otp");
    Route::post("send-otp-bulk", [ActeNaissanceController::class,'sendOtpBulk'])->name("acteNaissance.send.otp.bulk");
    Route::post("validate-otp", [ActeNaissanceController::class,'validateOtp'])->name("acteNaissance.validate.otp");
    Route::post("validate-otp-bulk", [ActeNaissanceController::class,'validateOtpBulk'])->name("acteNaissance.validate.otp.bulk");
    
    // Routes OTP pour l'annulation d'acte
    Route::post("send-otp-annulation", [ActeNaissanceController::class,'sendOtpAnnulation'])->name("acteNaissance.send.otp.annulation");
    Route::post("send-otp-annulation-bulk", [ActeNaissanceController::class,'sendOtpAnnulationBulk'])->name("acteNaissance.send.otp.annulation.bulk");
    Route::post("validate-otp-annulation", [ActeNaissanceController::class,'validateOtpAnnulation'])->name("acteNaissance.validate.otp.annulation");
    Route::post("validate-otp-annulation-bulk", [ActeNaissanceController::class,'validateOtpAnnulationBulk'])->name("acteNaissance.validate.otp.annulation.bulk");
    Route::get('{id}/generate',[ActeNaissanceController::class,"displayActe"])->name('acteNaissance.display');
    Route::post('generate-bulk',[ActeNaissanceController::class,"generateActeBulk"])->name('acteNaissance.generate.bulk');
    Route::get('{id}/acte/copie',[ActeNaissanceController::class,"displayCopie"])->name('acteNaissance.copie');
    Route::put('{id}/acte/naissance/approuver', [ActeNaissanceController::class,'naissanceApprouver'])->name('acteNaissance.naissance.approuver');
    Route::post('generate',[ActeNaissanceController::class,"generateActe"])->name('acteNaissance.generate.single');
    Route::get("acte/search", [ActeNaissanceController::class,'searchActe'])->name('ActeNaissance.search');
    Route::get("{id}/generate/duplicata", [ActeNaissanceController::class,'displayDuplicata'])->name('ActeNaissance.generate.duplicata');
    Route::get("repertoire", [ActeNaissanceController::class,'repertoire'])->name('ActeNaissance.repertoire');


    Route::get('{id}/generate/maquette',[ActeNaissanceController::class,"displayActeMaquette2"])->name('acteNaissance.displaymaquette2');
    Route::get('{id}/generate/copie',[ActeNaissanceController::class,"displayCopieMaquette2"])->name('acteNaissance.displayCopieMaquette2');
    Route::get('{id}/generate/displaySouche',[ActeNaissanceController::class,"displaySouche"])->name('acteNaissance.displaySouche');

    Route::post('retrait-acte', [ActeNaissanceController::class,"retraitActe"])->name("acteNaissance.retrait");

    Route::get('{id}/generate/displayExtrait',[ActeNaissanceController::class,"displayExtrait"])->name('acteNaissance.displayExtrait');
    Route::get('{id}/print/acte',[ActeNaissanceController::class,"printActe"])->name('acteNaissance.print.acte');
    Route::get('{id}/print/copie',[ActeNaissanceController::class,"printCopie"])->name('acteNaissance.print.copie');
    Route::get('{id}/print/extrait',[ActeNaissanceController::class,"printExtrait"])->name('acteNaissance.print.extrait');
    Route::post('find/acte',[ActeNaissanceController::class,"findActe"])->name('acteNaissance.find.acte');


    // Route::get('acte/suppression',[ActeNaissanceController::class,"suppression"])->name('acteNaissance.suppression');
    Route::put('acte/{id}/valider/annulation',[ActeNaissanceController::class,"validerAnnulation"])->name('acteNaissance.valider.annulation');
    // Route::get('acte/suppressionacte',[ActeNaissanceController::class,"suppressionacte"])->name('acteNaissance.suppressionacte');

    // Route::get('acte/rectification',[ActeNaissanceController::class,"rectification"])->name('acteNaissance.rectification');
    Route::get('acte/rectificationacte',[ActeNaissanceController::class,"rectificationacte"])->name('acteNaissance.rectificationacte');

    // Nouvelle route pour l'annulation des actes
    Route::post('annuler', [ActeNaissanceController::class, 'annulerActe'])->name('acteNaissance.annuler');
    Route::post('annuler/bulk', [ActeNaissanceController::class, 'annulerActesBulk'])->name('acteNaissance.annuler.bulk');

    Route::post('confirmer', [ActeNaissanceController::class, 'confirmerDossier'])->name('acteNaissance.confirmer');
    Route::post('confirmer/bulk', [ActeNaissanceController::class, 'confirmerDossiersBulk'])->name('acteNaissance.confirmer.bulk');
    Route::post('renvoyer/bulk', [ActeNaissanceController::class, 'renvoyerDossiersBulk'])->name('acteNaissance.renvoyer.bulk');
    Route::post('renvoyer', [ActeNaissanceController::class, 'renvoyerDossier'])->name('acteNaissance.renvoyer');
});

Route::middleware('auth')->prefix('certificatDestruction')->group(function() {

    Route::get('/', [CertificatDestructionController::class,'index'])->name("certificatDestruction.index");
    Route::get('create', [CertificatDestructionController::class,'create'])->name("certificatDestruction.create");
    Route::get('show/{id}', [CertificatDestructionController::class, 'show'])->name('certificatDestruction.show');
    Route::post('mouvement', [CertificatDestructionController::class, 'envoyerAuTribunal'])->name('certificatDestruction.mouvement');
    Route::post('store', [CertificatDestructionController::class,'store'])->name("certificatDestruction.store");
    Route::get('{id}/update', [CertificatDestructionController::class,'update'])->name("certificatDestruction.update");
    Route::get('{id}/requisition/generate', [CertificatDestructionController::class,'etat'])->name("certificatDestruction.etat");


});


Route::middleware('auth')->prefix('certificatNonInscription')->group(function() {
    Route::get('/', [CertificatNonInscriptionController::class,'index'])->name("certificatNonInscription.index");
    Route::get('create', [CertificatNonInscriptionController::class,'create'])->name("certificatNonInscription.create");
    Route::get('show/{id}', [CertificatNonInscriptionController::class, 'show'])->name('certificatNonInscription.show');
    Route::post('mouvement', [CertificatNonInscriptionController::class, 'envoyerAuTribunal'])->name('certificatNonInscription.mouvement');
    Route::post('store', [CertificatNonInscriptionController::class,'store'])->name("certificatNonInscription.store");
    Route::get('{id}/update', [CertificatNonInscriptionController::class,'update'])->name("certificatNonInscription.update");
    Route::get('{id}/requisition/generate', [CertificatNonInscriptionController::class,'etat'])->name("certificatNonInscription.etat");
});





Route::middleware('auth')->prefix('certificatTranscription')->group(function() {
    Route::get('/', [CertificatTranscriptionController::class,'index'])->name("certificatTranscription.index");
    Route::get('create', [CertificatTranscriptionController::class,'create'])->name("certificatTranscription.create");
    Route::get('show/{id}', [CertificatTranscriptionController::class, 'show'])->name('certificatTranscription.show');
    Route::get('{id}/certificatTrans/generate', [CertificatTranscriptionController::class,'etat'])->name("certificatTranscription.etat");
    Route::post('mouvement', [CertificatTranscriptionController::class, 'envoyerAuTribunal'])->name('certificatTranscription.mouvement');
    Route::post('store', [CertificatTranscriptionController::class,'store'])->name("certificatTranscription.store");
    Route::get('{id}/edit', [CertificatTranscriptionController::class,'edit'])->name("certificatTranscription.edit");
    Route::delete('{id}/destroy', [CertificatTranscriptionController::class,'destroy'])->name("certificatTranscription.destroy");
});



Route::middleware('auth')->prefix('statistiquesNaissance')->group(function() {
    Route::get('declarations/sexe', [NaissanceController ::class,'statParSexe'])->name("statistiquesNaissance.sexeDeclaration");
    Route::get('declarations/sexe/etat', [NaissanceController ::class,'statParSexeEtat'])->name("statistiquesNaissance.sexeDeclarationEtat");

    Route::get('naissances/sexe', [NaissanceController ::class,'statParSexeActe'])->name("statistiquesNaissance.sexeNaissance");
    Route::get('naissances/sexe/etat', [NaissanceController ::class,'statParSexeActeEtat'])->name("statistiquesNaissance.sexeNaissanceEtat");
    // Route::get('naissances/repertoire', [ActeNaissanceController ::class,'repertoireetat'])->name("statistiquesNaissance.repertoire");
    Route::post('naissances/repertoire', [ActeNaissanceController ::class,'repertoireAlphabetique'])->name("statistiquesNaissance.repertoire.resultat");
});

// Mouvements de naissance
Route::middleware('auth')->prefix('naissance/mouvements')->name('naissance.mouvements.')->group(function () {
    Route::get('historique/{id}', [MouvementNaissanceController::class, 'historique'])->name('historique');
    Route::get('create/{id}', [MouvementNaissanceController::class, 'create'])->name('create');
    Route::post('store/{id}', [MouvementNaissanceController::class, 'store'])->name('store');
    Route::get('edit/{id}', [MouvementNaissanceController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [MouvementNaissanceController::class, 'update'])->name('update');
    Route::delete('destroy/{id}', [MouvementNaissanceController::class, 'destroy'])->name('destroy');
});
