<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT011HolidayWorkerInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t011_holiday_worker_information', function (Blueprint $table) {
            $table->bigIncrements('holiday_worker_information_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedInteger('holiday_work_date')->default(0);
            $table->unsignedBigInteger('work_achievement_id')->default(0);
            $table->unsignedInteger('achievement_time')->default(0);
            $table->unsignedInteger('holiday_work_reason')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique('holiday_worker_information_id','holiday_worker_information_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t011_holiday_worker_information');
    }
}
