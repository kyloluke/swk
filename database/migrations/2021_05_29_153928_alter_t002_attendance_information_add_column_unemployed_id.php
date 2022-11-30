<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT002AttendanceInformationAddColumnUnemployedId extends Migration
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
            $table->unsignedBigInteger('unemployed_id')->default(0)->after('work_zone_id');
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
            $table->dropColumn('unemployed_id');
        });
    }
}
