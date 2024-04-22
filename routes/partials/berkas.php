<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'surat'], function () {
  // ==================== SURAT INTERNAL
  Route::post('internal/search', [\App\Http\Controllers\v2\RsiaSuratInternalController::class, 'search'])
    ->middleware('auth:user-aes');
  Route::resource('internal', \App\Http\Controllers\v2\RsiaSuratInternalController::class, [])
    ->except(['create', 'edit'])
    ->parameters(['internal' => 'base64_nomor_surat'])
    ->middleware('auth:user-aes');

  // ==================== SURAT EKSTERNAL
  Route::post('eksternal/search', [\App\Http\Controllers\v2\RsiaSuratEksternalController::class, 'search'])
    ->middleware('auth:user-aes');
  Route::resource('eksternal', \App\Http\Controllers\v2\RsiaSuratEksternalController::class, [])
    ->except(['create', 'edit'])
    ->parameters(['eksternal' => 'base64_nomor_surat'])
    ->middleware('auth:user-aes');
});
