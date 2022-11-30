<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM028WebPunchClockDeviationTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->bigIncrements('web_punch_clock_deviation_time_id');
            $table->unsignedBigInteger('clocking_in_out_id')->default(0);
            $table->unsignedInteger('allow_before_start_time')->default(0);
            $table->unsignedInteger('allow_after_end_time')->default(0);
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
        Schema::dropIfExists('m028_web_punch_clock_deviation_time');
    }
}
