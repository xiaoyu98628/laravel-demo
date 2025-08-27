<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ApprovalNode;
use Service\Common\Base\BaseRepository;

class ApprovalNodeRepositories extends BaseRepository
{
    public function __construct(ApprovalNode $model)
    {
        $this->model = $model;
    }
}
