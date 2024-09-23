<?php

namespace App\Traits;

trait ApiResponse 
{
    protected function success($message = null, $data = null, $code = 200) {
        return response()->json([
            'status'    => 'success',
            'message'   => $message,
            'data'      => $data
        ], $code);
    }

    protected function error($message = null, $code = 400) {
        return response()->json([
            'status'    => 'error',
            'message'   => $message
        ], $code);
    }
}
