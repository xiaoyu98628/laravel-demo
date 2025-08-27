<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ApprovalNode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Service\Common\Base\BaseRepository;

class ApprovalNodeRepositories extends BaseRepository
{
    public function __construct(ApprovalNode $model)
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
            'parent_id'   => Arr::get($inputs, 'parent_id'),
            'depth'       => Arr::get($inputs, 'depth'),
            'name'        => Arr::get($inputs, 'name'),
            'type'        => Arr::get($inputs, 'type'),
            'rules'       => Arr::get($inputs, 'rules'),
            'status'      => Arr::get($inputs, 'status'),
            'callback'    => Arr::get($inputs, 'callback', DB::raw("'{}'")),
            'approval_id' => Arr::get($inputs, 'approval_id'),
            'extend'      => Arr::get($inputs, 'extend'),
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
            'status' => Arr::get($inputs, 'status'),
            'extend' => Arr::get($inputs, 'extend'),
        ]);
    }
}
