<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Service\Common\Library\Response\ApiResponse;
use Service\Common\Library\Response\Common\ClientFailedCode;
use Service\Common\Library\Response\Exceptions\BusinessException;
use Service\Common\Library\Rpc\Service\Exception\BusinessProtocolException;
use Service\Common\Middleware\RequestLog;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            \Illuminate\Support\Facades\Route::prefix('admin')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', [
            RequestLog::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReport([
            BusinessException::class,
            BusinessProtocolException::class,
        ]);
        $exceptions->render(function (Exception $e, Request $request) {
            // 登录未授权
            if ($e instanceof AuthenticationException) {
                return ApiResponse::fail(code: ClientFailedCode::CLIENT_HTTP_UNAUTHORIZED_ERROR, data: $e->getMessage());
            }
            // 方法错误
            if ($e instanceof MethodNotAllowedHttpException) {
                return ApiResponse::fail(code: ClientFailedCode::CLIENT_METHOD_NOT_ALLOWED_ERROR, data: $e->getMessage());
            }
            // 路由不存在
            if ($e instanceof NotFoundHttpException) {
                return ApiResponse::fail(code: ClientFailedCode::CLIENT_NOT_FOUND_ERROR, data: $e->getMessage());
            }
            // 请求频率异常
            if ($e instanceof ThrottleRequestsException) {
                return ApiResponse::fail(code: ClientFailedCode::CLIENT_TOO_MANY_REQUESTS, data: $e->getMessage());
            }
            // 数据验证
            if ($e instanceof ValidationException) {
                $message = current(current($e->validator->errors()->getMessages())) ?: ClientFailedCode::CLIENT_PARAMETER_ERROR->message();

                return ApiResponse::fail(code: ClientFailedCode::CLIENT_PARAMETER_ERROR, data: $e->errors(), message: $message);
            }
            // 业务异常
            if ($e instanceof BusinessException) {
                return ApiResponse::fail(code: $e->getResponseCode(), message: $e->getMessage());
            }

            return false;
        });
    })->create();
