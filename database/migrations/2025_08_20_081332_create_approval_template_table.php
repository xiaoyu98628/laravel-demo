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
        Schema::create('approval_template', function (Blueprint $table) {
            $table->ulid('id')->primary()->comment('主键');
            $table->string('flow_code')->comment('流程标识');
            $table->string('name')->comment('名称');
            $table->json('callback')->nullable()->comment('回调');
            $table->string('remark')->nullable()->comment('备注');
            $table->enum('status', ['enable', 'disable'])->default('enable')->comment('状态[enable:启用,disable:禁用]');
            MigrationHelper::createAndAdmin($table);
            $table->index('flow_code');
            $table->comment('审批模版表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_template');
    }
};
