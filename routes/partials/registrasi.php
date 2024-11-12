<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['claim:role,pegawai|pasien|dokter'])->group(function ($router) {
    Route::prefix('registrasi')->group(function($router) {
        Orion::resource('periksa', \App\Http\Controllers\Orion\RegPeriksaController::class)->only(['search', 'show'])->parameters([ '' => 'base64-no_rawat' ]);
    });

    Route::prefix('booking')->group(function ($router) {
        Orion::resource('registrasi', \App\Http\Controllers\Orion\BookingRegistrasiController::class)->only(['search']);
        Route::apiResource('registrasi', \App\Http\Controllers\v2\BookingRegistrasiController::class)->only(['store', 'show', 'update', 'destroy']);

        Route::middleware('custom-user')->post('/registrasi/batal', [\App\Http\Controllers\v2\BookingRegistrasiController::class, 'batal']);
    });
});
