<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM007EmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m007_employee', function (Blueprint $table) {
            $table->bigIncrements('employee_id');
            $table->string('employee_code',20);
            $table->unsignedBigInteger('company_id')->default(0);
            $table->unsignedBigInteger('office_id')->default(0);
            $table->unsignedBigInteger('dept_id')->default(0);
            $table->unsignedBigInteger('work_closing_belonging_office_id');
            $table->unsignedBigInteger('post_id')->default(0);
            $table->string('employee_name',100)->nullable();
            $table->string('employee_kana_name',100)->nullable();
            $table->string('gender',10)->nullable();
            $table->Integer('joined_company_date');
            $table->Integer('retirement_company_date');
            $table->unsignedBigInteger('calendar_id')->default(0);
            $table->unsignedBigInteger('personal_calendar_class')->default(0);
            $table->unsignedBigInteger('work_zone_class')->default(0);
            $table->unsignedInteger('week_scheduled_working_days')->default(0);
            $table->Integer('scheduled_working_hours');
            $table->Integer('overtime_base_time');
            $table->Integer('available_input_class');
            $table->unsignedBigInteger('36_agreement_id')->nullable();
            $table->unsignedBigInteger('employment_status_id')->default(0);
            $table->Integer('closing_date_class');
            $table->unsignedBigInteger('paid_take_grant_days_pattern_id')->default(0);
            $table->unsignedBigInteger('authority_pattern_id')->default(0);
            $table->Integer('first_year_paid_take_date');
            $table->Integer('stamping_target_class');
            $table->string('email_address',100)->nullable();
            $table->string('stamping_password',100)->nullable();
            $table->Integer('grant_starting_date');
            $table->Integer('work_management_target_class');
            $table->unsignedInteger('valid_date_start')->default(0);
            $table->unsignedInteger('valid_date_end')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('create_user',20)->default("0");
            $table->timestamp('created_at')->useCurrent();
            $table->string('update_user',20)->default("0");
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
        Schema::dropIfExists('m007_employee');
    }
}
