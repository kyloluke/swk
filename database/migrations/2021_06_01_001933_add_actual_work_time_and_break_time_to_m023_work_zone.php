<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActualWorkTimeAndBreakTimeToM023WorkZone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->string('actual_work_time')->nullable();
            $table->string('break_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->dropColumn(['actual_work_time',  'break_time']);
        });
    }
}
