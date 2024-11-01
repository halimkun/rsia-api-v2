<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['user-aes', 'custom-user', 'claim:role,pegawai'])->group(function ($router) {
    Orion::resource('sep', \App\Http\Controllers\Orion\BridgingSepController::class)->only(['search', 'show'])
    ->parameters(['sep' => 'no_sep']);
    
    Route::get('/sep/{no_sep}/print', [\App\Http\Controllers\v2\BerkasKlaimController::class, 'print']);
    Route::get('/sep/{no_sep}/klaim/sync', [\App\Http\Controllers\v2\KlaimController::class, 'sync']);
    
    Route::resource('/sep/{no_sep}/klaim/status', \App\Http\Controllers\RsiaStatusKlaimController::class)->only(['store']);
    Route::resource('/sep/{no_sep}/klaim/logs', \App\Http\Controllers\RsiaLogStatusKlaim::class)->only(['index']);
    Orion::resource('/sep/{no_sep}/klaim/latest', \App\Http\Controllers\v2\HasilGroupingController::class)->only(['index']);
});
