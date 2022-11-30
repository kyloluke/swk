<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexT003AttendanceAggregate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            $table->index(['employee_id', 'attendance_year_month'], 't003_ea_index');
            $table->index('attendance_year_month', 't003_a_index');
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
            $table->dropIndex('t003_ea_index');
            $table->dropIndex('t003_a_index');
        });
    }
}
