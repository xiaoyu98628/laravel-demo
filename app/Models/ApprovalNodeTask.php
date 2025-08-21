<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalNodeTask extends BaseModel
{
    use SoftDeletes;

    protected $table = 'approval_node_task';

    protected $casts = [
        'operation_info' => 'json',
        'extend' => 'json',
    ];
}
