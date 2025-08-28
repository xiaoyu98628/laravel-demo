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
        Schema::create('flow_node_task', function (Blueprint $table) {
            $table->ulid('id')->primary()->comment('编号');
            $table->ulid('approver_id')->comment('审批人id');
            $table->string('approver_name')->comment('审批人名称');
            $table->string('approver_type')->comment('审批人类型');
            $table->json('operation_info')->nullable()->comment('操作信息');
            $table->enum('status', ['process', 'approve', 'reject', 'skip', 'auto', 'cancel'])->comment('状态[process:审批中,approve:通过,reject:驳回,skip:跳过,auto:自动,cancel:取消]');
            $table->ulid('flow_node_id')->comment('审批节点id');
            $table->json('extend')->nullable()->comment('额外信息');
            MigrationHelper::createAndAdmin($table);
            $table->comment('流程节点任务实例表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_node_task');
    }
};
