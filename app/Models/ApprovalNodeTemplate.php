<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalNodeTemplate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'approval_node_template';

    protected $casts = [
        'rules' => 'json',
        'callback' => 'json',
    ];
}
