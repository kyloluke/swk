<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexT002AttendanceInformationAttendanceDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t002_attendance_information', function (Blueprint $table) {
            $table->index('employee_id');
            $table->index('attendance_date');
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
            $table->dropIndex(['employee_id']);
            $table->dropIndex(['attendance_date']);
        });
    }
}
