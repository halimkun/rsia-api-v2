<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['user-aes', 'claim:role,pegawai'])->group(function () {
  // ==================== PEGAWAI
  Orion::resource('pegawai', \App\Http\Controllers\Orion\PegawaiController::class)->only(['search', 'show']);
  Route::resource('pegawai', \App\Http\Controllers\v2\PegawaiController::class)->except(['create', 'edit', 'show'])
    ->parameters(['pegawai' => 'nik']);

  // ==================== UPDATE PROFILE PEGAWAI
  Route::post('pegawai/{pegawai}/profile', [\App\Http\Controllers\v2\PegawaiController::class, 'updateProfile'])
    ->name('pegawai.update-profile');

  // ==================== BERKAS PEGAWAI
  Route::resource('pegawai.berkas', \App\Http\Controllers\v2\BerkasPegawaiController::class)
    ->except(['create', 'edit'])
    ->parameters(['pegawai' => 'nik', 'berkas' => 'kode_berkas']);

  // ==================== JADWAL PEGAWAI
  Orion::resource('pegawai.jadwal', \App\Http\Controllers\Orion\JadwalPegawaiController::class)->only('search')
    ->parameters(['pegawai' => 'nik', 'jadwal' => 'id']);
  Route::resource('pegawai.jadwal', \App\Http\Controllers\v2\JadwalPegawaiController::class)->only(['index'])
    ->parameters(['pegawai' => 'nik', 'jadwal' => 'id']);

  // ==================== PRESENSI PEGAWAI
  Orion::belongsToManyResource('pegawai', 'presensi', \App\Http\Controllers\Orion\PresensiKaryawanController::class)->only(['index', 'search']);
  Route::resource('pegawai/{pegawai}/presensi/temporary', \App\Http\Controllers\v2\RsiaTemporaryPresensiController::class)->only(['index']);

});
