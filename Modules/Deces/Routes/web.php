<?php

use Illuminate\Support\Facades\Route;
use Modules\Deces\Entities\ActeDeces;
use Modules\Deces\Http\Controllers\ActeDecesController;
use Modules\Deces\Http\Controllers\CentreHygieneController;
use Modules\Deces\Http\Controllers\CertificatNonInscriptionController;
use Modules\Deces\Http\Controllers\CertificatTranscriptionController;
use Modules\Deces\Http\Controllers\DecesController;
use Modules\Deces\Http\Controllers\RequisitionNonInscriptionDeceController;
use Modules\Deces\Http\Controllers\RequisitionTardiveController;
use Modules\Deces\Http\Controllers\RequisitionTranscriptionDecesController;

Route::middleware('auth')->prefix('declarationDeces')->group(function () {
    Route::get('/', [DecesController::class, 'index'])->name('declarationDeces.index');

    Route::get('certificat/constatation', [DecesController::class, 'certificatConstatation'])->name('declarationDeces.certificat.constatation');
    Route::get('create/certificat/constatation', [DecesController::class, 'createCertificatConstatation'])->name('declarationDeces.certificat.constatation.create');

    Route::get('create', [DecesController::class, 'create'])->name('declarationDeces.create');
    Route::post('store', [DecesController::class, 'store'])->name('declarationDeces.store');

    Route::post('mouvement', [DecesController::class, 'mouvement'])->name('declarationDeces.mouvement');
    Route::put('{id}/mouvement', [DecesController::class, 'mouvementEdit'])->name('declarationDeces.mouvement.edit');
    Route::delete('{id}/mouvement/destroy', [DecesController::class, 'mouvementDelete'])->name('declarationDeces.mouvement.delete');

    Route::get('{id}/edit', [DecesController::class, 'edit'])->name('declarationDeces.edit');
    Route::get('{id}/show', [DecesController::class, 'show'])->name('declarationDeces.show');
    Route::put('{id}/update', [DecesController::class, 'update'])->name('declarationDeces.update');
    // route updateDeclarationDeces
    // mise à jour de la déclaration de décès
    Route::post('updateDeclarationDeces', [DecesController::class, 'updateDeclarationDeces'])->name('declarationDeces.updateDeclarationDeces');

    Route::post('{id}/destroy', [DecesController::class, 'destroy'])->name('declarationDeces.destroy');
    Route::get('{id}/etat', [DecesController::class, 'etat'])->name('declarationDeces.etat');

    Route::get('tardive', [DecesController::class, 'declarationTardive'])->name('declarationDeces.tardive');
    Route::post('rechercheDefent', [DecesController::class, 'rechercheDefunt'])->name('declarationDeces.rechercheDefunt');

    Route::get('createautorisationtransfert', [DecesController::class, 'createTransfertDepouille'])->name('declarationDeces.create.autorisationtransfert');
    Route::get('autorisationtransfert', [DecesController::class, 'autorisationtransfert'])->name('declarationDeces.autorisationtransfert');
    Route::get('{id}/autorisationtransfertetat', [DecesController::class, 'autorisationtransfertetat'])->name('declarationDeces.autorisationtransfertetat');

    Route::post('declaration-deces/{code}/piece/{type}', [DecesController::class, 'storePiece'])->name('declarationDeces.piece.store');

});

Route::middleware('auth')->prefix('centreHygiene')->group(function () {
    Route::get('/', [CentreHygieneController::class, 'index'])->name('centreHygiene.index');
    Route::get('create', [CentreHygieneController::class, 'create'])->name('centreHygiene.create');
    Route::post('store', [CentreHygieneController::class, 'store'])->name('centreHygiene.store');
    Route::get('{id}/edit', [CentreHygieneController::class, 'edit'])->name('centreHygiene.edit');
    Route::get('{id}/show', [CentreHygieneController::class, 'show'])->name('centreHygiene.show');
    Route::put('{id}/update', [CentreHygieneController::class, 'update'])->name('centreHygiene.update');
    Route::post('{id}/destroy', [CentreHygieneController::class, 'destroy'])->name('centreHygiene.destroy');
    Route::get('{id}/etat', [CentreHygieneController::class, 'etat'])->name('centreHygiene.etat');
});

Route::middleware('auth')->prefix('acteDeces')->group(function () {
    Route::get('/', [ActeDecesController::class, 'index'])->name('acteDeces.index');
    Route::post('filter-documents', [ActeDecesController::class, 'filterDocuments'])->name('acteDeces.filter.documents');
    Route::post('filter-actes', [ActeDecesController::class, 'filterActes'])->name('acteDeces.filter.actes');
    Route::post('send-otp', [ActeDecesController::class, 'sendOtp'])->name('acteDeces.send.otp');
    Route::post('validate-otp', [ActeDecesController::class, 'validateOtp'])->name('acteDeces.validate.otp');
    Route::get('{id}/generate', [ActeDecesController::class, 'displayActe'])->name('acteDeces.display');
    Route::post('validate-otp-bulk', [ActeDecesController::class, 'validateOtpBulk'])->name('acteDeces.validate.otp.bulk');
    // Route::post('generate',[ActeDecesController::class,"generateActe"])->name('acteDeces.generate');
    Route::post('send-otp-bulk', [ActeDecesController::class, 'sendOtpBulk'])->name('acteDeces.send.otp.bulk');
    Route::put('{id}/acte/deces/approuver', [ActeDecesController::class, 'decesApprouver'])->name('acteDeces.approuver');
    Route::post('generate-bulk', [ActeDecesController::class, 'generateActeBulk'])->name('acteDeces.generate.bulk');
    Route::get('{id}/generateCopie', [ActeDecesController::class, 'displayCopie'])->name('acteDeces.displayCopie');
    Route::get('{id}/generateDuplicata', [ActeDecesController::class, 'displayDuplicata'])->name('acteDeces.displayDuplicata');
    Route::get('{id}/generateExtrait', [ActeDecesController::class, 'displayExtrait'])->name('acteDeces.displayExtrait');
    Route::get('actedeces/search', [ActeDecesController::class, 'searchActe'])->name('acteDeces.search');
    Route::post('annuler', [ActeDecesController::class, 'annulerActe'])->name('acteDeces.annuler');
    Route::post('annuler-bulk', [ActeDecesController::class, 'annulerActeBulk'])->name('acteDeces.annuler.bulk');
    Route::post('generate-single', [ActeDecesController::class, 'generateActe'])->name('acteDeces.generate.single');
    Route::post('renvoyer', [ActeDecesController::class, 'renvoyer'])->name('acteDeces.renvoyer');

    Route::post('retrait-acte', [ActeDecesController::class, 'retraitActe'])->name('acteDeces.retrait');

    Route::post('confirmer', [ActeDecesController::class, 'confirmerDossier'])->name('acteDeces.confirmer');
    Route::post('confirmer/bulk', [ActeDecesController::class, 'confirmerDossiersBulk'])->name('acteDeces.confirmer.bulk');
    Route::post('renvoyer/bulk', [ActeDecesController::class, 'renvoyerDossiersBulk'])->name('acteDeces.renvoyer.bulk');
    Route::get('{id}/print/acte', [ActeDecesController::class, 'printActe'])->name('acteDeces.print.acte');

});

Route::middleware('auth')->prefix('certificatNonInscriptionDeces')->group(function () {
    Route::get('/', [CertificatNonInscriptionController::class, 'index'])->name('certificatNonInscriptionDeces.index');
    Route::get('{id}/show', [CertificatNonInscriptionController::class, 'show'])->name('certificatNonInscriptionDeces.show');
    Route::get('create', [CertificatNonInscriptionController::class, 'create'])->name('certificatNonInscriptionDeces.create');
    Route::get('{id}/generate/certificat', [CertificatNonInscriptionController::class, 'displayCertificat'])->name('certificatNonInscriptionDeces.displayCertificat');
    Route::post('generate', [CertificatNonInscriptionController::class, 'generateActe'])->name('certificatNonInscriptionDeces.generate');
    Route::post('store', [CertificatNonInscriptionController::class, 'store'])->name('certificatNonInscriptionDeces.store');
    Route::post('mouvement', [CertificatNonInscriptionController::class, 'envoyerAuTribunal'])->name('certificatNonInscriptionDeces.mouvement');
});
Route::middleware('auth')->prefix('declarationTardiveDeces')->group(function () {
    Route::get('/', [CertificatNonInscriptionController::class, 'declarationTardive'])->name('declarationTardiveDeces.index');
});

Route::middleware('auth')->prefix('CertificatTranscriptionDeces')->group(function () {
    Route::get('/', [CertificatTranscriptionController::class, 'index'])->name('certificatTranscriptionDeces.index');
    Route::get('create', [DecesController::class, 'certificatNonIscription'])->name('certificatTranscriptionDeces.create');
    Route::get('{id}/generate/certificat', [CertificatTranscriptionController::class, 'displayCertificat'])->name('certificatTranscriptionDeces.displayCertificat');
    Route::post('generate', [CertificatTranscriptionController::class, 'generateActe'])->name('certificatTranscriptionDeces.generate');
    Route::post('store', [CertificatTranscriptionController::class, 'store'])->name('certificatTranscriptionDeces.store');
});

Route::middleware('auth')->prefix('RequisitionTardiveDeces')->group(function () {
    Route::get('/', [RequisitionTardiveController::class, 'index'])->name('RequisitionTardiveDeces.index');
    Route::get('{id}/requisition/generate', [RequisitionTardiveController::class, 'etat'])->name('RequisitionTardiveDeces.etat');
    Route::put('{id}/generate/create', [RequisitionTardiveController::class, 'generateRequisition'])->name('RequisitionTardiveDeces.generateRequisition');
});

Route::middleware('auth')->prefix('RequisitionNonInscriptionDeces')->group(function () {
    Route::get('/', [RequisitionNonInscriptionDeceController::class, 'index'])->name('RequisitionNonInscriptionDeces.index');
    Route::get('{id}/requisition/generate', [RequisitionNonInscriptionDeceController::class, 'etat'])->name('RequisitionNonInscriptionDeces.etat');
    Route::put('{id}/generate/create', [RequisitionNonInscriptionDeceController::class, 'generateRequisition'])->name('RequisitionNonInscriptionDeces.generateRequisition');
    Route::post('requisition/generateacte', [RequisitionNonInscriptionDeceController::class, 'generateActe'])->name('RequisitionNonInscriptionDeces.generateacte');
});

Route::middleware('auth')->prefix('RequisitionTranscriptionDeces')->group(function () {
    Route::get('/', [RequisitionTranscriptionDecesController::class, 'index'])->name('RequisitionTranscriptionDeces.index');
    Route::get('{id}/requisition/generate', [RequisitionTranscriptionDecesController::class, 'etat'])->name('RequisitionTranscriptionDeces.etat');
    Route::put('{id}/generate/create', [RequisitionTranscriptionDecesController::class, 'generateRequisition'])->name('RequisitionTranscriptionDeces.generateRequisition');
    Route::post('requisition/generateacte', [RequisitionTranscriptionDecesController::class, 'generateActe'])->name('RequisitionTranscriptionDeces.generateacte');
});

Route::middleware('auth')->prefix('statistiquesDeces')->group(function () {
    Route::get('declarationsdeces/cause', [DecesController::class, 'statParCause'])->name('statistiquesDeces.causeDeclaration');
    Route::get('declarationsdeces/cause/etat', [DecesController::class, 'statParCauseEtat'])->name('statistiquesDeces.causeDeclarationEtat');
    Route::get('actedeces/liste', [ActeDecesController::class, 'listedece'])->name('statistiquesDeces.listedece');
    Route::get('actedeces/etat', [ActeDecesController::class, 'etatlistedeces'])->name('statistiquesDeces.etatlistedeces');

    Route::get('statistique/decesparage', [DecesController::class, 'statParTrancheAge'])->name('statistiquesDeces.age');
    Route::get('statistique/decesparageEt', [DecesController::class, 'statParTrancheAgeEtat'])->name('statistiquesDeces.decesparageEt');

});
