<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FlowNodeTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
     * @return Model
     * @throws \Exception
     */
    public function store(array $inputs): Model
    {
        if (empty($inputs)) {
            throw new \Exception('参数错误');
        }

        return $this->query()->create([
            'parent_id'        => Arr::get($inputs, 'parent_id'),
            'depth'            => Arr::get($inputs, 'depth'),
            'name'             => Arr::get($inputs, 'name'),
            'description'      => Arr::get($inputs, 'description'),
            'type'             => Arr::get($inputs, 'type'),
            'rules'            => Arr::get($inputs, 'rules', DB::raw("'{}'")),
            'callback'         => Arr::get($inputs, 'callback', DB::raw("'{}'")),
            'flow_template_id' => Arr::get($inputs, 'flow_template_id'),
        ]);
    }

    /**
     * 更新
     * @param  string  $id
     * @param  array  $data
     * @return int
     * @throws \Exception
     */
    public function update(string $id, array $data): int
    {
        if (empty($inputs)) {
            throw new \Exception('参数错误');
        }

        return $this->query()->where('id', $id)->update([
            'parent_id'        => Arr::get($data, 'parent_id'),
            'depth'            => Arr::get($data, 'depth'),
            'name'             => Arr::get($data, 'name'),
            'description'      => Arr::get($data, 'description'),
            'type'             => Arr::get($data, 'type'),
            'rules'            => Arr::get($data, 'rules', DB::raw("'{}'")),
            'callback'         => Arr::get($data, 'callback', DB::raw("'{}'")),
            'flow_template_id' => Arr::get($data, 'flow_template_id'),
        ]);
    }
}
