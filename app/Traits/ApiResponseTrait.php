<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function success($data = null, $message = null, $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message ?? 'OK',
            'data' => $data
        ], $status);
    }

    protected function error($message = 'Erro interno', $status = 500, $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}
