<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Approval extends BaseModel
{
    use SoftDeletes;

    protected $table = 'approval';

    protected $casts = [
        'node_template_snapshot' => 'json',
        'callback' => 'json',
        'extend' => 'json',
    ];
}
