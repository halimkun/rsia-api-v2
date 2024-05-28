<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['user-aes', 'claim:role,pegawai|dokter'])->prefix('pasien')->group(function () {
    // ==================== PASIEN RAWAT INAP
    Orion::resource('ranap', \App\Http\Controllers\Orion\PasienRawatInapController::class)->only('search')
        ->parameters(['ranap' => 'base64_no_rawat']);
    Route::apiResource('ranap', \App\Http\Controllers\v2\PasienRawatInapController::class)->only('index')
        ->parameters(['ranap' => 'base64_no_rawat']);

    // ==================== PASIEN RAWAT INAP CUSTOM ROUTE
    Route::post('ranap/real-cost', [\App\Http\Controllers\v2\RealCostController::class, 'ranap'])
        ->name('ranap.real-cost');
    Route::post('ranap/grouping-cost', [\App\Http\Controllers\v2\GroupingCostController::class, 'ranap'])
        ->name('ranap.grouping-cost');

    // ==================== TARIF PASIEN RAWAT INAP
    Route::apiResource('ranap.real-cost', \App\Http\Controllers\v2\RealCostPasienRawatInap::class)->only('index')
        ->parameters(['ranap' => 'base64_no_rawat']);

    // ==================== BILLING PASIEN RAWAT INAP
    Route::apiResource('ranap.billing', \App\Http\Controllers\v2\BillingPasienController::class)->only('index')
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
