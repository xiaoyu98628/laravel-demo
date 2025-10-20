<?php

return [
    App\Providers\AppServiceProvider::class,

    Service\Common\Library\Response\ApiResponseProvider::class, // API返回
    Service\Common\Library\Annotations\Provider\ContractBindServiceProvider::class, // 注册契约绑定注解
    Service\Common\Library\Rpc\RpcServiceProvider::class, // 注册远程过程调用RPC
    Service\Common\Library\Driver\HttpModelServiceProvider::class, // 注册http模型
    Service\Common\Library\Tools\ToolsResponseProvider::class, // 注册工具包
];
