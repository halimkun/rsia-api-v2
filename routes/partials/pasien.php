<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['claim:role,pegawai|dokter|pasien'])->prefix('pasien')->group(function () {
    Route::post('riwayat/pemeriksaan/ralan/sync', [\App\Http\Controllers\v2\RiwayatPemeriksaanRalan::class, 'syncKlaim']);
    Route::post('riwayat/pemeriksaan/ranap/sync', [\App\Http\Controllers\v2\RiwayatPemeriksaanRanap::class, 'syncKlaim']);

    Route::post('riwayat/pemeriksaan/ralan/delete/synced', [\App\Http\Controllers\v2\RiwayatPemeriksaanRalan::class, 'deleteSyncedData']);
    Route::post('riwayat/pemeriksaan/ranap/delete/synced', [\App\Http\Controllers\v2\RiwayatPemeriksaanRanap::class, 'deleteSyncedData']);

    // ==================== RIWAYAT PEMERIKSAAN PASIEN
    Route::apiResource('.riwayat', \App\Http\Controllers\v2\RiwayatPemeriksaanPasienController::class)
        ->parameters(['' => 'no_rkm_medis', 'riwayat' => 'no_rawat'])->only(['index', 'show']);

    // ==================== RIWAYAT PEMERIKSAAN PASIEN CUSTOM ROUTE BY NOMOR RAWAT
    Route::apiResource('.riwayat.ralan', \App\Http\Controllers\v2\RiwayatPemeriksaanRalan::class)
        ->parameters(['' => 'no_rkm_medis', 'riwayat' => 'no_rawat'])->only(['index']);
    Route::get('{no_rkm_medis}/riwayat/{no_rawat}/ralan/get-tensi', [\App\Http\Controllers\v2\RiwayatPemeriksaanRalan::class, 'getTensi']);

    Route::apiResource('.riwayat.ranap', \App\Http\Controllers\v2\RiwayatPemeriksaanRanap::class)
        ->parameters(['' => 'no_rkm_medis', 'riwayat' => 'no_rawat'])->only(['index']);
    Route::get('{no_rkm_medis}/riwayat/{no_rawat}/ranap/get-tensi', [\App\Http\Controllers\v2\RiwayatPemeriksaanRanap::class, 'getTensi']);

    Route::apiResource('.riwayat.lab', \App\Http\Controllers\v2\RiwayatPeriksaLabController::class)
        ->parameters(['' => 'no_rkm_medis', 'riwayat' => 'no_rawat'])->only(['index']);

    Route::apiResource('.riwayat.radiologi', \App\Http\Controllers\v2\RiwayatPeriksaRadiologiController::class)
        ->parameters(['' => 'no_rkm_medis', 'riwayat' => 'no_rawat'])->only(['index']);

    Route::apiResource('.riwayat.obat', \App\Http\Controllers\v2\RiwayatPemberianObatController::class)
        ->parameters(['' => 'no_rkm_medis', 'riwayat' => 'no_rawat'])->only(['index']);

    // ==================== DIAGNOSA
    Orion::resource('diagnosa', \App\Http\Controllers\Orion\DiagnosaPasienController::class)->only('search')
        ->parameters(['' => 'base64_no_rawat']);

    // ==================== PROSEDUR
    Orion::resource('prosedur', \App\Http\Controllers\Orion\ProsedurPasienController::class)->only('search')
        ->parameters(['' => 'base64_no_rawat']);

    Route::middleware(['user-aes'])->group(function () {
        // ==================== PASIEN RAWAT INAP
        Route::resource('ranap', \App\Http\Controllers\v2\PasienRawatInapController::class)->only('index');
        Orion::resource('ranap', \App\Http\Controllers\Orion\PasienRawatInapController::class)->only('search')
            ->parameters(['ranap' => 'base64_no_rawat']);

        // ==================== PASIEN RAWAT INAP CUSTOM ROUTE
        Route::post('ranap/real-cost', [\App\Http\Controllers\v2\RealCostController::class, 'ranap'])->name('ranap.real-cost');
        Route::post('ranap/grouping-cost', [\App\Http\Controllers\v2\GroupingCostController::class, 'ranap'])->name('ranap.grouping-cost');

        // ==================== TARIF PASIEN RAWAT INAP (REAL COST UNTUK SPESIFIK PASIEN RAWAT INAP)
        Route::apiResource('ranap.real-cost', \App\Http\Controllers\v2\RealCostPasienRawatInap::class)->only('index')->parameters(['ranap' => 'base64_no_rawat']);

        // ==================== BILLING PASIEN RAWAT INAP
        Route::apiResource('ranap.billing', \App\Http\Controllers\v2\BillingPasienController::class)->only('index')->parameters(['ranap' => 'base64_no_rawat']);


        // ==================== PASIEN RAWAT JALAN
        Orion::resource('ralan', \App\Http\Controllers\Orion\PasienRawatJalanController::class)->only('search')->parameters(['ralan' => 'base64_no_rawat']);
        Route::apiResource('ralan', \App\Http\Controllers\v2\PasienRawatJalanController::class)->only('index')->parameters(['ralan' => 'base64_no_rawat']);
    });

    // ==================== PASIEN
    Route::apiResource('/', \App\Http\Controllers\v2\PasienController::class)->parameters(['' => 'no_rkm_medis']);
    Orion::resource('', \App\Http\Controllers\Orion\PasienController::class)->only('search')->parameters(['pasien' => 'no_rkm_medis']);
});
