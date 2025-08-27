<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $parent_id
 * @property string $depth
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $rules
 * @property array $callback
 * @property string $template_id
 * @property string $created_at
 * @property string $created_admin_id
 * @property string $updated_at
 * @property string $updated_admin_id
 * @property string $deleted_at
 * @property string $deleted_admin_id
 */
class ApprovalNodeTemplate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'approval_node_template';

    protected $casts = [
        'rules'    => 'json',
        'callback' => 'json',
    ];

    protected $fillable = [
        'id',
        'parent_id',
        'depth',
        'name',
        'description',
        'type',
        'rules',
        'callback',
        'template_id',
        'created_at',
        'created_admin_id',
        'updated_at',
        'updated_admin_id',
        'deleted_at',
        'deleted_admin_id',
    ];

    protected array $resource = [
        'id'          => 'string',
        'parent_id'   => 'string',
        'depth'       => 'string',
        'name'        => 'string',
        'description' => 'string',
        'type'        => 'string',
        'rules'       => 'string',
        'callback'    => 'array',
        'template_id' => 'string',
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('children');
    }
}
