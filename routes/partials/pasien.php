<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['user-aes', 'claim:role,pegawai|dokter'])->prefix('pasien')->group(function () {
    // ==================== PASIEN RAWAT INAP
    Orion::resource('ranap', \App\Http\Controllers\Orion\PasienRawatInapController::class)->only('search')
        ->parameters(['ranap' => 'base64_no_rawat']);
    Route::apiResource('ranap', \App\Http\Controllers\v2\PasienRawatInapController::class)->only('index')
        ->parameters(['ranap' => 'base64_no_rawat']);

    // ==================== TARIF PASIEN RAWAT INAP
    Route::apiResource('ranap.tarif', \App\Http\Controllers\v2\TarifPasienRawatInap::class)->only('index')
        ->parameters(['ranap' => 'base64_no_rawat']);



    // ==================== PASIEN RAWAT JALAN
    Orion::resource('ralan', \App\Http\Controllers\Orion\PasienRawatJalanController::class)->only('search')
        ->parameters(['ralan' => 'base64_no_rawat']);
    Route::apiResource('ralan', \App\Http\Controllers\v2\PasienRawatJalanController::class)->only('index')
        ->parameters(['ralan' => 'base64_no_rawat']);
});


// ==================== PASIEN
Orion::resource('pasien', \App\Http\Controllers\Orion\PasienController::class)->only('search')
    ->parameters(['pasien' => 'no_rkm_medis']);

Route::apiResource('pasien', \App\Http\Controllers\v2\PasienController::class)
    ->parameters(['pasien' => 'no_rkm_medis']);
