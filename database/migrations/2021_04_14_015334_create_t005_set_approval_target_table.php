<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT005SetApprovalTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t005_set_approval_target', function (Blueprint $table) {
            $table->bigIncrements('set_approval_target_id');
            $table->unsignedBigInteger('approver_id')->default(0);    
            $table->unsignedBigInteger('approved_person_id')->default(0);
            $table->unsignedInteger('valid_date_start')->default(0);
            $table->unsignedInteger('varid_date_end')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['set_approval_target_id','approver_id','approved_person_id'],'set_approval_target_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t005_set_approval_target');
    }
}
