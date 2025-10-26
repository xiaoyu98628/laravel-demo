<?php

declare(strict_types=1);

use App\Http\Controllers\Web\V1\FlowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as'     => 'api.',
], function () {

    Route::controller(FlowController::class)->as('flow.')->prefix('flow')->group(function () {
        Route::post('{code}', 'create')->name('create');
        Route::post('{id}/submit', 'submit')->name('submit');
    });
});
