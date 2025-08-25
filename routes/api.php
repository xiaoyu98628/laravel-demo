<?php

declare(strict_types=1);

use App\Constants\Enums\Approval\BusinessType;
use App\Http\Controllers\Web\V1\ApprovalController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as'     => 'api.',
], function () {

    Route::controller(ApprovalController::class)->as('approval.')->prefix('approval')->group(function () {
        Route::post('{type}', 'create')->name('create')->where('type', BusinessType::pattern());
    });

});
