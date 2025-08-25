<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Approval;
use Service\Common\Base\BaseRepository;

class ApprovalRepositories extends BaseRepository
{
    public function __construct(Approval $model)
    {
        $this->model = $model;
    }
}
