<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['user-aes', 'claim:role,pegawai'])->prefix('surat')->group(function () {
  // ==================== SURAT INTERNAL
  
  Orion::resource('internal', \App\Http\Controllers\Orion\RsiaSuratInternalController::class)->only('search');
  Route::resource('internal', \App\Http\Controllers\v2\RsiaSuratInternalController::class, [])
    ->except(['create', 'edit'])
    ->parameters(['internal' => 'base64_nomor_surat']);


  // ==================== SURAT EKSTERNAL
  Route::post('eksternal/search', [\App\Http\Controllers\v2\RsiaSuratEksternalController::class, 'search']);

  Route::resource('eksternal', \App\Http\Controllers\v2\RsiaSuratEksternalController::class, [])
    ->except(['create', 'edit'])
    ->parameters(['eksternal' => 'base64_nomor_surat']);


  // ==================== SURAT MASUK
  Route::post('masuk/search', [\App\Http\Controllers\v2\RsiaSuratMasukController::class, 'search']);

  Route::apiResource('masuk', \App\Http\Controllers\v2\RsiaSuratMasukController::class)
    ->parameters(['id']);
});


// ==================== BERKAS KOMITE
Route::middleware(['user-aes', 'claim:role,pegawai'])->prefix('berkas')->group(function () {
  Route::middleware(['user-aes', 'claim:role,pegawai'])->prefix('komite')->group(function () {
    // ==================== BERKAS KOMITE PMKP
    Route::post('pmkp/search', [\App\Http\Controllers\v2\RsiaBerkasKomitePmkpController::class, 'search']);

    Route::apiResource('pmkp', \App\Http\Controllers\v2\RsiaBerkasKomitePmkpController::class)
      ->parameters(['pmkp' => 'base64_nomor_tgl_terbit']);


    // ==================== BERKAS KOMITE MEDIS
    Orion::resource('medis', \App\Http\Controllers\Orion\RsiaBerkasKomiteMedisController::class)->only('search');

    Route::apiResource('medis', \App\Http\Controllers\v2\RsiaBerkasKomiteMedisController::class)
      ->parameters(['medis' => 'base64_nomor_tgl_terbit']);


    // ==================== BERKAS KOMITE PPI
    Route::post('ppi/search', [\App\Http\Controllers\v2\RsiaBerkasKomitePpiController::class, 'search']);

    Route::apiResource('ppi', \App\Http\Controllers\v2\RsiaBerkasKomitePpiController::class)
      ->parameters(['ppi' => 'base64_nomor_tgl_terbit']);


    // ==================== BERKAS KOMITE KEPERAWATAN
    Orion::resource('keperawatan', \App\Http\Controllers\Orion\RsiaBerkasKomiteKeperawatanController::class)->only('search');

    Route::apiResource('keperawatan', \App\Http\Controllers\v2\RsiaBerkasKomiteKeperawatanController::class)
      ->parameters(['keperawatan' => 'base64_nomor_tgl_terbit']);


    // ==================== BERKAS KOMITE KESEHATAN
    Orion::resource('kesehatan', \App\Http\Controllers\Orion\RsiaBerkasKomiteKesehatanController::class)->only('search');

    Route::apiResource('kesehatan', \App\Http\Controllers\v2\RsiaBerkasKomiteKesehatanController::class)
      ->parameters(['kesehatan' => 'base64_nomor_tgl_terbit']);
  });
});
