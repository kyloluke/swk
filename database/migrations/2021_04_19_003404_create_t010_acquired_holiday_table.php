<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT010AcquiredHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t010_acquired_holiday', function (Blueprint $table) {
            $table->bigIncrements('acquired_holiday_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedInteger('acquired_holiday_date')->default(0);
            $table->unsignedBigInteger('unemployed_id')->default(0);
            $table->unsignedInteger('acquired_holiday_unit')->default(0);
            $table->unsignedInteger('acquired_holiday_days')->default(0);
            $table->unsignedInteger('acquired_holiday_half_days')->default(0);
            $table->unsignedInteger('acquired_holiday_time')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['acquired_holiday_id','employee_id','acquired_holiday_date'],'acquired_holiday_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t010_acquired_holiday');
    }
}
