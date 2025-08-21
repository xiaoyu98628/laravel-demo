<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function success(mixed $data = null): JsonResponse
    {
        return self::response(status: true, code: 200, message: '操作成功', data: $data);
    }

    public static function error(): JsonResponse
    {
        return self::response(status: false, code: 500, message: '操作失败', data: null);
    }

    private static function response(bool $status, int $code, string $message, $data = null): JsonResponse
    {
        return response()->json(data: ['success' => $status, 'code' => $code, 'message' => $message, 'data' => $data], status: 200);
    }
}
