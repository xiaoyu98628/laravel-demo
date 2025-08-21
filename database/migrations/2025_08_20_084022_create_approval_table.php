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
            $table->string('name')->comment('标题');
            $table->string('business_type')->comment('业务类型');
            $table->ulid('business_id')->comment('业务id');
            $table->string('status')->default('created')->comment('状态[created:创建,approved:审批中,success:通过,rejected:驳回,cancelled:取消]');
            $table->json('node_template_snapshot')->nullable()->comment('节点模版快照');
            $table->json('callback')->nullable()->comment('回调');
            $table->string('applicant_type')->default('user')->comment('申请人类型[user:用户,admin:管理员]');
            $table->ulid('applicant_id')->comment('申请人id');
            $table->json('extend')->nullable()->comment('额外信息');
            $table->ulid('template_id')->comment('模版id');
            MigrationHelper::createAndAdmin($table);
            $table->index(['business_type', 'business_id']);
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
