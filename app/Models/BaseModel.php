<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Service\Common\Base\ResourceModelInterface;

class BaseModel extends Model implements ResourceModelInterface
{
    use HasUlids;

    /** @var string 与表关联的主键 */
    protected $primaryKey = 'id';

    /** @var bool 表明模型的 ID 是否自增 */
    public $incrementing = false;

    /** @var string 主键 ID 的数据类型 */
    protected $keyType = 'string';

    /** @var array 不可被批量赋值的属性（黑名单） */
    protected $guarded = [];

    protected array $resource = [];

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    public function getResource(): array
    {
        return $this->resource;
    }
}
