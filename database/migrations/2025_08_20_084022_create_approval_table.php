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
        Schema::create('approval', function (Blueprint $table) {
            $table->ulid('id')->primary()->comment('主键');
            $table->ulid('parent_id')->nullable()->comment('父级id');
            $table->string('flow_code')->comment('流程标识');
            $table->string('title')->comment('标题');
            $table->string('business_type')->comment('业务类型[order:订单]');
            $table->ulid('business_id')->comment('业务id');
            $table->enum('status', ['create', 'process', 'success', 'reject', 'cancel'])->comment('状态[create:创建,process:进行中,success:通过,reject:驳回,cancel:取消]');
            $table->json('node_template_snapshot')->nullable()->comment('节点模版快照');
            $table->json('callback')->nullable()->comment('回调');
            $table->string('applicant_type')->default('user')->comment('申请人类型[user:用户,admin:管理员]');
            $table->ulid('applicant_id')->comment('申请人id');
            $table->json('extend')->nullable()->comment('额外信息');
            $table->ulid('template_id')->comment('模版id');
            MigrationHelper::createTime($table);
            $table->index(['business_type', 'business_id']);
            $table->index('status');
            $table->index('flow_code');
            $table->comment('审批实例表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval');
    }
};
