<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $title
 * @property string $type
 * @property string $code
 * @property string $parent_flow_id
 * @property string $parent_node_id
 * @property string $business_id
 * @property array $business_snapshot
 * @property string $level
 * @property string $status
 * @property array $flow_node_template_snapshot
 * @property array $callback
 * @property string $applicant_type
 * @property string $applicant_id
 * @property array $extend
 * @property string $flow_template_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Flow extends BaseModel
{
    use SoftDeletes;

    protected $table = 'flow';

    protected $casts = [
        'business_snapshot'           => 'json',
        'flow_node_template_snapshot' => 'json',
        'callback'                    => 'json',
        'extend'                      => 'json',
    ];

    protected array $resource = [
        'id'                          => 'string',
        'title'                       => 'string',
        'type'                        => 'string',
        'code'                        => 'string',
        'parent_flow_id'              => 'string',
        'parent_node_id'              => 'string',
        'level'                       => 'string',
        'business_id'                 => 'string',
        'business_snapshot'           => 'array',
        'status'                      => 'string',
        'flow_node_template_snapshot' => 'array',
        'callback'                    => 'array',
        'applicant_type'              => 'string',
        'applicant_id'                => 'string',
        'extend'                      => 'array',
        'flow_template_id'            => 'string',
        'created_at'                  => 'string',
        'updated_at'                  => 'string',
        'deleted_at'                  => 'string',
    ];
}
