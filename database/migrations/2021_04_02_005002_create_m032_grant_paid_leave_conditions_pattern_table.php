<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM032GrantPaidLeaveConditionsPatternTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->bigIncrements('grant_paid_leave_conditions_pattern_id');
            $table->unsignedinteger('attendance_rate_from')->default(0);
            $table->unsignedinteger('attendance_rate_to')->default(0);
            $table->unsignedinteger('round_down_class')->default(0);
            $table->unsignedinteger('grant_rate')->default(0);
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
        Schema::dropIfExists('m032_grant_paid_leave_conditions_pattern');
    }
}
