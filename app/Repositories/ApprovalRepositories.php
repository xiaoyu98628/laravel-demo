<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Approval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Service\Common\Base\BaseRepository;

class ApprovalRepositories extends BaseRepository
{
    public function __construct(Approval $model)
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
            'parent_id'              => Arr::get($inputs, 'parent_id'),
            'flow_code'              => Arr::get($inputs, 'flow_code'),
            'title'                  => Arr::get($inputs, 'title'),
            'business_type'          => Arr::get($inputs, 'business_type'),
            'business_id'            => Arr::get($inputs, 'business_id'),
            'status'                 => Arr::get($inputs, 'status'),
            'node_template_snapshot' => Arr::get($inputs, 'node_template_snapshot'),
            'callback'               => Arr::get($inputs, 'callback', DB::raw("'{}'")),
            'applicant_type'         => Arr::get($inputs, 'applicant_type'),
            'applicant_id'           => Arr::get($inputs, 'applicant_id'),
            'extend'                 => Arr::get($inputs, 'extend'),
            'template_id'            => Arr::get($inputs, 'template_id'),
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
            'status'                 => Arr::get($inputs, 'status'),
            'node_template_snapshot' => Arr::get($inputs, 'node_template_snapshot'),
            'extend'                 => Arr::get($inputs, 'extend'),
        ]);
    }
}
