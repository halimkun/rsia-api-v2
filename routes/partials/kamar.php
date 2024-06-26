<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['claim:role,pegawai|pasien|dokter'])->group(function ($router) {
    
    
    // kamar inap data
    Route::prefix('kamar')->group(function($router) {
        Orion::resource('inap', \App\Http\Controllers\Orion\KamarInapController::class)->only(['search', 'index'])
            ->parameters([ 'inap' => 'base64-no_rawat' ]);
        Route::apiResource('inap', \App\Http\Controllers\v2\KamarInapController::class)->only(['show'])
            ->parameters([ 'inap' => 'base64-no_rawat' ]);
    });
});
