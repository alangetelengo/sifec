<?php

use Illuminate\Support\Facades\Route;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Http\Controllers\NaissanceController;
use Modules\Naissance\Http\Controllers\RequisitionController;
use Modules\Naissance\Http\Controllers\ActeNaissanceController;
use Modules\Naissance\Http\Controllers\LivretNaissanceController;
use Modules\Naissance\Http\Controllers\RequisitionTardiveController;
use Modules\Naissance\Http\Controllers\CertificatDestructionController;
use Modules\Naissance\Http\Controllers\CertificatTranscriptionController;
use Modules\Naissance\Http\Controllers\CertificatNonInscriptionController;
use Modules\Naissance\Http\Controllers\FicheMaterniteController;
use Modules\Naissance\Http\Controllers\JugementController;
use Modules\Naissance\Http\Controllers\RequisitionTranscriptionController;

Route::middleware('auth')->prefix('declarationNaissance')->group(function() {
    Route::get('/', [NaissanceController::class,'index'])->name("declarationNaissance.index");
    Route::get('create', [NaissanceController::class,'create'])->name("declarationNaissance.create");
    Route::get('certificat/non/inscription', [NaissanceController::class,'certificatNonInscription'])->name("declarationNaissance.certificat");
    Route::get('create/certificat', [NaissanceController::class,'createCertificat'])->name("declarationNaissance.certificat.create");
    Route::get('affaire-sociale/create', [NaissanceController::class,'affaireSociale'])->name("declarationNaissance.as");
    Route::post('affaire-sociale/store', [NaissanceController::class,'affaireSocialeStore'])->name("declarationNaissance.as.store");
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

    // Route::put('{id}/adopter', [NaissanceController::class,'adoption'])->name("declarationNaissance.adopter");
    // Route::post('store/adoption/pleniere', [NaissanceController::class,'storeAdoptionPleniere'])->name("declarationNaissance.store.adoption.pleniere");
    Route::get('create/declaration/jugement/{id}', [NaissanceController::class,'createDeclarationJugement'])->name("declarationNaissance.jugement");


    // Route::get('jugementhomologation', [NaissanceController::class,'jugementhomologation'])->name("declarationNaissance.jugementhomologation");
    // Route::post('store/jugementhomologation',[NaissanceController::class,'storeJugementHomologation'])->name("declarationNaissance.store.jugement.homologation");
});

Route::middleware('auth')->prefix('jugement')->group(function() {
    Route::get('/', [JugementController::class,'index'])->name("jugement.index");
    Route::get('create/{id}', [JugementController::class,'create'])->name("jugement.create");
    Route::get('edit/{id}', [JugementController::class,'edit'])->name("jugement.edit");
    Route::put('update/{id}', [JugementController::class,'update'])->name("jugement.update");
    Route::get('show/{id}', [JugementController::class,'show'])->name("jugement.show");
    Route::post('send-jugement', [JugementController::class,'send'])->name("jugement.send");
    Route::delete('destroy/{id}', [JugementController::class,'destroy'])->name("jugement.destroy");
    Route::post('store', [JugementController::class,'store'])->name("jugement.store");

});

Route::middleware('auth')->prefix('acteNaissance')->group(function() {
    Route::get('/', [ActeNaissanceController::class,'index'])->name("acteNaissance.index");
    Route::post("send-otp", [ActeNaissanceController::class,'sendOtp'])->name("acteNaissance.send.otp");
    Route::post("send-otp-bulk", [ActeNaissanceController::class,'sendOtpBulk'])->name("acteNaissance.send.otp.bulk");
    Route::post("validate-otp", [ActeNaissanceController::class,'validateOtp'])->name("acteNaissance.validate.otp");
    Route::post("validate-otp-bulk", [ActeNaissanceController::class,'validateOtpBulk'])->name("acteNaissance.validate.otp.bulk");
    Route::get('{id}/generate',[ActeNaissanceController::class,"displayActe"])->name('acteNaissance.display');
    Route::post('generate-bulk',[ActeNaissanceController::class,"generateActeBulk"])->name('acteNaissance.generate.bulk');
    Route::get('{id}/acte/copie',[ActeNaissanceController::class,"displayCopie"])->name('acteNaissance.copie');
    Route::put('{id}/acte/naissance/approuver', [ActeNaissanceController::class,'naissanceApprouver'])->name('acteNaissance.naissance.approuver');
    Route::post('generate',[ActeNaissanceController::class,"generateActe"])->name('acteNaissance.generate');
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

});
Route::middleware('auth')->prefix('requisition')->group(function() {
    Route::get('/', [RequisitionController::class,'index'])->name("requisition.index");

    Route::get('create/{id}', [RequisitionController::class,'create'])->name("requisition.create");
    // Route::get('create/requisition/{id}', [RequisitionController::class,'createRequisition'])->name("declarationNaissance.jugement");

    Route::get('edit/{id}', [RequisitionController::class,'edit'])->name("requisition.edit");
    Route::put('update/{id}', [RequisitionController::class,'update'])->name("requisition.update");
    Route::get('show/{id}', [RequisitionController::class,'show'])->name("requisition.show");
    Route::post('send-requisition', [RequisitionController::class,'send'])->name("requisition.send");
    Route::delete('destroy/{id}', [RequisitionController::class,'destroy'])->name("requisition.destroy");
    Route::post('store', [RequisitionController::class,'store'])->name("requisition.store");


    // Route::get('{id}/etat/requisition',[RequisitionController::class,"etat"])->name('requisition.etat');
    // Route::get('{id}/destruction/acte/requisition',[RequisitionController::class,"destructionActe"])->name('requisition.destruction.acte');
    // Route::put('{id}/generate/jugement',[RequisitionController::class,"generateJugement"])->name('requisition.generateJugement');
    // Route::get('{id}/jugement/generate', [RequisitionController::class,'etatJugement'])->name("requisition.etatJugement");
    // Route::get('reconstitution/jugement', [RequisitionController::class,'jugement'])->name("requisition.jugement");

});
Route::middleware('auth')->prefix('certificatDestruction')->group(function() {
    Route::get('/', [CertificatDestructionController::class,'index'])->name("certificatDestruction.index");
    Route::get('create/certificatDestruction',[CertificatDestructionController::class,"create"])->name('certificatDestruction.create');
    Route::post('store/certificatDestruction',[CertificatDestructionController::class,"store"])->name('certificatDestruction.store');
    Route::get('{id}/etat/certificatDestruction',[CertificatDestructionController::class,"etat"])->name('certificatDestruction.etat');
    Route::put('{id}/generate/requisition',[CertificatDestructionController::class,"generateRequisition"])->name('certificatDestruction.generateRequisition');
});

//09 Novembre 2022
Route::middleware('auth')->prefix('certificatNonInscription')->group(function() {
    Route::get('/', [CertificatNonInscriptionController::class,'index'])->name("certificatNonInscription.index");
    Route::post('create', [CertificatNonInscriptionController::class,'create'])->name("certificatNonInscription.create");
    Route::post('store', [CertificatNonInscriptionController::class,'store'])->name("certificatNonInscription.store");
    Route::get('{id}/update', [CertificatNonInscriptionController::class,'update'])->name("certificatNonInscription.update");
    Route::get('{id}/requisition/generate', [CertificatNonInscriptionController::class,'etat'])->name("certificatNonInscription.etat");
});

//09 Novembre 2022
Route::middleware('auth')->prefix('RequisitionTardive')->group(function() {
    Route::get('/', [RequisitionTardiveController::class,'index'])->name("RequisitionTardive.index");
    Route::get('{id}/requisitiontardive/generate', [RequisitionTardiveController::class,'etat'])->name("RequisitionTardive.etat");
    Route::put('{id}/generate/requisition',[RequisitionTardiveController::class,"generateRequisition"])->name('RequisitionTardive.generateRequisition');
    Route::post('requisition/generateacte',[RequisitionTardiveController::class,"generateActe"])->name('requisition.generateacte');
});


Route::middleware('auth')->prefix('certificatTranscription')->group(function() {
    Route::get('/', [CertificatTranscriptionController::class,'index'])->name("certificatTranscription.index");
    Route::get('create', [CertificatTranscriptionController::class,'create'])->name("certificatTranscription.create");
    Route::post('store', [CertificatTranscriptionController::class,'store'])->name("certificatTranscription.store");
    Route::get('{id}/certificatTrans/generate', [CertificatTranscriptionController::class,'etat'])->name("certificatTranscription.etat");
});

Route::middleware('auth')->prefix('RequisitionTranscription')->group(function() {
    Route::get('/', [RequisitionTranscriptionController::class,'index'])->name("RequisitionTranscription.index");
    Route::get('{id}/requisitionTranscription/generate', [RequisitionTranscriptionController::class,'etat'])->name("RequisitionTranscription.etat");
    Route::put('{id}/generate/requisition',[RequisitionTranscriptionController::class,"generateRequisition"])->name('RequisitionTranscription.generateRequisition');
    Route::post('requisition/generateacte',[RequisitionTranscriptionController::class,"generateActe"])->name('RequisitionTranscription.generateacte');
});


Route::middleware('auth')->prefix('statistiquesNaissance')->group(function() {
    Route::get('declarations/sexe', [NaissanceController ::class,'statParSexe'])->name("statistiquesNaissance.sexeDeclaration");
    Route::get('declarations/sexe/etat', [NaissanceController ::class,'statParSexeEtat'])->name("statistiquesNaissance.sexeDeclarationEtat");

    Route::get('naissances/sexe', [NaissanceController ::class,'statParSexeActe'])->name("statistiquesNaissance.sexeNaissance");
    Route::get('naissances/sexe/etat', [NaissanceController ::class,'statParSexeActeEtat'])->name("statistiquesNaissance.sexeNaissanceEtat");
    // Route::get('naissances/repertoire', [ActeNaissanceController ::class,'repertoireetat'])->name("statistiquesNaissance.repertoire");
    Route::post('naissances/repertoire', [ActeNaissanceController ::class,'repertoireAlphabetique'])->name("statistiquesNaissance.repertoire.resultat");
});

Route::middleware('auth')->prefix('fiche_maternite')->group(function(){
    Route::get('/', [FicheMaterniteController::class,'index'])->name('fiche_maternite.index');
    Route::get('create',[FicheMaterniteController::class,'create'])->name("fiche_maternite.create");
    Route::post('store',[FicheMaterniteController::class,'store'])->name("fiche_maternite.store");
    Route::get('edit/{id}',[FicheMaterniteController::class,'edit'])->name("fiche_maternite.edit");
    Route::put('update/{id}',[FicheMaterniteController::class,'update'])->name("fiche_maternite.update");
    Route::get('send/notification/{id}',[FicheMaterniteController::class,'sendNotification'])->name("fiche_maternite.send.notification");
});




