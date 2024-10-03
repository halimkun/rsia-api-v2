<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['user-aes', 'claim:role,pegawai'])->group(function ($router) {
    Orion::resource('sep', \App\Http\Controllers\Orion\BridgingSepController::class)->only(['search', 'show'])
        ->parameters(['sep' => 'no_sep']);
});
Route::get('sep/{no_sep}/print', [\App\Http\Controllers\v2\BerkasKlaimController::class, 'print']);
