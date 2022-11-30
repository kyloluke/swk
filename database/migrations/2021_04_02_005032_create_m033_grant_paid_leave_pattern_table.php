<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM033GrantPaidLeavePatternTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m033_grant_paid_leave_pattern', function (Blueprint $table) {
            $table->bigIncrements('grant_paid_leave_pattern_id');
            $table->unsignedinteger('prescribed_week_days')->default(0);
            $table->unsignedinteger('service_length_from')->default(0);
            $table->unsignedinteger('service_length_to')->default(0);
            $table->unsignedinteger('grant_paid_leave_days')->default(0);
            $table->unsignedinteger('obligatory_take_paid_leave_days')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m033_grant_paid_leave_pattern');
    }
}
