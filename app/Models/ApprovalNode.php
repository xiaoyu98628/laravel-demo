<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalNode extends BaseModel
{
    use SoftDeletes;

    protected $table = 'approval_node';

    protected $casts = [
        'node_template_snapshot' => 'json',
        'callback' => 'json',
        'extend' => 'json',
    ];
}
