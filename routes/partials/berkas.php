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
  Orion::resource('eksternal', \App\Http\Controllers\Orion\RsiaSuratEksternalController::class)->only('search');
  Route::resource('eksternal', \App\Http\Controllers\v2\RsiaSuratEksternalController::class, [])
    ->except(['create', 'edit'])
    ->parameters(['eksternal' => 'base64_nomor_surat']);


  // ==================== SURAT MASUK
  Orion::resource('masuk', \App\Http\Controllers\Orion\RsiaSuratMasukController::class)->only('search');
  Route::apiResource('masuk', \App\Http\Controllers\v2\RsiaSuratMasukController::class)
    ->parameters(['id']);
});


// ==================== BERKAS
Route::middleware(['user-aes', 'claim:role,pegawai'])->prefix('berkas')->group(function () {

  // ==================== BERKAS PKS
  Orion::resource('pks', \App\Http\Controllers\Orion\RsiaPksController::class)->only('search');
  Route::apiResource('pks', \App\Http\Controllers\v2\RsiaPksController::class)
    ->parameters(['pks' => 'base64_nomor_tgl_terbit']);
  
  
    // ==================== BERKAS SK
  Orion::resource('sk', \App\Http\Controllers\Orion\RsiaSkController::class)->only('search');
  Route::apiResource('sk', \App\Http\Controllers\v2\RsiaSkController::class)
    ->parameters(['sk' => 'base64_nomor_tgl_terbit']);
  
  
  // ==================== BERKAS IHT
  Orion::resource('iht', \App\Http\Controllers\Orion\RsiaBerkasIhtController::class)->only('search');
  Route::apiResource('iht', \App\Http\Controllers\v2\RsiaBerkasIhtController::class)
    ->parameters(['iht' => 'base64_nomor_tgl_terbit']);

  // ==================== BERKAS RADIOLOGI
  Orion::resource('radiologi', \App\Http\Controllers\Orion\RsiaBerkasRadiologiController::class)->only('search');
  Route::apiResource('radiologi', \App\Http\Controllers\v2\RsiaBerkasRadiologiController::class)
    ->parameters(['radiologi' => 'base64_nomor_tgl_terbit']);


  
  // ==================== BERKAS KOMITE
  Route::middleware(['user-aes', 'claim:role,pegawai'])->prefix('komite')->group(function () {
    // ==================== BERKAS KOMITE PMKP
    Orion::resource('pmkp', \App\Http\Controllers\Orion\RsiaBerkasKomitePmkpController::class)->only('search');
    Route::apiResource('pmkp', \App\Http\Controllers\v2\RsiaBerkasKomitePmkpController::class)
      ->parameters(['pmkp' => 'base64_nomor_tgl_terbit']);


    // ==================== BERKAS KOMITE MEDIS
    Orion::resource('medis', \App\Http\Controllers\Orion\RsiaBerkasKomiteMedisController::class)->only('search');
    Route::apiResource('medis', \App\Http\Controllers\v2\RsiaBerkasKomiteMedisController::class)
      ->parameters(['medis' => 'base64_nomor_tgl_terbit']);


    // ==================== BERKAS KOMITE PPI
    Orion::resource('ppi', \App\Http\Controllers\Orion\RsiaBerkasKomitePpiController::class)->only('search');
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
