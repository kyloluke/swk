<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT013OverworkEmployeeYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t013_overwork_employee_year', function (Blueprint $table) {
            $table->bigIncrements('overwork_employee_year_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedInteger('target_year')->default(0);
            $table->unsignedBigInteger('violation_warning_id')->default(0);
            $table->unsignedInteger('overwork_time')->default(0);
            $table->unsignedInteger('holiday_work_days')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['overwork_employee_year_id','employee_id','target_year','violation_warning_id'],'overwork_employee_year_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t013_overwork_employee_year');
    }
}
