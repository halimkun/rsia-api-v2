<?php

namespace App\Helpers;

class ApiResponse
{
  public static function success($message = null)
  {
    return response()->json([
      'message' => $message,
    ], 200);
  }

  public static function error($message, $error, $status = 400)
  {
    return response()->json([
      'message' => $message,
      'error'   => $error,
    ], $status);
  }

  public static function errorWithData($data, $message, $error, $status = 400)
  {
    return response()->json([
      'message' => $message,
      'error'   => $error,
      'data'    => $data,
    ], $status);
  }

  public static function notFound($message = "")
  {
    return response()->json([
      'message'   => $message,
      'error' => "Cannot find data or resource",
    ], 404);
  }

  public static function unauthorized($message = '')
  {
    return response()->json([
      'message' => $message,
      'error'   => 'Unauthorized',
    ], 401);
  }

  public static function forbidden($message = '')
  {
    return response()->json([
      'message' => $message,
      'error'   => 'Forbidden',
    ], 403);
  }

  public static function validationError($message = '')
  {
    return response()->json([
      'message' => $message,
      'error'   => 'Validation Error',
    ], 422);
  }

  public static function internalServerError($message = '')
  {
    return response()->json([
      'message' => $message,
      'error'   => 'Internal Server Error',
    ], 500);
  }

  public static function badGateway($message = '')
  {
    return response()->json([
      'message' => $message,
      'error'   => 'Bad Gateway',
    ], 502);
  }

  public static function withToken($isSuccess, $token, $others = [])
  {
    $data = [
      'access_token' => $token,
    ];

    //  if others is not array
    throw_if(!is_array($others), new \Exception('Others must be array'));

    //  loop others
    foreach ($others as $key => $value) {
      $data[$key] = $value;
    }

    return response()->json($data, 200);
  }
}
