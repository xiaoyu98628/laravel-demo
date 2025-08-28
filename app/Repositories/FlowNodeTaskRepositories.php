<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FlowNodeTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
     */
    public function store(array $inputs): Model
    {
        return $this->query()->create([
            'approver_id'      => Arr::get($inputs, 'approver_id'),
            'approver_name'    => Arr::get($inputs, 'approver_name'),
            'approver_type'    => Arr::get($inputs, 'approver_type'),
            'operation_info'   => Arr::get($inputs, 'operation_info', DB::raw("'{}'")),
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
     */
    public function update(string $id, array $inputs): int
    {
        return $this->query()->where('id', $id)->update([
            'operation_info' => Arr::get($inputs, 'operation_info', DB::raw("'{}'")),
            'status'         => Arr::get($inputs, 'status'),
            'extend'         => Arr::get($inputs, 'extend'),
        ]);
    }
}
