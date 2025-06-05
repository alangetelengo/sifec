<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Mail\ValidationRegistreMailable;
use App\Http\Controllers\CartesController;
use App\Http\Controllers\DashbordGouvController;
use App\Http\Controllers\PaiementDocumentController;
use App\Http\Controllers\QrcodeController;
use Illuminate\Support\Facades\Artisan;
use Modules\Notification\Jobs\CreationRegistreJob;
use Modules\Notification\Jobs\ValidationRegistreJob;
use Modules\Notification\Jobs\ValidationacteNaissanceJob;
use Modules\Authentification\Http\Controllers\AuthentificationController;

Route::get('/', [AuthentificationController::class,'index'])->name('dashboard.index')->middleware(['auth']);
// Route::get('/', [AuthentificationController::class,'home'])->name('home.index')->middleware(['auth']);

Route::get("testmail",function(){
    // Mail::to("mukinayiseth@gmail.com")
    // ->send(new ValidationRegistreMailable("BRAZZAVILLE","015247","AN01451"));
    // dispatch(new CreationRegistreJob("TRIB","TPE5425","AN21452","OUENZE","alangetelengo87@gmail.com"));
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

Auth::routes();




