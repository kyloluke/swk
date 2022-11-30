<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT009HolidayManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t009_holiday_management', function (Blueprint $table) {
            $table->bigIncrements('holiday_management_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedInteger('grant_date')->default(0);
            $table->unsignedInteger('next_grant_paid_leave_date')->default(0);
            $table->unsignedInteger('holiday_management_class')->default(0);
            $table->unsignedInteger('grant_holiday_days')->default(0);
            $table->unsignedInteger('acquired_holiday_days')->default(0);
            $table->unsignedInteger('acquired_holiday_half_days')->default(0);
            $table->unsignedInteger('acquired_holiday_time')->default(0);
            $table->unsignedInteger('remaining_holiday_days')->default(0);
            $table->unsignedInteger('remaining_holiday_time')->default(0);
            $table->unsignedInteger('valid_date_start')->default(0);
            $table->unsignedInteger('varid_date_end')->default(0);
            $table->unsignedInteger('accumulated_paid_leave_transition_class')->default(0);
            $table->unsignedInteger('priority_use_class')->default(0);
            $table->unsignedInteger('obligatory_take_paid_leave_days')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['holiday_management_id','employee_id','grant_date'],'holiday_management_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t009_holiday_management');
    }
}
