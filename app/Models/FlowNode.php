<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $parent_id
 * @property string $type
 * @property string $depth
 * @property string $name
 * @property string $rules
 * @property string $status
 * @property array $callback
 * @property string $flow_id
 * @property array $extend
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class FlowNode extends BaseModel
{
    use SoftDeletes;

    protected $table = 'flow_node';

    protected $casts = [
        'rules'    => 'json',
        'callback' => 'json',
        'extend'   => 'json',
    ];

    protected $fillable = [
        'id',
        'parent_id',
        'depth',
        'name',
        'type',
        'rules',
        'status',
        'callback',
        'flow_id',
        'extend',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected array $resource = [
        'id'         => 'string',
        'parent_id'  => 'string',
        'depth'      => 'string',
        'name'       => 'string',
        'type'       => 'string',
        'rules'      => 'array',
        'status'     => 'string',
        'callback'   => 'array',
        'flow_id'    => 'string',
        'extend'     => 'array',
        'created_at' => 'string',
        'updated_at' => 'string',
        'deleted_at' => 'string',
    ];
}
