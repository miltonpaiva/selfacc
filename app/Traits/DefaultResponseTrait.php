<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait DefaultResponseTrait
{
    /**
     * Return a success JSON response.
     *
     * @param string|null $message
     * @param mixed|null  $data
     * @param int|null    $code
     *
     * @return JsonResponse
     */
    public static function success(?string $message = 'Success', ?array $data = [], ?int $code = JsonResponse::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string|null $message
     * @param int|null    $code
     * @param array|null  $errors_data
     *
     * @return JsonResponse
     */
    public static function error(?string $message = 'Error', ?array $errors_data = [], ?int $code = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status'      => 'error',
            'message'     => $message,
            'errors_data' => $errors_data,
        ], $code);
    }
}