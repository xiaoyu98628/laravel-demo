<?php

declare(strict_types=1);

namespace App\Models;

use App\Constants\Enums\FlowNodeTemplate\Type;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $parent_id
 * @property int $depth
 * @property int $priority
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $rules
 * @property array $callback
 * @property string $flow_template_id
 * @property string $created_at
 * @property string $created_admin_id
 * @property string $updated_at
 * @property string $updated_admin_id
 * @property string $deleted_at
 * @property string $deleted_admin_id
 */
class FlowNodeTemplate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'flow_node_template';

    protected $casts = [
        'rules'    => 'json',
        'callback' => 'json',
    ];

    protected $fillable = [
        'id',
        'parent_id',
        'depth',
        'priority',
        'name',
        'description',
        'type',
        'rules',
        'callback',
        'flow_template_id',
        'created_at',
        'created_admin_id',
        'updated_at',
        'updated_admin_id',
        'deleted_at',
        'deleted_admin_id',
    ];

    protected array $resource = [
        'id'               => 'string',
        'parent_id'        => 'string',
        'depth'            => 'int',
        'priority'         => 'int',
        'name'             => 'string',
        'description'      => 'string',
        'type'             => 'string',
        'rules'            => 'array',
        'callback'         => 'array',
        'flow_template_id' => 'string',
    ];

    /**
     * 子节点
     * @return HasOne
     */
    public function children(): HasOne
    {
        return $this->hasOne(self::class, 'parent_id', 'id')
            ->whereNot('type', Type::CONDITION->value)
            ->with(['children', 'conditionNode']);
    }

    /**
     * 条件节点
     * @return HasMany
     */
    public function conditionNode(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->where('type', Type::CONDITION->value)
            ->with(['children', 'conditionNode']);
    }
}
