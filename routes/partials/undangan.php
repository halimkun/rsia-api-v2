<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['user-aes', 'claim:role,pegawai'])->prefix('undangan')->group(function () {
    // ==================== PENERIMA UNDANGAN 
    Route::apiResource('penerima', \App\Http\Controllers\v2\RsiaPenerimaUndanganController::class)
        ->parameters(['penerima' => 'base64_no_surat']);

    // ==================== KEHADIRAN RAPAT
    Route::apiResource('kehadiran', \App\Http\Controllers\v2\RsiaKehadiranRapatController::class)
        ->only(['store', 'show'])
        ->parameters(['kehadiran' => 'base64_no_surat']);
});
