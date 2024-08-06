<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['claim:role,pegawai|dokter'])->prefix('rsia')->group(function ($router) {
    Route::resource('file/manager', \App\Http\Controllers\v2\RsiaFileManagerController::class)->parameter('manager', 'id');
    Orion::resource('coder/nik', \App\Http\Controllers\Orion\RsiaCoderNikController::class)->parameter('manager', 'id')->except(['create', 'edit']);
});
