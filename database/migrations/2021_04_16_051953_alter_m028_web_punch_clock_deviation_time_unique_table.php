<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM028WebPunchClockDeviationTimeUniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->unique(['web_punch_clock_deviation_time_id','clocking_in_out_id'],'web_punch_clock_deviation_time_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
