<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FlowNode;
use Illuminate\Support\Arr;
use Service\Common\Base\BaseRepository;

class FlowNodeRepositories extends BaseRepository
{
    public function __construct(FlowNode $model)
    {
        $this->model = $model;
    }

    /**
     * 创建
     * @param  array  $inputs
     * @return FlowNode
     * @throws \Exception
     */
    public function store(array $inputs): FlowNode
    {
        if (empty($inputs)) {
            throw new \Exception('参数错误');
        }

        return $this->query()->create([
            'parent_id' => Arr::get($inputs, 'parent_id'),
            'depth'     => Arr::get($inputs, 'depth'),
            'name'      => Arr::get($inputs, 'name'),
            'type'      => Arr::get($inputs, 'type'),
            'rules'     => Arr::get($inputs, 'rules'),
            'status'    => Arr::get($inputs, 'status'),
            'callback'  => empty($inputs['callback']) ? null : $inputs['callback'],
            'flow_id'   => Arr::get($inputs, 'flow_id'),
            'extend'    => Arr::get($inputs, 'extend'),
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
            'status' => Arr::get($inputs, 'status'),
            'extend' => Arr::get($inputs, 'extend'),
        ]);
    }
}
