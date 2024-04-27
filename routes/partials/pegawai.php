<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:user-aes', 'claim:role,pegawai'])->group(function () {
  // ==================== PEGAWAI
  Route::resource('pegawai', \App\Http\Controllers\v2\PegawaiController::class)
    ->except(['create', 'edit'])
    ->parameters(['pegawai' => 'nik']);

  // ==================== BERKAS PEGAWAI
  Route::resource('pegawai.berkas', \App\Http\Controllers\v2\BerkasPegawaiController::class)
    ->except(['create', 'edit'])
    ->parameters(['pegawai' => 'nik', 'berkas' => 'kode_berkas']);
});
