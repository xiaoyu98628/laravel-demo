<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FlowNodeTemplate;
use Illuminate\Support\Arr;
use Service\Common\Base\BaseRepository;

class FlowNodeTemplateRepositories extends BaseRepository
{
    public function __construct(FlowNodeTemplate $model)
    {
        $this->model = $model;
    }

    /**
     * 创建
     * @param  array  $inputs
     * @return FlowNodeTemplate
     * @throws \Exception
     */
    public function store(array $inputs): FlowNodeTemplate
    {
        if (empty($inputs)) {
            throw new \Exception('参数错误');
        }

        return $this->query()->create([
            'parent_id'        => Arr::get($inputs, 'parent_id'),
            'depth'            => Arr::get($inputs, 'depth'),
            'priority'         => Arr::get($inputs, 'priority'),
            'name'             => Arr::get($inputs, 'name'),
            'description'      => Arr::get($inputs, 'description'),
            'type'             => Arr::get($inputs, 'type'),
            'rules'            => empty($inputs['rules']) ? null : $inputs['rules'],
            'callback'         => empty($inputs['callback']) ? null : $inputs['callback'],
            'flow_template_id' => Arr::get($inputs, 'flow_template_id'),
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
            'parent_id'        => Arr::get($inputs, 'parent_id'),
            'depth'            => Arr::get($inputs, 'depth'),
            'priority'         => Arr::get($inputs, 'priority'),
            'name'             => Arr::get($inputs, 'name'),
            'description'      => Arr::get($inputs, 'description'),
            'type'             => Arr::get($inputs, 'type'),
            'rules'            => empty($inputs['rules']) ? null : $inputs['rules'],
            'callback'         => empty($inputs['callback']) ? null : $inputs['callback'],
            'flow_template_id' => Arr::get($inputs, 'flow_template_id'),
        ]);
    }

    /**
     * 根据flowTemplateId获取ID
     * @param  string  $flowTemplateId
     * @return array
     */
    public function findIdByFlowTemplateId(string $flowTemplateId): array
    {
        return $this->query()->where('flow_template_id', $flowTemplateId)->pluck('id')->toArray();
    }
}
