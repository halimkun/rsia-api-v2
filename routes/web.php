<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// ========== LARAVEL BREEZE
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
// ========== LARAVEL BREZE END

Route::prefix('app')->group(function () {
    Route::prefix('notification')->group(function () {
        Route::get('jadwal-dokter', [\App\Http\Controllers\web\JadwalDokterController::class, 'index'])->name('app.notification.jadwal-dokter');
        Route::post('jadwal-dokter/store', [\App\Http\Controllers\web\JadwalDokterController::class, 'store'])->name('app.notification.jadwal-dokter.store');
    });
});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
Route::get('/horizon/{view?}', [\App\Http\Controllers\HorizonCustomHomeController::class, 'index'])->where('view', '(.*)')->name('horizon.index');