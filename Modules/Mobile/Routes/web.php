<?php

use Illuminate\Support\Facades\Route;
use Modules\Mobile\Http\Controllers\DemandeDocumentController;
use Modules\Naissance\Http\Controllers\ActeNaissanceController;

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

Route::prefix('demande-document')->group(function() {
    Route::post('store', [DemandeDocumentController::class,'store'])->name('demande.document');
    Route::post('paiement', [DemandeDocumentController::class,'paiementDocument'])->name('demande.paiement.document');
    Route::get('{id}/acte/copie',[ActeNaissanceController::class,"displayCopie"])->name('demande.acteNaissance.display');
    Route::get("{id}/generate/duplicata", [ActeNaissanceController::class,'displayDuplicata'])->name('demande.ActeNaissance.generate.duplicata');
});

