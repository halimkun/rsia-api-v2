<?php 

use Orion\Facades\Orion;

Orion::resource('departemen', \App\Http\Controllers\Orion\DepartemenController::class)
    // ->middleware(['user-aes', 'claim:role,pegawai'])
    ->only('search');