<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Constants\Enums\FlowTemplate\Status;
use App\Models\FlowTemplate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Service\Common\Base\BaseRepository;

class FlowTemplateRepositories extends BaseRepository
{
    public function __construct(FlowTemplate $model)
    {
        $this->model = $model;
    }

    /**
     * 分页
     * @param  array  $inputs
     * @return LengthAwarePaginator
     */
    public function page(array $inputs): LengthAwarePaginator
    {
        $query = $this->query();

        $query->when(! empty($inputs['type']), fn ($query) => $query->where('type', $inputs['type']));
        $query->when(! empty($inputs['status']), fn ($query) => $query->where('status', $inputs['status']));
        $query->when(! empty($inputs['name']), fn ($query) => $query->where('name', 'like', '%'.$inputs['name'].'%'));

        return $query->paginate($inputs['page_size']);
    }

    /**
     * 列表
     * @param  array  $inputs
     * @return Collection
     */
    public function list(array $inputs): Collection
    {
        $query = $this->query();

        $query->when(! empty($inputs['type']), fn ($query) => $query->where('type', $inputs['type']));
        $query->when(! empty($inputs['status']), fn ($query) => $query->where('status', $inputs['status']));

        return $query->get();
    }

    /**
     * 创建
     * @param  array  $inputs
     * @return Model
     * @throws \Exception
     */
    public function store(array $inputs): Model
    {
        if (empty($inputs)) {
            throw new \Exception('参数错误');
        }

        return $this->query()->create([
            'type'     => Arr::get($inputs, 'type'),
            'name'     => Arr::get($inputs, 'name'),
            'callback' => empty($inputs['callback']) ? null : $inputs['callback'],
            'remark'   => Arr::get($inputs, 'remark', ''),
            'status'   => Status::DISABLE->value,
        ]);
    }

    /**
     * 更新
     * @param  string  $id
     * @param  array  $inputs
     * @return int
     * @throws \Exception
     */
    public function update(string $id, array $inputs): int
    {
        if (empty($inputs)) {
            throw new \Exception('参数错误');
        }

        return $this->query()->where('id', $id)->update([
            'name'     => Arr::get($inputs, 'name'),
            'callback' => empty($inputs['callback']) ? null : $inputs['callback'],
            'remark'   => Arr::get($inputs, 'remark', ''),
        ]);
    }
}
