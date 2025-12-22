<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Mail\ValidationRegistreMailable;
use App\Http\Controllers\CartesController;
use App\Http\Controllers\QrcodeController;
use App\Http\Controllers\DashbordGouvController;
use App\Http\Controllers\PersonneSearchController;
use Modules\Notification\Jobs\CreationRegistreJob;
use App\Http\Controllers\PaiementDocumentController;
use Modules\Notification\Jobs\ValidationRegistreJob;
use Modules\Notification\Jobs\ValidationacteNaissanceJob;
use Modules\Notification\Http\Controllers\NotificationController;
use Modules\Authentification\Http\Controllers\AuthentificationController;
use Modules\Naissance\Http\Controllers\NaissanceController;

Route::get('/', [AuthentificationController::class,'index'])->name('dashboard.index')->middleware(['auth']);
// Route::get('/', [AuthentificationController::class,'home'])->name('home.index')->middleware(['auth']);

Route::get("testmail",function(){
    dispatch(new ValidationacteNaissanceJob("MAKELEKELE","AN0001","14F52","alangetelengo87@gmail.com"));
    return "le traitement sera fait";
});

Route::put('{id}/update', [AuthentificationController::class,'update'])->name('dashboard.update')->middleware(['auth']);
Route::post('store', [AuthentificationController::class,'authentification'])->name("dashboard.login");
Route::get('carteducongo', [AuthentificationController::class,'carte'])->name('dashboard.carteducongo');
Route::get('statgenredep', [AuthentificationController::class,'statGenreDep'])->name('dashboard.statgenredep');


Route::prefix("qrcode")->group(function() {
    Route::get("/", [QrcodeController::class,'index'])->name('qrcode.index');
    Route::get("naissance/certificat", [QrcodeController::class,'certificatNaissance'])->name('qrcode.naissanceCertificat');
    Route::get("naissance/requisition", [QrcodeController::class,'requisitionNaissance'])->name('qrcode.naissanceRequisition');
    Route::get("naissance/duplicata", [QrcodeController::class,'duplicata'])->name('qrcode.duplicata');
    Route::get("deces", [QrcodeController::class,'deces'])->name('qrcode.deces');
    Route::get("deces/certificat", [QrcodeController::class,'certificatDeces'])->name('qrcode.decesCertificat');
    Route::get("deces/requisition", [QrcodeController::class,'requisitionDeces'])->name('qrcode.decesRequisition');
});

Route::middleware('auth')->prefix('cartes')->group(function() {
    // Route::get('/', [CartesController::class,'index'])->name('cartes.index');
    Route::get('cumuleNationale', [CartesController::class,'cumuleNationale'])->name('cartes.cumule.nationale');
    Route::get('cumuleNationaleYear', [CartesController::class,'cumuleNationaleYear'])->name('cartes.cumule.nationale.year');
    Route::get('cumuleNationaleMonth', [CartesController::class,'cumuleNationaleMonth'])->name('cartes.cumule.nationale.month');
    Route::get('cumuleNationaleWeek', [CartesController::class,'cumuleNationaleWeek'])->name('cartes.cumule.nationale.week');
    Route::get('cumuleNationaleDate', [CartesController::class,'cumuleNationaleDate'])->name('cartes.cumule.nationale.date');

    Route::post('departementGet',[CartesController::class,'departementGet'])->name("carte.departement.get");
    Route::post('cumuleDepartement', [CartesController::class,'cumuleDepartement'])->name('cartes.cumule.departement');
    Route::post('cumuleDepartementYear', [CartesController::class,'cumuleDepartementYear'])->name('cartes.cumule.departement.year');
    Route::post('cumuleDepartementMonth', [CartesController::class,'cumuleDepartementMonth'])->name('cartes.cumule.departement.month');
    Route::post('cumuleDepartementWeek', [CartesController::class,'cumuleDepartementWeek'])->name('cartes.cumule.departement.week');
    Route::post('cumuleDepartementDate', [CartesController::class,'cumuleDepartementDate'])->name('cartes.cumule.departement.date');
});

Route::middleware('auth')->prefix('tableau')->group(function() {
    Route::get('/', [HomeController::class,'tableaudebord'])->name('tableau.index');
    Route::get('impression', [HomeController::class,'impressiontableau'])->name('tableau.impression');
    Route::get('impression/prefet', [HomeController::class,'impressiontableauprefet'])->name('tableau.impressionprefet');
    Route::get('{id}/impression/details', [HomeController::class,'impressiondetails'])->name('tableau.details');
    // Route::get('cumuleNationale', [HomeController::class,'cumuleNationale'])->name('cartes.cumule.nationale');
});

Route::get('/personnes/recherche', [PersonneSearchController::class, 'recherche'])->name('personnes.recherche');



Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/read/{id}', [NotificationController::class, 'read'])->name('notifications.read');

// Notifications AJAX
Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
Route::get('/notifications/unread-list', [NotificationController::class, 'unreadList'])->name('notifications.unreadList');
Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');


Auth::routes();

// ==========================================
// Routes 2FA (Double Authentification)
// ==========================================

use App\Http\Controllers\TwoFactorController;

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

Route::middleware(['signed','throttle:10,1'])
    ->get('/verification/acte/{niupp}', [NaissanceController::class, 'verificationActe'])
    ->name('verification.acte');

Route::middleware(['signed','throttle:10,1'])
    ->get('/verification/acte/deces/{code}', [\Modules\Deces\Http\Controllers\DecesController::class, 'verificationActe'])
    ->name('verification.acte.deces');

Route::middleware(['signed','throttle:10,1'])
    ->get('/verification/declaration/{code}', [NaissanceController::class, 'verificationDeclaration'])
    ->name('verification.declaration');



