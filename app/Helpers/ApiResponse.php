<?php

namespace App\Helpers;

class ApiResponse
{

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
