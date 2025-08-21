<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\ApprovalTemplate\Status;
use App\Models\ApprovalTemplate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Service\Common\Base\BaseRepository;

class ApprovalTemplateRepositories extends BaseRepository
{
    public function __construct(ApprovalTemplate $model)
    {
        $this->model = $model;
    }

    /**
     * 分页
     * @param  array  $data
     * @return LengthAwarePaginator
     */
    public function page(array $data): LengthAwarePaginator
    {
        $query = $this->query();

        ! empty($data['flow_code']) && $query->where('flow_code', $data['flow_code']);
        ! empty($data['status']) && $query->where('status', $data['status']);
        ! empty($data['name']) && $query->where('name', 'like', '%'.$data['name'].'%');

        return $query->paginate($data['page_size']);
    }

    /**
     * 列表
     * @param  array  $data
     * @return Collection
     */
    public function list(array $data): Collection
    {
        return $this->query()->get();
    }

    /**
     * 创建
     * @param  array  $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->query()->create([
            'flow_code' => $data['flow_code'],
            'name'      => $data['name'],
            'callback'  => Arr::get($data, 'callback', DB::raw("'{}'")),
            'remark'    => Arr::get($data, 'remark', ''),
            'status'    => Status::DISABLE->value,
        ]);
    }
}
