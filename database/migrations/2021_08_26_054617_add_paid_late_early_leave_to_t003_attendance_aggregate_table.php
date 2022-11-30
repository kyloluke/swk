<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidLateEarlyLeaveToT003AttendanceAggregateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            //有休遅刻早退回数
            $table->unsignedInteger('paid_late_early_leave')->default(0)->after('remaining_paid_leave_time');
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
            //有休遅刻早退回数
            $table->dropColumn('paid_late_early_leave');
        });
    }
}
