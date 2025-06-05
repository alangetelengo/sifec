<?php

use Illuminate\Support\Facades\Route;
use Modules\Rectification\Http\Controllers\RectificationController;

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

Route::middleware('auth')->prefix('rectification')->group(function() {
    Route::get("/", [RectificationController::class,'index'])->name("rectification.index");
    Route::get("create", [RectificationController::class,'create'])->name("rectification.create");
    Route::post("getActe", [RectificationController::class,'getActe'])->name("rectification.get.acte");
    Route::post("old-value", [RectificationController::class,'oldValue'])->name("rectification.recup-old-value");
    Route::post("store", [RectificationController::class,'store'])->name("rectification.store");
    Route::delete("delete/{id}", [RectificationController::class,'destroy'])->name("rectification.destroy");
    Route::get("show/{id}", [RectificationController::class,'show'])->name("rectification.show");
    Route::get("edit/{id}", [RectificationController::class,'edit'])->name("rectification.edit");
    Route::put("update/{id}", [RectificationController::class,'update'])->name("rectification.update");
    Route::get("etat/{id}", [RectificationController::class,'ficheRectification'])->name("rectification.etat");
    Route::post("get-details", [RectificationController::class,'getDetails'])->name("rectification.get.details");
    Route::get("send/{id}", [RectificationController::class,'send'])->name("rectification.send");

});
