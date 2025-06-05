<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Deces\Http\Controllers\ActeDecesController;

Route::middleware('auth:api')->get('/deces', function (Request $request) {
    return $request->user();
});

Route::get("apitest", [ActeDecesController::class, 'apitest'])->name("apitest");
