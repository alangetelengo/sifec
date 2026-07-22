<?php

use App\Http\Controllers\CartesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PersonneSearchController;
use App\Http\Controllers\QrcodeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Authentification\Http\Controllers\AuthentificationController;
use Modules\Naissance\Http\Controllers\NaissanceController;
use Modules\Notification\Http\Controllers\NotificationController;

Route::get('/', [AuthentificationController::class, 'index'])->name('dashboard.index')->middleware(['auth']);
// Route::get('/', [AuthentificationController::class,'home'])->name('home.index')->middleware(['auth']);

Route::put('{id}/update', [AuthentificationController::class, 'update'])->name('dashboard.update')->middleware(['auth']);
Route::post('store', [AuthentificationController::class, 'authentification'])->name('dashboard.login');
Route::middleware('auth')->get('carteducongo', [AuthentificationController::class, 'carte'])->name('dashboard.carteducongo');
Route::middleware('auth')->get('statgenredep', [AuthentificationController::class, 'statGenreDep'])->name('dashboard.statgenredep');

Route::prefix('qrcode')->group(function () {
    Route::get('/', [QrcodeController::class, 'index'])->name('qrcode.index');
    Route::get('naissance/certificat', [QrcodeController::class, 'certificatNaissance'])->name('qrcode.naissanceCertificat');
    Route::get('naissance/requisition', [QrcodeController::class, 'requisitionNaissance'])->name('qrcode.naissanceRequisition');
    Route::get('naissance/duplicata', [QrcodeController::class, 'duplicata'])->name('qrcode.duplicata');
    Route::get('deces', [QrcodeController::class, 'deces'])->name('qrcode.deces');
    Route::get('deces/certificat', [QrcodeController::class, 'certificatDeces'])->name('qrcode.decesCertificat');
    Route::get('deces/requisition', [QrcodeController::class, 'requisitionDeces'])->name('qrcode.decesRequisition');
});

Route::middleware('auth')->prefix('cartes')->group(function () {
    // Route::get('/', [CartesController::class,'index'])->name('cartes.index');
    Route::get('cumuleNationale', [CartesController::class, 'cumuleNationale'])->name('cartes.cumule.nationale');
    Route::get('cumuleNationaleYear', [CartesController::class, 'cumuleNationaleYear'])->name('cartes.cumule.nationale.year');
    Route::get('cumuleNationaleMonth', [CartesController::class, 'cumuleNationaleMonth'])->name('cartes.cumule.nationale.month');
    Route::get('cumuleNationaleWeek', [CartesController::class, 'cumuleNationaleWeek'])->name('cartes.cumule.nationale.week');
    Route::get('cumuleNationaleDate', [CartesController::class, 'cumuleNationaleDate'])->name('cartes.cumule.nationale.date');

    Route::post('departementGet', [CartesController::class, 'departementGet'])->name('carte.departement.get');
    Route::post('cumuleDepartement', [CartesController::class, 'cumuleDepartement'])->name('cartes.cumule.departement');
    Route::post('cumuleDepartementYear', [CartesController::class, 'cumuleDepartementYear'])->name('cartes.cumule.departement.year');
    Route::post('cumuleDepartementMonth', [CartesController::class, 'cumuleDepartementMonth'])->name('cartes.cumule.departement.month');
    Route::post('cumuleDepartementWeek', [CartesController::class, 'cumuleDepartementWeek'])->name('cartes.cumule.departement.week');
    Route::post('cumuleDepartementDate', [CartesController::class, 'cumuleDepartementDate'])->name('cartes.cumule.departement.date');

    Route::get('periode/synthese-nationale', [CartesController::class, 'syntheseNationalePeriode'])->name('cartes.periode.synthese.nationale');
    Route::post('periode/synthese-departement', [CartesController::class, 'syntheseDepartementPeriode'])->name('cartes.periode.synthese.departement');
    Route::get('periode/serie-journaliere', [CartesController::class, 'serieNationaleJournaliere'])->name('cartes.periode.serie.journaliere');
    Route::get('periode/transcriptions', [CartesController::class, 'transcriptionsHorsTerritoire'])->name('cartes.periode.transcriptions');
    Route::get('periode/export-pdf', [CartesController::class, 'exportCartePdf'])->name('cartes.periode.export.pdf');
});

Route::middleware('auth')->prefix('tableau')->group(function () {
    Route::get('/', [HomeController::class, 'tableaudebord'])->name('tableau.index');
    Route::get('impression', [HomeController::class, 'impressiontableau'])->name('tableau.impression');
    Route::get('impression/prefet', [HomeController::class, 'impressiontableauprefet'])->name('tableau.impressionprefet');
    Route::get('{id}/impression/details', [HomeController::class, 'impressiondetails'])->name('tableau.details');
    // Route::get('cumuleNationale', [HomeController::class,'cumuleNationale'])->name('cartes.cumule.nationale');
});

Route::middleware('auth')->get('/personnes/recherche', [PersonneSearchController::class, 'recherche'])->name('personnes.recherche');

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [NotificationController::class, 'read'])->name('notifications.read');

    // Notifications AJAX
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/unread-list', [NotificationController::class, 'unreadList'])->name('notifications.unreadList');
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

Auth::routes();

// ==========================================
// Routes 2FA (Double Authentification)
// ==========================================

use App\Http\Controllers\Admin\DemandeDocumentConfigController;
use App\Http\Controllers\Admin\SignelecController;
use App\Http\Controllers\Admin\TarificationController;
use App\Http\Controllers\DemandeDocumentController;
use App\Http\Controllers\TwoFactorController;
use Modules\Deces\Http\Controllers\DecesController;
use Modules\Mariage\Http\Controllers\MariageController;
use Modules\Referentiel\Http\Controllers\RegistreController;

// Routes de configuration 2FA (nécessite authentification)
Route::middleware(['auth'])->group(function () {
    Route::prefix('two-factor')->name('two-factor.')->group(function () {
        // Page principale de configuration
        Route::get('/', [TwoFactorController::class, 'index'])->name('index');

        // Activation
        Route::get('/enable', [TwoFactorController::class, 'enable'])->name('enable');
        Route::get('/csrf-token', [TwoFactorController::class, 'getCsrfToken'])->name('csrf-token');
        Route::post('/confirm', [TwoFactorController::class, 'confirm'])->name('confirm');

        // Codes de récupération
        Route::get('/recovery-codes', [TwoFactorController::class, 'showRecoveryCodes'])->name('recovery-codes');
        Route::get('/recovery-codes/download', [TwoFactorController::class, 'downloadRecoveryCodesPdf'])->name('recovery-codes.download');
        Route::get('/recovery-codes/print', [TwoFactorController::class, 'printRecoveryCodesPdf'])->name('recovery-codes.print');
        Route::post('/recovery-codes/regenerate', [TwoFactorController::class, 'regenerateRecoveryCodes'])->name('recovery-codes.regenerate');

        // Désactivation
        Route::post('/disable', [TwoFactorController::class, 'disable'])->name('disable');
    });
});

// Routes de vérification 2FA (lors de la connexion, sans auth complète)
Route::middleware(['web'])->group(function () {
    Route::get('/two-factor/verify', [TwoFactorController::class, 'showVerify'])->name('two-factor.verify');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify.post');
    Route::post('/two-factor/verify-recovery', [TwoFactorController::class, 'verifyRecoveryCode'])->name('two-factor.verify-recovery');
});

// Première connexion : changement obligatoire du mot de passe provisoire (123456)
Route::middleware(['auth'])->group(function () {
    Route::get('/premiere-connexion/mot-de-passe', [AuthentificationController::class, 'showFirstLoginPassword'])
        ->name('first-login-password.show');
    Route::post('/premiere-connexion/mot-de-passe', [AuthentificationController::class, 'updateFirstLoginPassword'])
        ->name('first-login-password.update');
});

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/acte/{niupp}', [NaissanceController::class, 'verificationActe'])
    ->name('verification.acte');

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/registre/{code}', [RegistreController::class, 'verificationRegistre'])
    ->name('verification.registre');

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/acte/deces/{code}', [DecesController::class, 'verificationActe'])
    ->name('verification.acte.deces');

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/certificat/deces/{code}', [DecesController::class, 'verificationCertificatDeces'])
    ->name('verification.certificat.deces');

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/constatation/deces/{code}', [DecesController::class, 'verificationConstatationDeces'])
    ->name('verification.constatation.deces');

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/declaration/deces/{code}', [DecesController::class, 'verificationDeclarationDeces'])
    ->name('verification.declaration.deces');

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/acte/mariage/{code}', [MariageController::class, 'verificationActe'])
    ->name('verification.acte.mariage');

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/declaration/{code}', [NaissanceController::class, 'verificationDeclaration'])
    ->name('verification.declaration');

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/certificat-naissance/{code}', [NaissanceController::class, 'verificationCertificatNaissance'])
    ->name('verification.certificat.naissance');

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/declaration/mariage/{code}', [MariageController::class, 'verificationDeclaration'])
    ->name('verification.declaration.mariage');

Route::middleware(['signed', 'throttle:10,1'])
    ->get('/verification/demande-document/{code}', [DemandeDocumentController::class, 'verification'])
    ->name('verification.demande.document');

/*
|--------------------------------------------------------------------------
| Routes de test PHPUnit (flash + redirection) — uniquement pendant les tests
|--------------------------------------------------------------------------
*/
if (app()->runningUnitTests()) {
    Route::middleware('web')->get('/__sifec_flash_src', static function () {
        return redirect('/__sifec_flash_tgt')->with('success', 'Flash ok');
    });
    Route::middleware('web')->get('/__sifec_flash_tgt', static function () {
        return response(
            '<html><body>'.e((string) session('success', 'missing')).'</body></html>',
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    });
}

// Routes pour la gestion des demandes de documents (copies/extraits)
Route::middleware(['auth'])->prefix('demande-document')->name('demandeDocument.')->group(function () {
    Route::get('/', [DemandeDocumentController::class, 'index'])->name('index');
    Route::get('/create', [DemandeDocumentController::class, 'create'])->name('create');
    Route::post('/', [DemandeDocumentController::class, 'store'])->name('store');
    Route::get('/{code}', [DemandeDocumentController::class, 'show'])->name('show');

    // Workflow
    Route::post('/{code}/generer-pdf', [DemandeDocumentController::class, 'passerEnAttenteSignature'])->name('genererPdf');

    // Signature électronique .p12 (vérification dynamique des permissions dans le contrôleur)
    Route::post('/signature/prepare', [DemandeDocumentController::class, 'prepareSignature'])->name('sign.prepare');
    Route::post('/signature/finalize', [DemandeDocumentController::class, 'finalizeSignature'])->name('sign.finalize');
    Route::post('/signature/initier', [DemandeDocumentController::class, 'initierSignature'])->name('initierSignature'); // deprecated OTP
    Route::post('/signature/valider', [DemandeDocumentController::class, 'validerSignature'])->name('validerSignature'); // deprecated OTP

    Route::post('/{code}/rejeter', [DemandeDocumentController::class, 'rejeter'])->name('rejeter');
    Route::post('/{code}/livree', [DemandeDocumentController::class, 'marquerLivree'])->name('livree');
    Route::post('/{code}/renouveler', [DemandeDocumentController::class, 'preparerRenouvellement'])->name('renouveler');

    // PDF
    Route::get('/{code}/pdf', [DemandeDocumentController::class, 'telechargerPdf'])->name('pdf');

    // AJAX
    Route::post('/rechercher-acte', [DemandeDocumentController::class, 'rechercherActe'])->name('rechercherActe');
});

// Admin — validité des documents demande (copies / extraits)
Route::middleware(['auth', 'can:module.admin.demande_document.parametres'])->prefix('admin/demande-document-config')->name('admin.demande-document-config.')->group(function () {
    Route::get('/', [DemandeDocumentConfigController::class, 'edit'])->name('edit');
    Route::put('/', [DemandeDocumentConfigController::class, 'update'])->middleware('can:module.admin.demande_document.parametres.modifier')->name('update');
});

// Routes Admin - Gestion des tarifs (Réservé aux administrateurs)
Route::middleware(['auth', 'can:module.admin.tarifs'])->prefix('admin/tarifs')->name('admin.tarifs.')->group(function () {
    Route::get('/', [TarificationController::class, 'index'])->name('index');
    Route::get('/create', [TarificationController::class, 'create'])->name('create')->middleware('can:module.admin.tarifs.modifier');
    Route::post('/', [TarificationController::class, 'store'])->name('store')->middleware('can:module.admin.tarifs.modifier');
    Route::get('/{code}/edit', [TarificationController::class, 'edit'])->name('edit')->middleware('can:module.admin.tarifs.modifier');
    Route::put('/{code}', [TarificationController::class, 'update'])->name('update')->middleware('can:module.admin.tarifs.modifier');
    Route::post('/{code}/toggle', [TarificationController::class, 'toggleActif'])->name('toggle')->middleware('can:module.admin.tarifs.modifier');
    Route::delete('/{code}', [TarificationController::class, 'destroy'])->name('destroy')->middleware('can:module.admin.tarifs.modifier');
});

// Admin — SIGNELEC (signature électronique GUOT)
Route::middleware(['auth', 'can:module.admin.signelec'])->prefix('admin/signelec')->name('admin.signelec.')->group(function () {
    Route::get('/', [SignelecController::class, 'dashboard'])->name('dashboard');
    Route::get('/institutions', [SignelecController::class, 'institutions'])->name('institutions');
    Route::get('/signataires', [SignelecController::class, 'signataires'])->name('signataires');
    Route::get('/parametres', [SignelecController::class, 'parametres'])->name('parametres');
    Route::put('/parametres', [SignelecController::class, 'updateParametres'])->name('parametres.update');
});
