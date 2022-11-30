<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT017DailyReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t017_daily_report', function (Blueprint $table) {
            $table->bigIncrements('daily_report_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedInteger('work_date')->default(0);
            $table->unsignedInteger('work_no')->default(0);
            $table->unsignedInteger('work_time_start')->default(0);
            $table->unsignedInteger('work_time_end')->default(0);
            $table->string('work_item_name',100)->nullable();
            $table->text('work_content');
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['daily_report_id','employee_id','work_date'],'daily_report_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t017_daily_report');
    }
}
