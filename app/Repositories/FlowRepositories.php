<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Flow;
use Illuminate\Support\Arr;
use Service\Common\Base\BaseRepository;

class FlowRepositories extends BaseRepository
{
    public function __construct(Flow $model)
    {
        $this->model = $model;
    }

    /**
     * 创建
     * @param  array  $inputs
     * @return Flow
     * @throws \Exception
     */
    public function store(array $inputs): Flow
    {
        if (empty($inputs)) {
            throw new \Exception('参数错误');
        }

        return $this->query()->create([
            'title'                       => Arr::get($inputs, 'title'),
            'business_type'               => Arr::get($inputs, 'business_type'),
            'business_id'                 => Arr::get($inputs, 'business_id'),
            'parent_flow_id'              => Arr::get($inputs, 'parent_flow_id'),
            'parent_node_id'              => Arr::get($inputs, 'parent_node_id'),
            'level'                       => Arr::get($inputs, 'level'),
            'business_snapshot'           => Arr::get($inputs, 'business_snapshot'),
            'status'                      => Arr::get($inputs, 'status'),
            'flow_node_template_snapshot' => Arr::get($inputs, 'flow_node_template_snapshot'),
            'callback'                    => empty($inputs['callback']) ? null : $inputs['callback'],
            'applicant_type'              => Arr::get($inputs, 'applicant_type'),
            'applicant_id'                => Arr::get($inputs, 'applicant_id'),
            'extend'                      => Arr::get($inputs, 'extend'),
            'flow_template_id'            => Arr::get($inputs, 'flow_template_id'),
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
            'status'                      => Arr::get($inputs, 'status'),
            'flow_node_template_snapshot' => Arr::get($inputs, 'flow_node_template_snapshot'),
            'extend'                      => Arr::get($inputs, 'extend'),
        ]);
    }
}
