<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalTemplate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'approval_template';

    protected $casts = [
        'callback' => 'json',
    ];
}
