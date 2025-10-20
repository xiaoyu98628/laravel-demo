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
        Schema::create('flow', function (Blueprint $table) {
            $table->ulid('id')->primary()->comment('主键');
            $table->string('title')->comment('标题');
            $table->enum('business_type', ['partner', 'publisher', 'finance', 'execution', 'workflow', 'project'])->comment('类型[partner:合作者审批,publisher:发布者审批,finance:财务审批,execution:执行流审批,workflow:工作流审批,project:项目审批]');
            $table->ulid('business_id')->comment('业务id');
            $table->ulid('parent_flow_id')->nullable()->comment('父级流程id');
            $table->ulid('parent_node_id')->nullable()->comment('父级节点id');
            $table->enum('level', ['main', 'subflow'])->comment('层级[main:主流程,subflow:子流程]');
            $table->json('business_snapshot')->nullable()->comment('业务快照');
            $table->enum('status', ['create', 'process', 'waiting', 'success', 'reject', 'cancel'])->comment('状态[create:创建,process:进行中,waiting:等待,success:通过,reject:驳回,cancel:取消]');
            $table->json('flow_node_template_snapshot')->nullable()->comment('流程节点模版快照');
            $table->json('callback')->nullable()->comment('回调');
            $table->enum('applicant_type', ['user', 'admin'])->default('user')->comment('申请人类型[user:用户,admin:管理员]');
            $table->ulid('applicant_id')->comment('申请人id');
            $table->json('extend')->nullable()->comment('额外信息');
            $table->ulid('flow_template_id')->comment('流程模版id');
            MigrationHelper::createTime($table);
            $table->index(['business_type', 'business_id']);
            $table->index('status');
            $table->index('business_type');
            $table->comment('审批实例表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_flow');
    }
};
