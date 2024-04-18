<?php

namespace App\Helpers;

use \Illuminate\Support\Facades\Auth;

class AuthenticatedData extends Auth
{
  // get all data from authenticated user
  public static function all($guard = null)
  {
    $guard = $guard ?? Auth::getDefaultDriver();
    return Auth::guard($guard)->user();
  }

  // get specific data from authenticated user
  public static function get($key, $guard = null)
  {
    $guard = $guard ?? Auth::getDefaultDriver();
    return Auth::guard($guard)->user()->$key;
  }
}
