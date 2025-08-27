<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $parent_id
 * @property string $flow_code
 * @property string $title
 * @property string $business_type
 * @property string $business_id
 * @property string $status
 * @property array $node_template_snapshot
 * @property array $callback
 * @property string $applicant_type
 * @property string $applicant_id
 * @property array $extend
 * @property string $template_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Approval extends BaseModel
{
    use SoftDeletes;

    protected $table = 'approval';

    protected $casts = [
        'node_template_snapshot' => 'json',
        'callback'               => 'json',
        'extend'                 => 'json',
    ];

    protected $fillable = [
        'id',
        'parent_id',
        'flow_code',
        'title',
        'business_type',
        'business_id',
        'status',
        'node_template_snapshot',
        'callback',
        'applicant_type',
        'applicant_id',
        'extend',
        'template_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected array $resource = [
        'id'                     => 'string',
        'parent_id'              => 'string',
        'flow_code'              => 'string',
        'title'                  => 'string',
        'business_type'          => 'string',
        'business_id'            => 'string',
        'status'                 => 'string',
        'node_template_snapshot' => 'array',
        'callback'               => 'array',
        'applicant_type'         => 'string',
        'applicant_id'           => 'string',
        'extend'                 => 'array',
        'template_id'            => 'string',
        'created_at'             => 'string',
        'updated_at'             => 'string',
        'deleted_at'             => 'string',
    ];
}
