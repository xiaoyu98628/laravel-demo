<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ApprovalNodeTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Service\Common\Base\BaseRepository;

class ApprovalNodeTemplateRepositories extends BaseRepository
{
    public function __construct(ApprovalNodeTemplate $model)
    {
        $this->model = $model;
    }

    /**
     * 创建
     * @param  array  $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->query()->create([
            'parent_id'   => '',
            'step_order'  => '',
            'name'        => '',
            'description' => '',
            'type'        => '',
            'rules'       => '',
            'callback'    => Arr::get($data, 'callback', DB::raw("'{}'")),
            'template_id' => '',
        ]);
    }

    public function update(string $id, array $data): int
    {
        return $this->query()->where('id', $id)->update([
            'parent_id'   => '',
            'step_order'  => '',
            'name'        => '',
            'description' => '',
            'type'        => '',
            'rules'       => '',
            'callback'    => Arr::get($data, 'callback', DB::raw("'{}'")),
            'template_id' => '',
        ]);
    }
}
