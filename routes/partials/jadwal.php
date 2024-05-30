<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['claim:role,pegawai|pasien|dokter'])->group(function ($router) {
    Orion::resource('jadwal', \App\Http\Controllers\Orion\JadwalDokterController::class)->only(['search', 'index']);
});
