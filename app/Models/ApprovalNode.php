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
 * @property string $method
 * @property string $status
 * @property array $callback
 * @property string $approval_id
 * @property array $extend
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class ApprovalNode extends BaseModel
{
    use SoftDeletes;

    protected $table = 'approval_node';

    protected $casts = [
        'callback' => 'json',
        'extend'   => 'json',
    ];

    protected $fillable = [
        'id',
        'parent_id',
        'type',
        'depth',
        'name',
        'method',
        'status',
        'callback',
        'approval_id',
        'extend',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected array $resource = [
        'id'          => 'string',
        'parent_id'   => 'string',
        'type'        => 'string',
        'depth'       => 'string',
        'name'        => 'string',
        'method'      => 'string',
        'status'      => 'string',
        'callback'    => 'array',
        'approval_id' => 'string',
        'extend'      => 'array',
        'created_at'  => 'string',
        'updated_at'  => 'string',
        'deleted_at'  => 'string',
    ];
}
