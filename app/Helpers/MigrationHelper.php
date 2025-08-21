<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Database\Schema\Blueprint;

class MigrationHelper
{
    /**
     * @param Blueprint $table
     * @return void
     */
    public static function createAndAdmin(Blueprint $table): void
    {
        $table->dateTime('created_at')->index()->nullable()->comment('创建时间');
        $table->ulid('created_admin_id')->nullable()->comment('创建者编号');
        $table->dateTime('updated_at')->nullable()->comment('更新时间');
        $table->ulid('updated_admin_id')->nullable()->comment('更新者编号');
        $table->dateTime('deleted_at')->nullable()->comment('删除时间');
        $table->ulid('deleted_admin_id')->nullable()->comment('删除者编号');
    }

    /**
     * @param Blueprint $table
     * @return void
     */
    public static function createTime(Blueprint $table): void
    {
        $table->dateTime('created_at')->index()->nullable()->comment('创建时间');
        $table->dateTime('updated_at')->nullable()->comment('更新时间');
        $table->dateTime('deleted_at')->nullable()->comment('删除时间');
    }
}
