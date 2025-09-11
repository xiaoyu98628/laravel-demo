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
        Schema::create('flow_node', function (Blueprint $table) {
            $table->ulid('id')->primary()->comment('编号');
            $table->ulid('parent_id')->nullable()->comment('父级id');
            $table->unsignedTinyInteger('depth')->default(1)->comment('步骤');
            $table->string('name')->comment('节点名称');
            $table->enum('type', ['start', 'condition', 'condition_route', 'approval', 'cc', 'subflow', 'end'])->comment('类型[start:开始节点,condition:条件节点,condition_route:条件路由节点,approval:审核节点,cc:抄送节点,subflow:子流程节点,end:结束节点]');
            $table->json('rules')->comment('审批规则');
            $table->enum('status', ['process', 'approve', 'reject', 'skip', 'auto', 'cancel'])->comment('状态[process:审批中,approve:通过,reject:驳回,skip:跳过,auto:自动,cancel:取消]');
            $table->json('callback')->nullable()->comment('回调');
            $table->ulid('flow_id')->comment('审批id');
            $table->json('extend')->nullable()->comment('额外信息');
            MigrationHelper::createTime($table);
            $table->index('parent_id');
            $table->index('flow_id');
            $table->comment('审批节点实例表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_node');
    }
};
