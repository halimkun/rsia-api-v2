<?php

namespace App\Helpers;

class ApiResponse
{
  public static function success($data, $message = null)
  {
    return response()->json([
      'status'  => 'success',
      'message' => $message,
      'data'    => $data,
    ], 200);
  }

  public static function ok($message = 'OK')
  {
    return response()->json([
      'status'  => 'success',
      'message' => $message,
    ], 200);
  }

  public static function error($message, $status = 400)
  {
    return response()->json([
      'status'  => 'error',
      'message' => $message,
    ], $status);
  }

  public static function errorWithData($data, $message, $status = 400)
  {
    return response()->json([
      'status'  => 'error',
      'message' => $message,
      'data'    => $data,
    ], $status);
  }

  public static function notFound($message = 'Data tidak ditemukan')
  {
    return response()->json([
      'status'  => 'error',
      'message' => $message,
    ], 404);
  }

  public static function unauthorized($message = 'Unauthorized')
  {
    return response()->json([
      'status'  => 'error',
      'message' => $message,
    ], 401);
  }

  public static function forbidden($message = 'Forbidden')
  {
    return response()->json([
      'status'  => 'error',
      'message' => $message,
    ], 403);
  }

  public static function validationError($message = 'Validation Error')
  {
    return response()->json([
      'status'  => 'error',
      'message' => $message,
    ], 422);
  }

  public static function internalServerError($message = 'Internal Server Error')
  {
    return response()->json([
      'status'  => 'error',
      'message' => $message,
    ], 500);
  }

  public static function badGateway($message = 'Bad Gateway')
  {
    return response()->json([
      'status'  => 'error',
      'message' => $message,
    ], 502);
  }

  // custom 
  public static function customSuccess($isSuccess, $status, $message, $data = null)
  {
    return response()->json([
      'status'  => $status,
      'message' => $message,
      'data'    => $data,
    ], 200);
  }

  public static function customError($isSuccess, $status, $message, $data = null)
  {
    return response()->json([
      'status'  => $status,
      'message' => $message,
      'data'    => $data,
    ], 400);
  }

  // custom 
  public static function custom($isSuccess, $status, $message, $data = null, $code = 200)
  {
    return response()->json([
      'status'  => $status,
      'message' => $message,
      'data'    => $data,
    ], $code);
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
