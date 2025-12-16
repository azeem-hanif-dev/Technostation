<?php

namespace App\Traits\Helpers;

use Illuminate\Http\JsonResponse;

trait ResponseMethodsTrait
{
    protected function sendError($errorMessages, $result = [], $code = 404): JsonResponse
    {
        $response = [
            'metadata' => [
                'responseCode' => $code,
                'success' => false,
                'message' => $errorMessages,
            ],
            'payload' => $result,
        ];

        return response()->json($response, $code);
    }

    protected function sendResponse($result, $message = '', $code = 200): JsonResponse
    {
        $response = [
            'metadata' => [
                'responseCode' => $code,
                'success' => true,
                'message' => $message,
            ],
            'payload' => $result,
        ];

        return response()->json($response, 200);
    }

    protected function sendServerError($errorMessages = 'Something went wrong, internal server error', $result = [], $code = 500): JsonResponse
    {
        $response = [
            'metadata' => [
                'responseCode' => $code,
                'success' => false,
                'message' => $errorMessages,
            ],
            'payload' => $result,
        ];

        return response()->json($response, $code);
    }

    protected function serverErrorMessage(): string
    {
        return 'Something went wrong, internal server error';
    }
}
