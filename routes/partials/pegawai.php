<?php

use Illuminate\Support\Facades\Route;

Route::resource('pegawai', \App\Http\Controllers\v2\Pegawai::class)->middleware('auth:user-aes');
