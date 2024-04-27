<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function ($router) {
  $router->prefix('auth')->group(function ($router) {
    $router->post('login', [\App\Http\Controllers\v2\UserAuthController::class, 'login']);

    $router->middleware(['user-aes', 'claim:role,pegawai'])->group(function ($router) {
      $router->get('logout', [\App\Http\Controllers\v2\UserAuthController::class, 'logout']);
      $router->get('refresh', [\App\Http\Controllers\v2\UserAuthController::class, 'refresh']);
      $router->get('detail', [\App\Http\Controllers\v2\UserAuthController::class, 'detail']);
    });
  });
});
