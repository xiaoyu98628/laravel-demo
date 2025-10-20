<?php

namespace App\Flow\Strategies\Flow\Types;

use App\Constants\Enums\FlowTemplate\Status;
use App\Repositories\FlowTemplateRepositories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class TypeStrategy
{
    protected Model $template;

    public function __construct(
        private readonly FlowTemplateRepositories $flowTemplateRepositories
    ) {}

    /**
     * 选择模板
     * @param  array  $inputs
     * @return Model
     */
    public function selectTemplate(array $inputs): Model
    {
        $this->template = $this->flowTemplateRepositories->query()
            ->where('type', static::$type)
            ->where('status', Status::ENABLE->value)
            ->first();
        if (empty($template)) {
            throw new ModelNotFoundException('未找到启用的财务审批模板');
        }

        return $this->template;
    }
}
