<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function (Request $request) {
    try {
        $c = \Illuminate\Support\Facades\DB::connection()->getPdo();
        return response()->json([
            'message' => 'Database connection success!',
            'connection' => $c->getAttribute(PDO::ATTR_CONNECTION_STATUS)
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Database connection failed!',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/credit', function (Request $request) {
    return response()->json([
        'developer'   => 'M Faisal Halim',
        'email'       => 'ffaisalhalim@gmail.com',
        'github'      => 'https://github.com/halimkun',
        'repository'  => 'https://github.com/halimkun/rsia-api-v2.git',
        'license'     => 'MIT License',
        'license_url' => 'https://github.com/halimkun/rsia-api-v2/blob/master/LICENSE',
        'version'     => 'Laravel ' . app()->version(),
    ], 200);
});

Route::middleware(['user-aes', 'claim:role,pegawai|dokter'])->prefix('notification')->group(function () {
    Route::post('send', [\App\Http\Controllers\v2\NotificationController::class, 'send']);
    Route::post('with-template', [\App\Http\Controllers\v2\NotificationController::class, 'withTemplate']);
});

$files = scandir(__DIR__ . '/partials');
foreach ($files as $file) {
    // if file is not a directory
    if (!is_dir(__DIR__ . '/partials/' . $file)) {
        // require_once the file
        require_once __DIR__ . '/partials/' . $file;
    }
}
