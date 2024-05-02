<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['user-aes', 'claim:role,pegawai'])->group(function () {
  // ==================== PEGAWAI
  Orion::resource('pegawai', \App\Http\Controllers\Orion\PegawaiController::class)->only('search');
  Route::resource('pegawai', \App\Http\Controllers\v2\PegawaiController::class)
    ->except(['create', 'edit'])
    ->parameters(['pegawai' => 'nik']);

  // ==================== BERKAS PEGAWAI
  Route::resource('pegawai.berkas', \App\Http\Controllers\v2\BerkasPegawaiController::class)
    ->except(['create', 'edit'])
    ->parameters(['pegawai' => 'nik', 'berkas' => 'kode_berkas']);
});
