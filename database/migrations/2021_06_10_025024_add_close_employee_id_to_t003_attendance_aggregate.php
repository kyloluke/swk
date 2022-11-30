<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCloseEmployeeIdToT003AttendanceAggregate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            $table->unsignedBigInteger('close_employee_id')->default(0)->after('close_state_id');
            $table->unsignedBigInteger('close_manager_employee_id')->default(0)->after('close_employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            $table->dropColumn('close_employee_id');
            $table->dropColumn('close_manager_employee_id');
        });
    }
}
