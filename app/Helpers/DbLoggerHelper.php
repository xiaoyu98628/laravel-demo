<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Service\Common\Library\Logging\Enum\LogChannel;

class DbLoggerHelper
{
    public static function enable(): void
    {
        // 仅在本地环境启用
        if (! app()->environment('local')) {
            return;
        }

        DB::listen(function ($query) {
            $sql  = self::formatSql($query->sql, $query->bindings);
            $time = number_format($query->time, 2);

            $log = <<<LOG
[SQL LOG]
SQL: {$sql}
Time: {$time} ms
------------------------------------------------------------
LOG;
            Log::channel(LogChannel::LOG_DB->value)->info($log);
        });
    }

    /**
     * 格式化 SQL 语句并绑定参数
     */
    private static function formatSql(string $sql, array $bindings): string
    {
        $formattedBindings = array_map(function ($binding) {
            if (is_null($binding)) {
                return 'NULL';
            }

            if (is_bool($binding)) {
                return $binding ? 'TRUE' : 'FALSE';
            }

            if (is_numeric($binding)) {
                return $binding;
            }

            return "'".addslashes((string) $binding)."'";
        }, $bindings);

        return vsprintf(str_replace('?', '%s', $sql), $formattedBindings);
    }
}
