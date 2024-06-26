<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Orion::resource('undangan', \App\Http\Controllers\Orion\RsiaUndangan::class)
    // ->middleware(['user-aes', 'claim:role,pegawai'])
    ->only('search');

Route::middleware(['user-aes', 'claim:role,pegawai'])->prefix('undangan')->group(function () {
    // ==================== PENERIMA UNDANGAN 
    Orion::resource('penerima', \App\Http\Controllers\Orion\RsiaPenerimaUndanganController::class)->only(['search'])
        ->parameters(['penerima' => 'base64_no_surat']);
        
    Route::apiResource('penerima', \App\Http\Controllers\v2\RsiaPenerimaUndanganController::class)
        ->only(['store', 'show'])
        ->parameters(['penerima' => 'base64_no_surat']);

    // FIXME : review ulang endpoint undangan (SEMUA)
    // ==================== KEHADIRAN RAPAT
    Route::apiResource('kehadiran', \App\Http\Controllers\v2\RsiaKehadiranRapatController::class)
        ->only(['store', 'show'])
        ->parameters(['kehadiran' => 'base64_no_surat']);
});
