<?php

/**
 * API versionnée : préfixe /api/v1 (bootstrap/app.php).
 *
 * Classification, risques et alignement can:/portail : docs/api-v1-routes-et-autorisations.md
 */

use App\Http\Controllers\Api\AuthentificationActeController;
use App\Http\Controllers\Api\BanController;
use App\Http\Controllers\Api\DemandeDocumentApiController;
use App\Http\Controllers\Api\DocumentEtatCivilController;
use App\Http\Controllers\Api\PayementController;
use App\Http\Controllers\Api\SignatureController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// --- Technique : authentification API (Passport) ---
Route::post('login', [UserController::class, 'login']);

// Signatures acte mariage : Bearer + scope signatures-mariage (tablettes : login API puis envoi du token)
Route::middleware(['auth:api', 'scope:signatures-mariage'])->post('upload-signatures', [SignatureController::class, 'upload']);

// --- Public portail ---
Route::post('verificationActe', [DocumentEtatCivilController::class, 'verificationActe']);

Route::post('authentification', [AuthentificationActeController::class, 'authentification']);

// Demande (copies, extrait, duplicata) d'actes
Route::post('demandeActe', [AuthentificationActeController::class, 'demandeActe']);

Route::get('copie/actenaissance/{id}', [AuthentificationActeController::class, 'displayCopie'])->name('copieActeNaissance');
Route::get('extrait/actenaissance/{id}', [AuthentificationActeController::class, 'displayExtraitActe'])->name('acteNaissance.displayExtraitActe');
Route::get('duplicata/actenaissance/{id}', [AuthentificationActeController::class, 'displayDuplicata'])->name('duplicataActeNaissance');

Route::get('copie/actenaissance/portail/{id}', [AuthentificationActeController::class, 'displayCopiePortail'])->name('copieActeNaissancePortail');
Route::get('extrait/actenaissance/portail/{id}', [AuthentificationActeController::class, 'displayExtraitActePortail'])->name('acteNaissance.displayExtraitActePortail');

Route::get('copie/actedeces/{id}', [AuthentificationActeController::class, 'displayCopieDeces'])->name('copieActeDeces');
Route::get('extrait/actedeces/{id}', [AuthentificationActeController::class, 'displayExtraitActeDeces'])->name('acteDeces.displayExtrait');
Route::get('duplicata/actedeces/{id}', [AuthentificationActeController::class, 'displayDuplicataDeces'])->name('duplicataActeDeces');

Route::get('etatactenaissance/{id}', [AuthentificationActeController::class, 'displayActe'])->name('acteNaissance.displayEtat');
Route::get('etatactedeces/{id}', [AuthentificationActeController::class, 'displayActeDeces'])->name('acteDeces.displayEtat');
Route::get('etatactemariage/{id}', [AuthentificationActeController::class, 'displayActeMariage'])->name('acteMariage.displayEtat');

// Liste des CEC (portail)
Route::get('listeCec', [AuthentificationActeController::class, 'listeCec'])->name('listeCec');

// BAN / journal : Bearer + scope mariage-ban (+ droit métier web si l’utilisateur est un User complet)
Route::middleware(['auth:api', 'scope:mariage-ban', 'can:module.acteMariage'])->get('banMariage', [BanController::class, 'journalMariagesSansActe'])->name('banMariage');

// statut paiement MOMO
Route::post('statutPaiementMomo', [PayementController::class, 'statutPaiementMomo'])->name('statutPaiementMomo');

// Retirées : méthodes inexistantes sur AuthentificationActeController (erreur 500).
// Réimplémenter ou exposer via contrôleur web authentifié (ex. PaiementDocumentController@etatRecouvrement).
// Route::get('etatRecouvrement', ...);
// Route::post('historiqueAuthentification', ...);
// Route::get('etatHistorique/{id}', ...);
// Route::get('etatRecetteDemandeEnLigne', ...);

// statut paiement rdc
Route::post('rdcPaiement', [PayementController::class, 'rdcpaiement'])->name('rdcpaiement');

// Consultation statut demande
Route::get('demande/{code}/statut', [DemandeDocumentApiController::class, 'consulterStatut'])->name('demande.statut');

// route d'accès au controlleur du paiement
Route::get('paiement', [PayementController::class, 'paiement'])->name('paiement');

// route de redirection en cas de confirmation de paiement paypal
Route::get('successPaypal', [PayementController::class, 'successPaypal'])->name('successPaypal');

// route de redirection en cas d'annulation de paiement paypal
Route::get('cancelPaypal', [PayementController::class, 'cancelPaypal'])->name('cancelPaypal');
// tst

// route d'impression du reçu de paiement de l'authentification des actes
Route::get('etatRecuNaissance/{id}', [AuthentificationActeController::class, 'etatRecuNaissance'])->name('etatRecuNaissance');
Route::get('etatRecuMariage/{id}', [AuthentificationActeController::class, 'etatRecuMariage'])->name('etatRecuMariage');
Route::get('etatRecuDeces/{id}', [AuthentificationActeController::class, 'etatRecuDeces'])->name('etatRecuDeces');
Route::get('etatRecuDecesNA/{id}', [AuthentificationActeController::class, 'etatRecuDecesNA'])->name('etatRecuDecesNA');
Route::get('etatRecuNaissanceNA/{id}', [AuthentificationActeController::class, 'etatRecuNaissanceNA'])->name('etatRecuNaissanceNA');
Route::get('etatRecuMariageNA/{id}', [AuthentificationActeController::class, 'etatRecuMariageNA'])->name('etatRecuMariageNA');
