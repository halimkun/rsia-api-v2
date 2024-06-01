<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['claim:role,pegawai,dokter,pasien'])->group(function ($router) {
    Orion::resource('dokter', \App\Http\Controllers\Orion\DokterController::class)->only(['search', 'index', 'show'])
        ->parameters(['dokter' => 'no_sep']);
});
