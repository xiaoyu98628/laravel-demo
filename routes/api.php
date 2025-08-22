<?php

declare(strict_types=1);

use App\Http\Controllers\Web\V1\ApprovalController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as'     => 'api.',
], function () {

    Route::apiResource('approval', ApprovalController::class)->only(['store']);

});
