<?php

namespace App\Traits;


trait Response
{
    protected function sendResponse($message)
    {
        return response()->json([
            'message' => $message,
            'status'  => true
        ], 200);
    }

    protected function sendError($message)
    {
        return response()->json([
            'message' => $message,
            'status'  => false
        ], 200);
    }
}
