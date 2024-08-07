<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

// FIXME : review ulang endpoint undangan (SEMUA) 
Route::middleware(['user-aes', 'claim:role,pegawai'])->prefix('undangan')->group(function () {
    // ==================== PENERIMA UNDANGAN 
    Orion::resource('penerima', \App\Http\Controllers\Orion\RsiaPenerimaUndanganController::class)->only(['search'])
        ->parameters(['penerima' => 'base64_no_surat']); // INFO : selesai

    Route::apiResource('penerima', \App\Http\Controllers\v2\RsiaPenerimaUndanganController::class)
        ->only(['store', 'show'])
        ->parameters(['penerima' => 'base64_no_surat']);

    // ==================== KEHADIRAN RAPAT
    Route::apiResource('kehadiran', \App\Http\Controllers\v2\RsiaKehadiranRapatController::class)
        ->only(['store', 'show'])
        ->parameters(['kehadiran' => 'base64_no_surat']);
});

Route::middleware(['user-aes', 'claim:role,pegawai'])->group(function () {
    Orion::resource('undangan', \App\Http\Controllers\Orion\RsiaUndanganController::class)
        ->only('search'); // INFO : selesai

    Route::resource('agenda', \App\Http\Controllers\v2\AgendaController::class)
        ->only(['index']);
});
