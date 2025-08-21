<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalTemplate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'approval_template';

    protected $casts = [
        'callback' => 'json',
    ];

    protected $fillable = [
        'id',
        'flow_code',
        'name',
        'callback',
        'remark',
        'status',
        'created_at',
        'created_admin_id',
        'updated_at',
        'updated_admin_id',
        'deleted_at',
        'deleted_admin_id',
    ];

    protected array $resource = [
        'id'        => 'string',
        'flow_code' => 'string',
        'name'      => 'string',
        'callback'  => 'array',
        'remark'    => 'string',
        'status'    => 'string',
    ];
}
