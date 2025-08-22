<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\V1\ApprovalTemplateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as'     => 'api.',
], function () {
});
