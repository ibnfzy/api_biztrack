<?php

use CodeIgniter\HTTP\ResponseInterface;

if (! function_exists('respondSuccess')) {
    /**
     * Return a standardized success response payload.
     */
    function respondSuccess(
        ResponseInterface $response,
        $data = null,
        ?string $message = null,
        int $statusCode = 200
    ): ResponseInterface {
        $payload = ['success' => true];

        if (func_num_args() >= 2) {
            $payload['data'] = $data;
        }

        if ($message !== null) {
            $payload['message'] = $message;
        }

        return $response->setStatusCode($statusCode)->setJSON($payload);
    }
}

if (! function_exists('respondError')) {
    /**
     * Return a standardized error response payload.
     */
    function respondError(
        ResponseInterface $response,
        string $message,
        string $error,
        int $statusCode
    ): ResponseInterface {
        return $response->setStatusCode($statusCode)->setJSON([
            'success' => false,
            'error'   => $error,
            'message' => $message,
        ]);
    }
}

if (! function_exists('respondBadRequest')) {
    function respondBadRequest(ResponseInterface $response, string $message = 'Validation error description'): ResponseInterface
    {
        return respondError($response, $message, 'Bad Request', 400);
    }
}

if (! function_exists('respondUnauthorized')) {
    function respondUnauthorized(ResponseInterface $response, string $message = 'JWT token is required or invalid'): ResponseInterface
    {
        return respondError($response, $message, 'Unauthorized', 401);
    }
}

if (! function_exists('respondForbidden')) {
    function respondForbidden(ResponseInterface $response, string $message = 'Insufficient permissions'): ResponseInterface
    {
        return respondError($response, $message, 'Forbidden', 403);
    }
}

if (! function_exists('respondNotFound')) {
    function respondNotFound(ResponseInterface $response, string $message = 'Resource not found'): ResponseInterface
    {
        return respondError($response, $message, 'Not Found', 404);
    }
}

if (! function_exists('respondServerError')) {
    function respondServerError(ResponseInterface $response, string $message = 'An unexpected error occurred'): ResponseInterface
    {
        return respondError($response, $message, 'Internal Server Error', 500);
    }
}

