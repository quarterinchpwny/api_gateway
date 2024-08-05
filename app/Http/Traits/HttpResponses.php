<?php

namespace App\Http\Traits;

use Illuminate\Http\Response;

trait HttpResponses
{
    /**
     * successResponse
     *
     * @param  mixed $code
     * @param  mixed $message
     * @param  mixed $data
     * @return void
     */
    public function successResponse($code = Response::HTTP_OK, $message = null, $data)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    /**
     * errorResponse
     *
     * @param  mixed $code
     * @param  mixed $message
     * @param  mixed $e
     * @return void
     */
    public function errorResponse($code = Response::HTTP_BAD_REQUEST, $message = null, $e = null)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'error' => $e ? $e->getMessage() : null,
        ]);
    }
}
