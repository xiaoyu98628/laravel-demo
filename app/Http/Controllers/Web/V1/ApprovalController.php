<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Service\Common\Library\Response\ApiResponse;

class ApprovalController extends Controller
{
    public function store(string $type, Request $request)
    {
        return match ($type) {
            default => ApiResponse::fail(message: '暂不支持该类型')
        };
    }
}
