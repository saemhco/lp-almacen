<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function errorResponse($message, $error = null, $code = 400)
    {
        return response()->json([
            'success' => false,
            'code' => $code,
            'message' => $message,
            'error' => $error ?? null,
        ], $code);
    }
}
