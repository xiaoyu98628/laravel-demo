<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\V1\ApprovalTemplateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as'     => 'admin.',
], function () {

    Route::controller(ApprovalTemplateController::class)->as('approval-template.')->prefix('approval-template')->group(function () {
        Route::put('status', 'status')->name('status');
    });
    Route::apiResource('approval-template', ApprovalTemplateController::class)->except(['destroy']);
});
