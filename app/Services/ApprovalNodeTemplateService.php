<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ApprovalNodeTemplateRepositories;
use Service\Common\Base\BaseRepository;

class ApprovalNodeTemplateService extends BaseRepository
{
    public function __construct(
        private readonly ApprovalNodeTemplateRepositories $repositories
    ) {}

    public function updateOrStore(string $templateId, array $node, int $stepOrder, ?string $parentId = null): string
    {
        if (empty($node['id'])) {
            $model = $this->repositories->create($node);
            $id    = $model->id;
        } else {

        }

        return $id;
    }
}
