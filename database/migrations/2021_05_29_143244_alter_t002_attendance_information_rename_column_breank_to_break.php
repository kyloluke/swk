<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT002AttendanceInformationRenameColumnBreankToBreak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t002_attendance_information', function (Blueprint $table) {
            $table->renameColumn('breank_time', 'break_time');
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
            $table->renameColumn('break_time', 'breank_time');//<-記述
        });
    }
}
