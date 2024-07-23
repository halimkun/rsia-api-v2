<?php

namespace App\Http\Controllers;

use App\Helpers\Notification\FirebaseCloudMessaging;
use Illuminate\Http\Request;
use Laravel\Horizon\Horizon;
use Illuminate\Support\Facades\App;

/**
 * Horizon custom home controller.
 * 
 * This controller is used to override the default Horizon home controller. 
 * 
 * why  :
 * we need to override the default Horizon home controller to change the path of the Horizon dashboard. this method created to avoid the 404 error when accessing the Horizon dashboard and data.
 * */
class HorizonCustomHomeController extends Controller
{
    /**
     * Single page application catch-all route.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    // public function index(Request $request)
    // {
    //     $horizon = Horizon::scriptVariables();
    //     $horizon['path'] = env('APP_NAME', 'laravel') . '/' . env('HORIZON_PATH', 'horizon');

    //     return view('horizon::layout', [
    //         'assetsAreCurrent'       => Horizon::assetsAreCurrent(),
    //         'horizonScriptVariables' => $horizon,
    //         'isDownForMaintenance'   => App::isDownForMaintenance(),
    //     ]);
    // }
}
