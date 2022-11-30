<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCloseStateIdToT002AttendanceInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t002_attendance_information', function (Blueprint $table) {
            $table->unsignedBigInteger('close_state_id')->default(0);
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
            $table->dropColumn('close_state_id');
        });
    }
}
