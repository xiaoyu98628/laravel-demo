<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FlowNodeTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Service\Common\Base\BaseRepository;

class FlowNodeTaskRepositories extends BaseRepository
{
    public function __construct(FlowNodeTask $model)
    {
        $this->model = $model;
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
            'approver_id'      => Arr::get($inputs, 'approver_id'),
            'approver_name'    => Arr::get($inputs, 'approver_name'),
            'approver_type'    => Arr::get($inputs, 'approver_type'),
            'operation_info'   => empty($inputs['operation_info']) ? null : $inputs['operation_info'],
            'status'           => Arr::get($inputs, 'status'),
            'approval_node_id' => Arr::get($inputs, 'approval_node_id'),
            'extend'           => Arr::get($inputs, 'extend'),
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
            'operation_info' => empty($inputs['operation_info']) ? null : $inputs['operation_info'],
            'status'         => Arr::get($inputs, 'status'),
            'extend'         => Arr::get($inputs, 'extend'),
        ]);
    }
}
