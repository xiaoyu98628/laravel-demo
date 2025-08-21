<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Service\Common\Base\ResourceModelInterface;

class BaseModel extends Model implements ResourceModelInterface
{
    use HasUlids;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

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
