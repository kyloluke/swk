<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT002AttendanceInformationUniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t002_attendance_information', function (Blueprint $table) {
            $table->unique(['attendance_information_id','employee_id','attendance_date'],'attendance_information_id');
            $table->dropColumn('violation_warning_class');
            $table->unsignedBigInteger('violation_warning_id')->default(0);
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
            $table->dropColumn('violation_warning_id');
            $table->unsignedInteger('violation_warning_class')->default(0);
        });
    }
}
