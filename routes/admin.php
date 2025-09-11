<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\V1\FlowTemplateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as'     => 'admin.',
], function () {

    Route::group([
        'prefix' => 'flow',
        'as'     => 'flow.',
    ], function () {
        Route::controller(FlowTemplateController::class)->as('template.')->prefix('template')->group(function () {
            Route::put('{id}/status', 'status')->name('status');
        });
        Route::apiResource('template', FlowTemplateController::class)->except(['destroy']);
    });

});
