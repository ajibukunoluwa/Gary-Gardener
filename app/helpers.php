<?php

use App\Exceptions\AbortJsonException;
use Symfony\Component\HttpFoundation\Response;

if (! function_exists('json')) {

    /**
     * Format json response for consistency.
     *
     * @param  array|mix    $data
     * @param  string   $message    (Response message)
     * @param  int      $statusCode (Status code for request)
     *
     * @return object
     */
    function json($data, string $message, int $statusCode = Response::HTTP_OK)
    {
        $response = [
            'status'    => $statusCode,
            'message'   => $message,
            'data'      => $data,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Abort request
     *
     * @param  string   $message    (Response message)
     * @param  int      $statusCode (Status code for request)
     *
     * @return object
     */
    function abortJson(string $message, int $statusCode = Response::HTTP_FORBIDDEN)
    {
        Throw new AbortJsonException($message, $statusCode);
    }
}
