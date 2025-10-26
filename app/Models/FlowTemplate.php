<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $type
 * @property string $code
 * @property string $name
 * @property array $callback
 * @property string $remark
 * @property string $status
 * @property string $created_at
 * @property string $created_admin_id
 * @property string $updated_at
 * @property string $updated_admin_id
 * @property string $deleted_at
 * @property string $deleted_admin_id
 */
class FlowTemplate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'flow_template';

    protected $casts = [
        'callback' => 'json',
    ];

    protected array $resource = [
        'id'       => 'string',
        'type'     => 'string',
        'code'     => 'string',
        'name'     => 'string',
        'callback' => 'array',
        'remark'   => 'string',
        'status'   => 'string',
    ];

    /**
     * @return HasOne
     */
    public function nodeTemplate(): HasOne
    {
        return $this->hasOne(FlowNodeTemplate::class, 'flow_template_id', 'id')->whereNull('parent_id');
    }
}
