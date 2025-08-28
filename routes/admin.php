<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\V1\FlowTemplateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as'     => 'admin.',
], function () {

    Route::controller(FlowTemplateController::class)->as('flow-template.')->prefix('flow-template')->group(function () {
        Route::put('status', 'status')->name('status');
    });
    Route::apiResource('flow-template', FlowTemplateController::class)->except(['destroy']);
});
