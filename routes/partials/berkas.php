<?php

use Orion\Facades\Orion;
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

    
  // ==================== SURAT MASUK
  Route::post('masuk/search', [\App\Http\Controllers\v2\RsiaSuratMasukController::class, 'search'])
    ->middleware('auth:user-aes');

  Route::apiResource('masuk', \App\Http\Controllers\v2\RsiaSuratMasukController::class)
    ->parameters(['id'])
    ->middleware('auth:user-aes');
});


// ==================== BERKAS KOMITE
Route::group(['prefix' => 'berkas'], function () {
  Route::group(['prefix' => 'komite'], function () {
    // ==================== BERKAS KOMITE PMKP
    Route::post('pmkp/search', [\App\Http\Controllers\v2\RsiaBerkasKomitePmkpController::class, 'search'])
      ->middleware('auth:user-aes');

    Route::apiResource('pmkp', \App\Http\Controllers\v2\RsiaBerkasKomitePmkpController::class)
      ->parameters(['pmkp' => 'base64_nomor_tgl_terbit'])
      ->middleware('auth:user-aes');
    
    
    // ==================== BERKAS KOMITE MEDIS
    Orion::resource('medis', \App\Http\Controllers\Orion\RsiaBerkasKomiteMedisController::class)
      ->only('search')
      ->middleware('auth:user-aes');
      
    Route::apiResource('medis', \App\Http\Controllers\v2\RsiaBerkasKomiteMedisController::class)
      ->parameters(['medis' => 'base64_nomor_tgl_terbit'])
      ->middleware('auth:user-aes');


    // ==================== BERKAS KOMITE PPI
    Route::post('ppi/search', [\App\Http\Controllers\v2\RsiaBerkasKomitePpiController::class, 'search'])
      ->middleware('auth:user-aes');

    Route::apiResource('ppi', \App\Http\Controllers\v2\RsiaBerkasKomitePpiController::class)
      ->parameters(['ppi' => 'base64_nomor_tgl_terbit'])
      ->middleware('auth:user-aes');
  });
});
