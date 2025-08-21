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
        Schema::create('approval_node_template', function (Blueprint $table) {
            $table->ulid('id')->primary()->comment('主键');
            $table->ulid('parent_id')->nullable()->comment('父级id');
            $table->unsignedTinyInteger('step_order')->default(1)->comment('步骤');
            $table->string('name')->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->enum('type', ['condition', 'approval', 'cc'])->comment('类型[condition:条件节点,approval:审核节点,cc:抄送节点]');
            $table->json('rules')->nullable()->comment('审批规则');
            $table->json('callback')->nullable()->comment('回调');
            $table->ulid('template_id')->comment('模版id');
            MigrationHelper::createAndAdmin($table);
            $table->index('parent_id');
            $table->index('template_id');
            $table->comment('审批节点模版表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_node_template');
    }
};
