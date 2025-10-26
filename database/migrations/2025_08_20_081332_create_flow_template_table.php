<?php

use App\Helpers\MigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flow_template', function (Blueprint $table) {
            $table->ulid('id')->primary()->comment('主键');
            $table->enum('type', ['general'])->comment('类型[general:通用审批]');
            $table->string('code', 50)->comment('标识');
            $table->string('name', 50)->comment('名称');
            $table->json('callback')->nullable()->comment('回调');
            $table->string('remark')->nullable()->comment('备注');
            $table->enum('status', ['enable', 'disable'])->default('enable')->comment('状态[enable:启用,disable:禁用]');
            MigrationHelper::createAndAdmin($table);
            $table->index('code');
            $table->comment('审批流程模版表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_template');
    }
};
