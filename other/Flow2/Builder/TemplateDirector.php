<?php

declare(strict_types=1);

namespace other\Flow2\Builder;

// 示例：项目审批模板，包含子流程（财务）
final class TemplateDirector
{
    public static function projectWithFinanceSubflow(): array
    {
        // 组合树形定义（可根据你的 FlowNodeTemplate 真实数据构建）
        return [
            'type'     => 'start',
            'name'     => '开始',
            'children' => [
                [
                    'type'  => 'approval',
                    'name'  => '项目负责人审批',
                    'rules' => [
                        'mode'      => 'anysign',
                        'approvers' => [
                            ['id' => 'u1', 'name' => '负责人A', 'type' => 'user'],
                        ],
                    ],
                    'children' => [
                        [
                            'type'  => 'subflow',
                            'name'  => '财务子流程',
                            'rules' => [
                                'subflow_template_id' => 'FINANCE_TEMPLATE_ID', // 实际应为真实模板ID
                            ],
                            'children' => [
                                [
                                    'type' => 'end',
                                    'name' => '结束',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function financeSimple(): array
    {
        return [
            'type'     => 'start',
            'name'     => '开始',
            'children' => [
                [
                    'type'  => 'approval',
                    'name'  => '财务审批',
                    'rules' => [
                        'mode'      => 'countersign',
                        'approvers' => [
                            ['id' => 'f1', 'name' => '财务A', 'type' => 'user'],
                            ['id' => 'f2', 'name' => '财务B', 'type' => 'user'],
                        ],
                    ],
                    'children' => [
                        [
                            'type'  => 'cc',
                            'name'  => '财务抄送',
                            'rules' => [
                                'cc_list' => [
                                    ['id' => 'm1', 'name' => '经理', 'type' => 'user'],
                                ],
                            ],
                            'children' => [
                                ['type' => 'end', 'name' => '结束'],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
