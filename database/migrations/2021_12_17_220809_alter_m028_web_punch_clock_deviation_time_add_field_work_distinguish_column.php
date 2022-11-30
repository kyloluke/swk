<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM028WebPunchClockDeviationTimeAddFieldWorkDistinguishColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m028_web_punch_clock_deviation_time', function(Blueprint $table) {
            $table->unsignedTinyInteger('field_work_distinguish')->default(0)->after('detail_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->dropColumn('field_work_distinguish');
        });
    }
}
