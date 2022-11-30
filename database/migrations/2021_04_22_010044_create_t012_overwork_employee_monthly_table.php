<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT012OverworkEmployeeMonthlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t012_overwork_employee_monthly', function (Blueprint $table) {
            $table->bigIncrements('overwork_employee_monthly_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedInteger('target_year_month')->default(0);
            $table->unsignedBigInteger('violation_warning_id')->default(0);
            $table->unsignedInteger('overwork_time')->default(0);
            $table->unsignedInteger('holiday_work_days')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['overwork_employee_monthly_id','employee_id','target_year_month','violation_warning_id'],'overwork_employee_monthly_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t012_overwork_employee_monthly');
    }
}
