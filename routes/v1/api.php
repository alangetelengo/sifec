<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PayementController;
use App\Http\Controllers\Api\SignatureController;
use App\Http\Controllers\Api\DocumentEtatCivilController;
use App\Http\Controllers\Api\AuthentificationActeController;
use App\Http\Controllers\Api\BanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("login",[UserController::class,"login"]);
Route::post("upload-signatures",[SignatureController::class,"upload"]);

Route::post('verificationActe', [DocumentEtatCivilController::class,'verificationActe']);

Route::post("authentification", [AuthentificationActeController::class, "authentification"]);

//route API demande des (copies extrait et duplicata) d'actes
Route::post("demandeActe", [AuthentificationActeController::class, "demandeActe"]);

Route::get('copie/actenaissance/{id}',[AuthentificationActeController::class,"displayCopie"])->name('copieActeNaissance');
Route::get('extrait/actenaissance/{id}',[AuthentificationActeController::class,"displayExtraitActe"])->name('acteNaissance.displayExtraitActe');
Route::get("duplicata/actenaissance/{id}", [AuthentificationActeController::class,'displayDuplicata'])->name('duplicataActeNaissance');

Route::get('copie/actenaissance/portail/{id}',[AuthentificationActeController::class,"displayCopiePortail"])->name('copieActeNaissancePortail');
Route::get('extrait/actenaissance/portail/{id}',[AuthentificationActeController::class,"displayExtraitActePortail"])->name('acteNaissance.displayExtraitActePortail');


Route::get('copie/actedeces/{id}',[AuthentificationActeController::class,"displayCopieDeces"])->name('copieActeDeces');
Route::get('extrait/actedeces/{id}',[AuthentificationActeController::class,"displayExtraitActeDeces"])->name('acteDeces.displayExtrait');
Route::get("duplicata/actedeces/{id}", [AuthentificationActeController::class,'displayDuplicataDeces'])->name('duplicataActeDeces');

Route::get('etatactenaissance/{id}',[AuthentificationActeController::class,"displayActe"])->name('acteNaissance.displayEtat');
Route::get('etatactedeces/{id}',[AuthentificationActeController::class,"displayActeDeces"])->name('acteDeces.displayEtat');
Route::get('etatactemariage/{id}',[AuthentificationActeController::class,"displayActeMariage"])->name('acteMariage.displayEtat');

//route d'accès à la liste des centre d'état civil depuis le portail
Route::get('listeCec',[AuthentificationActeController::class,"listeCec"])->name('listeCec');

//route affichage bon de mariage
Route::get('banMariage', [BanController::class, 'journalMariagesSansActe'])
        ->name('banMariage');

//statut paiement MOMO
Route::post('statutPaiementMomo',[PayementController::class,"statutPaiementMomo"])->name('statutPaiementMomo');


//route affichage de l'état de recocuvrement des Recettes relatives aux authentifications des actes par les administrations
Route::get('etatRecouvrement',[AuthentificationActeController::class,"etatRecouvrement"])->name('etatRecouvement');

//route affichage de l'état historique des authentifications par administration
Route::post('historiqueAuthentification',[AuthentificationActeController::class,"historiqueAuthentification"])->name('historiqueAuthentification');
Route::get('etatHistorique/{id}',[AuthentificationActeController::class,"etatHistorique"])->name('etatHistorique');

//route affichage de l'état des recettes relatives aux demandes d'acte d'acte en ligne
Route::get('etatRecetteDemandeEnLigne',[AuthentificationActeController::class,"etatRecetteDemandeEnLigne"])->name('etatRecetteDemandeEnLigne');


//statut paiement rdc
Route::post('rdcPaiement',[PayementController::class,"rdcpaiement"])->name('rdcpaiement');

//route d'accès au controlleur du paiement
Route::get('paiement',[PayementController::class,"paiement"])->name('paiement');

//route de redirection en cas de confirmation de paiement paypal
Route::get('successPaypal',[PayementController::class,"successPaypal"])->name('successPaypal');

//route de redirection en cas d'annulation de paiement paypal
Route::get('cancelPaypal',[PayementController::class,"cancelPaypal"])->name('cancelPaypal');
//tst

//route d'impression du reçu de paiement de l'authentification des actes
Route::get('etatRecuNaissance/{id}',[AuthentificationActeController::class,"etatRecuNaissance"])->name('etatRecuNaissance');
Route::get('etatRecuMariage/{id}',[AuthentificationActeController::class,"etatRecuMariage"])->name('etatRecuMariage');
Route::get('etatRecuDeces/{id}',[AuthentificationActeController::class,"etatRecuDeces"])->name('etatRecuDeces');
Route::get('etatRecuDecesNA/{id}',[AuthentificationActeController::class,"etatRecuDecesNA"])->name('etatRecuDecesNA');
Route::get('etatRecuNaissanceNA/{id}',[AuthentificationActeController::class,"etatRecuNaissanceNA"])->name('etatRecuNaissanceNA');
Route::get('etatRecuMariageNA/{id}',[AuthentificationActeController::class,"etatRecuMariageNA"])->name('etatRecuMariageNA');

