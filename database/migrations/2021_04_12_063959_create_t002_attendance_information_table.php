<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT002AttendanceInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t002_attendance_information', function (Blueprint $table) {
            $table->bigIncrements('attendance_information_id');
            $table->unsignedBigInteger('employee_id')->default(0);
            $table->unsignedInteger('attendance_date')->default(0);
            $table->unsignedInteger('violation_warning_class')->default(0);
            $table->unsignedBigInteger('work_holiday_id')->default(0);
            $table->unsignedBigInteger('approval_state_id')->default(0);
            $table->unsignedBigInteger('work_achievement_id')->default(0);
            $table->unsignedBigInteger('work_zone_id')->default(0);
            $table->unsignedInteger('work_time_start')->default(0);
            $table->unsignedInteger('work_time_end')->default(0);
            $table->unsignedInteger('actual_work_time')->default(0);
            $table->unsignedInteger('web_punch_clock_time_start')->default(0);
            $table->unsignedInteger('web_punch_clock_time_end')->default(0);
            $table->unsignedInteger('other_system_time_start')->default(0);
            $table->unsignedInteger('other_system_time_end')->default(0);
            $table->unsignedInteger('statutory_working_time')->default(0);
            $table->unsignedInteger('non_statutory_working_time')->default(0);
            $table->unsignedInteger('midnight_time')->default(0);
            $table->unsignedInteger('deduction_time')->default(0);
            $table->unsignedInteger('unemployed_time')->default(0);
            $table->unsignedInteger('holiday_work_time')->default(0);
            $table->unsignedInteger('absent_time')->default(0);
            $table->text('substitute_holiday_reason');
            $table->text('information');
            $table->text('remand_reason');
            $table->unsignedInteger('approval_request_date')->default(0);
            $table->unsignedBigInteger('input_employee_id')->default(0);
            $table->unsignedBigInteger('approval_employee_id')->default(0);
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
        Schema::dropIfExists('t002_attendance_information');
    }
}
