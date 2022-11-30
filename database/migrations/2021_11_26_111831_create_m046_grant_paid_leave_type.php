<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM046GrantPaidLeaveType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m046_grant_paid_leave_type', function (Blueprint $table) {
            $table->bigIncrements('grant_paid_leave_type_id');
            $table->string('grant_paid_leave_type_name',100)->nullable();
            $table->unsignedinteger('grant_paid_leave_month')->default(0);
            $table->unsignedinteger('grant_paid_leave_day')->default(0);    
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m046_grant_paid_leave_type');
    }
}
