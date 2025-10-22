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
        Schema::create('flow_node_template', function (Blueprint $table) {
            $table->ulid('id')->primary()->comment('主键');
            $table->ulid('parent_id')->nullable()->comment('父级id');
            $table->unsignedTinyInteger('depth')->default(1)->comment('步骤');
            $table->unsignedTinyInteger('priority')->default(0)->comment('优先等级[数字越大优先级越高]');
            $table->string('name')->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->enum('type', ['start', 'condition', 'condition_route', 'approval', 'cc', 'subflow', 'end'])->comment('类型[start:开始节点,condition:条件节点,condition_route:条件路由节点,approval:审核节点,cc:抄送节点,subflow:子流程节点,end:结束节点]');
            $table->json('rules')->nullable()->comment('审批规则');
            $table->json('callback')->nullable()->comment('回调');
            $table->ulid('flow_template_id')->comment('流程模版id');
            MigrationHelper::createAndAdmin($table);
            $table->index('parent_id');
            $table->index('flow_template_id');
            $table->comment('审批节点模版表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_node_template');
    }
};
