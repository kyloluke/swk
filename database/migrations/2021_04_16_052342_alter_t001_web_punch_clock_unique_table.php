<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT001WebPunchClockUniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t001_web_punch_clock', function (Blueprint $table) {
            $table->unique(['web_punch_clock_id','employee_id','punch_clock_date','clocking_in_out_id'],'web_punch_clock_id');
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
