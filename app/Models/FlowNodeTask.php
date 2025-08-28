<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $approval_id
 * @property string $approver_name
 * @property string $approver_type
 * @property array $operation_info
 * @property string $status
 * @property string $flow_node_id
 * @property array $extend
 * @property string $created_at
 * @property string $created_admin_id
 * @property string $updated_at
 * @property string $updated_admin_id
 * @property string $deleted_at
 * @property string $deleted_admin_id
 */
class FlowNodeTask extends BaseModel
{
    use SoftDeletes;

    protected $table = 'flow_node_task';

    protected $casts = [
        'operation_info' => 'json',
        'extend'         => 'json',
    ];

    protected $fillable = [
        'id',
        'approver_id',
        'approver_name',
        'approver_type',
        'operation_info',
        'status',
        'flow_node_id',
        'extend',
        'created_at',
        'created_admin_id',
        'updated_at',
        'updated_admin_id',
        'deleted_at',
        'deleted_admin_id',
    ];

    protected array $resource = [
        'id'               => 'string',
        'approver_id'      => 'string',
        'approver_name'    => 'string',
        'approver_type'    => 'string',
        'operation_info'   => 'array',
        'status'           => 'string',
        'flow_node_id'     => 'string',
        'extend'           => 'array',
        'created_at'       => 'string',
        'created_admin_id' => 'string',
        'updated_at'       => 'string',
        'updated_admin_id' => 'string',
        'deleted_at'       => 'string',
        'deleted_admin_id' => 'string',
    ];
}
