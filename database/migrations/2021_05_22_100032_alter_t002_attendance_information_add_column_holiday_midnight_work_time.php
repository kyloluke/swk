<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT002AttendanceInformationAddColumnHolidayMidnightWorkTime extends Migration
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
            $table->unsignedBigInteger('holiday_midnight_work_time')->default(0)->after('holiday_work_time');
            $table->unsignedBigInteger('work_zone_time_end')->default(0)->after('work_zone_id');
            $table->unsignedBigInteger('work_zone_time_start')->default(0)->after('work_zone_id');
            $table->unsignedBigInteger('holiday_midnight_work_break_time')->default(0)->after('midnight_time');
            $table->unsignedBigInteger('holiday_work_break_time')->default(0)->after('midnight_time');
            $table->unsignedBigInteger('midnight_break_time')->default(0)->after('midnight_time');
            $table->unsignedBigInteger('breank_time')->default(0)->after('midnight_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t002_attendance_information', function($table)
        {
            $table->dropColumn('holiday_midnight_work_time');
            $table->dropColumn('work_zone_time_end');
            $table->dropColumn('work_zone_time_start');
            $table->dropColumn('breank_time');
            $table->dropColumn('midnight_break_time');
            $table->dropColumn('holiday_work_break_time');
            $table->dropColumn('holiday_midnight_work_break_time');
        });
    }
}
