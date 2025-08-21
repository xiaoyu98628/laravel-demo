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
        Schema::create('approval_node', function (Blueprint $table) {
            $table->ulid('id')->primary()->comment('编号');
            $table->ulid('parent_id')->nullable()->comment('父级id');
            $table->string('type')->default('approver')->comment('类型[condition:条件,approver:审核人,copy_people:抄送人]');
            $table->smallInteger('step_number')->default(1)->comment('步骤编号');
            $table->string('step_name')->comment('步骤名称');
            $table->string('status')->default('created')->comment('状态[approved:审批中,success:通过,rejected:驳回,cancelled:取消]');

            $table->json('callback')->nullable()->comment('回调');
            $table->uuid('approval_id')->comment('审批id');
            MigrationHelper::createAndAdmin($table);
            $table->comment('审批节点实例表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_node');
    }
};
