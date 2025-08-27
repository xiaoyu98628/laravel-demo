<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ApprovalNodeTask;
use Service\Common\Base\BaseRepository;

class ApprovalNodeTaskRepositories extends BaseRepository
{
    public function __construct(ApprovalNodeTask $model)
    {
        $this->model = $model;
    }
}
