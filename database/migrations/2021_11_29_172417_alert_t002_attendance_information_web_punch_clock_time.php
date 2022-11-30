<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertT002AttendanceInformationWebPunchClockTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t002_attendance_information', function($table)
        {
            $table->unsignedInteger('web_punch_clock_time_start')->nullable()->change();
            $table->unsignedInteger('web_punch_clock_time_end')->nullable()->change();
        });

        DB::table('t002_attendance_information')
            ->where('web_punch_clock_time_start', 0)
            ->update(['web_punch_clock_time_start' => null]);
        DB::table('t002_attendance_information')
            ->where('web_punch_clock_time_end', 0)
            ->update(['web_punch_clock_time_end' => null]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('t002_attendance_information')
            ->where('web_punch_clock_time_start', null)
            ->update(['web_punch_clock_time_start' => 0]);
        DB::table('t002_attendance_information')
            ->where('web_punch_clock_time_end', null)
            ->update(['web_punch_clock_time_end' => 0]);
        
        Schema::table('t002_attendance_information', function($table)
        {
            $table->unsignedInteger('web_punch_clock_time_start')->default(0)->change();
            $table->unsignedInteger('web_punch_clock_time_end')->default(0)->change();
        });
    }
}
