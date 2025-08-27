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
            $table->enum('flow_code', ['partner', 'publisher', 'finance', 'execution', 'workflow', 'project'])->comment('流程标识[partner:合作者审批,publisher:发布者审批,finance:财务审批,execution:执行流审批,workflow:工作流审批,project:项目审批]');
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
