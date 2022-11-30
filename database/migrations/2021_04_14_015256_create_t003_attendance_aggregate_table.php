<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT003AttendanceAggregateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t003_attendance_aggregate', function (Blueprint $table) {
            $table->bigIncrements('attendance_aggregate_id');
            $table->unsignedBigInteger('employee_id')->default(0);
            $table->unsignedInteger('attendance_year_month')->default(0);
            $table->unsignedInteger('scheduled_working_days')->default(0);
            $table->unsignedInteger('actual_working_days')->default(0);
            $table->unsignedInteger('holiday_working_days')->default(0);
            $table->unsignedInteger('actual_working_time')->default(0);
            $table->unsignedInteger('statutory_working_time')->default(0);
            $table->unsignedInteger('non_statutory_working_time')->default(0);
            $table->unsignedInteger('deduction_time')->default(0);
            $table->unsignedInteger('holiday_work_time')->default(0);
            $table->unsignedInteger('midnight_time')->default(0);
            $table->unsignedInteger('over_60hours')->default(0);
            $table->unsignedBigInteger('last_grant_paid_leave_pattern_id')->default(0);
            $table->unsignedInteger('acquired_paid_leave_days')->default(0);
            $table->unsignedInteger('remaining_paid_leave_days')->default(0);
            $table->unsignedInteger('early_leave_late_arrival_days')->default(0);
            $table->unsignedInteger('early_leave_late_arrival_days_absent')->default(0);
            $table->unsignedInteger('special_paid_holiday_days')->default(0);
            $table->unsignedInteger('special_non_paid_holiday_days')->default(0);
            $table->unsignedInteger('accumulated_paid_leave_days')->default(0);
            $table->unsignedInteger('unused_accumulated_paid_leave_days')->default(0);
            $table->unsignedInteger('acquired_substitute_holidays')->default(0);
            $table->unsignedInteger('absent_days')->default(0);
            $table->unsignedInteger('absent_time')->default(0);
            $table->unsignedBigInteger('close_state_id')->default(0);
            $table->unsignedInteger('36agreement_special_provisions_apply_class')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['attendance_aggregate_id','employee_id','attendance_year_month'],'attendance_aggregate_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t003_attendance_aggregate');
    }
}
