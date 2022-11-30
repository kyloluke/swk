<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeT002AttendanceInformationTable2Columns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t002_attendance_information', function (Blueprint $table) {
            $table->unsignedInteger('web_punch_clock_time_start')->default(null)->change();
            $table->unsignedInteger('web_punch_clock_time_end')->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t002_attendance_information', function (Blueprint $table) {
            $table->unsignedInteger('web_punch_clock_time_start')->default(0)->change();
            $table->unsignedInteger('web_punch_clock_time_end')->default(0)->change();
        });
    }
}
