<?php

use Illuminate\Support\Facades\Route;
use Modules\Reporting\Http\Controllers\ReportingController;

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

Route::middleware('auth')->prefix('reporting')->group(function() {
    Route::post("store/copie", [ReportingController::class,'genererCopie'])->name("reporting.store.copie");
    Route::get("dashbord/recette", [ReportingController::class,'dashbordRecette'])->name("reporting.recette");
    Route::get("rapport-periodique", [ReportingController::class, 'rapportPeriodique'])->name("reporting.periodique");
    Route::post("rapport-periodique", [ReportingController::class, 'rapportPeriodique'])->name("reporting.periodique.search");
    Route::post("rapport-periodique/pdf", [ReportingController::class, 'rapportPeriodiquePdf'])->name("reporting.periodique.pdf");

    // Tableau statistique national des actes par département (accès authentifié)
    Route::get("statistique/tableau-national-departements", [ReportingController::class, 'tableauNationalDepartements'])->name("reporting.statistique.tableau.national.departements");
    Route::post("statistique/tableau-national-departements/display", [ReportingController::class, 'displayTableauNationalDepartements'])->name("reporting.statistique.tableau.national.departements.display");
    Route::get("statistique/tableau-national-departements/pdf", [ReportingController::class, 'tableauNationalDepartementsPdf'])->name("reporting.statistique.tableau.national.departements.pdf");

    // Rapport d'exploitation des actes de naissance (ancienne version - conservée pour compatibilité)
    Route::middleware('can:module.naissance.rapport.exploitation')->group(function() {
        Route::get("naissance/rapport-exploitation", [ReportingController::class, 'rapportExploitationNaissance'])->name("reporting.naissance.rapport.exploitation");
        Route::get("naissance/rapport-exploitation/pdf", [ReportingController::class, 'rapportExploitationNaissancePdf'])->name("reporting.naissance.rapport.exploitation.pdf");
    });

    // Rapport d'exploitation générique des faits d'état civil (nouvelle version)
    Route::middleware('can:module.naissance.rapport.exploitation')->group(function() {
        Route::get("faits/rapport-exploitation", [ReportingController::class, 'rapportExploitationFaits'])->name("reporting.faits.rapport.exploitation");
        Route::post("faits/rapport-exploitation/display", [ReportingController::class, 'displayRapportExploitationFaits'])->name("reporting.faits.rapport.exploitation.display");
        Route::get("faits/rapport-exploitation/pdf", [ReportingController::class, 'rapportExploitationFaitsPdf'])->name("reporting.faits.rapport.exploitation.pdf");
    });
    
    // Annuaire statistique des faits d'état civil (Naissance / Mariage / Décès)
    Route::middleware('can:module.naissance.rapport.exploitation')->group(function() {
        Route::get("faits/annuaire-statistique", [ReportingController::class, 'annuaireStatistiqueFaits'])->name("reporting.faits.annuaire.statistique");
        Route::post("faits/annuaire-statistique/display", [ReportingController::class, 'displayAnnuaireStatistiqueFaits'])->name("reporting.faits.annuaire.statistique.display");
        Route::get("faits/annuaire-statistique/pdf", [ReportingController::class, 'annuaireStatistiqueFaitsPdf'])->name("reporting.faits.annuaire.statistique.pdf");

        // Compatibilité URL naissance
        Route::get("naissance/annuaire-statistique", [ReportingController::class, 'annuaireStatistiqueNaissance'])->name("reporting.naissance.annuaire.statistique");
        Route::post("naissance/annuaire-statistique/display", [ReportingController::class, 'displayAnnuaireStatistiqueNaissance'])->name("reporting.naissance.annuaire.statistique.display");
        Route::get("naissance/annuaire-statistique/pdf", [ReportingController::class, 'annuaireStatistiqueNaissancePdf'])->name("reporting.naissance.annuaire.statistique.pdf");
    });

    // Répertoire alphabétique des faits d'état civil (Naissance / Mariage / Décès)
    Route::middleware('can:module.naissance.rapport.exploitation')->group(function () {
        Route::get('faits/repertoire-alphabetique', [ReportingController::class, 'repertoireAlphabetiqueFaits'])->name('reporting.faits.repertoire.alphabetique');
        Route::post('faits/repertoire-alphabetique/display', [ReportingController::class, 'displayRepertoireAlphabetiqueFaits'])->name('reporting.faits.repertoire.alphabetique.display');
        Route::get('faits/repertoire-alphabetique/pdf', [ReportingController::class, 'repertoireAlphabetiqueFaitsPdf'])->name('reporting.faits.repertoire.alphabetique.pdf');
    });

    // API pour les filtres géographiques
    Route::get("localites/enfants/{codeLocalite}", [ReportingController::class, 'getLocalitesEnfants'])->name("reporting.localites.enfants");
});
