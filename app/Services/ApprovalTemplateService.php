<?php

namespace App\Services;

use App\Repositories\ApprovalTemplateRepositories;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ApprovalTemplateService
{
    public function __construct(
        private ApprovalTemplateRepositories $repositories
    ) {}

    /**
     * 列表
     * @param array $inputs
     * @return Collection|LengthAwarePaginator
     */
    public function index(array $inputs): Collection|LengthAwarePaginator
    {
        $query = $this->repositories->query();

        ! empty($inputs['code']) && $query->where('code', $inputs['code']);

        ! empty($inputs['status']) && $query->where('status', $inputs['status']);

        ! empty($inputs['name']) && $query->where('name', 'like', '%'.$inputs['name'].'%');

        return empty($inputs['not_page'])
            ? $query->get()
            : $query->paginate($inputs['page_size'] ?? 10);
    }
}
