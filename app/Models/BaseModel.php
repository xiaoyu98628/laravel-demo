<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasUlids;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
