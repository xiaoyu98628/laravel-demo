<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $flow_code
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

    /**
     * @return HasOne
     */
    public function nodeTemplate(): HasOne
    {
        return $this->hasOne(ApprovalNodeTemplate::class, 'template_id', 'id');
    }
}
