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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth'])->name('dashboard');
    Route::get('/notifikasi/pasien', [\App\Http\Controllers\web\NotifikasiPasienController::class, 'index'])->name('notifikasi.pasien');
    Route::get('/notifikasi/pasien/jadwal-dokter', [\App\Http\Controllers\web\JadwalDokterController::class, 'index'])->name('notifikasi.pasien.jadwal-dokter');
    Route::post('/notifikasi/pasien/jadwal-dokter/send', [\App\Http\Controllers\web\JadwalDokterController::class, 'store'])->name('notifikasi.pasien.jadwal-dokter.send');
});

require __DIR__.'/auth.php';

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
Route::get('/horizon/{view?}', [\App\Http\Controllers\HorizonCustomHomeController::class, 'index'])->where('view', '(.*)')->name('horizon.index');