<?php

use Illuminate\Support\Facades\Route;

// ==================== PEGAWAI
Route::resource('pegawai', \App\Http\Controllers\v2\PegawaiController::class)
  ->except(['create', 'edit'])
  ->parameters(['pegawai' => 'nik'])
  ->middleware('auth:user-aes');
  
  // ==================== BERKAS PEGAWAI
  Route::resource('pegawai.berkas', \App\Http\Controllers\v2\BerkasPegawaiController::class)
  ->except(['create', 'edit'])
  ->parameters(['pegawai' => 'nik', 'berkas' => 'kode_berkas'])
  ->middleware('auth:user-aes');
