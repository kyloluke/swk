<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT003AttendanceAggregateAddColumnUnemployedTakeUnitClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            //有給取得半日数、有給取得時間数
            $table->unsignedInteger('acquired_paid_leave_half_days')->default(0)->after('acquired_paid_leave_days');
            $table->unsignedInteger('acquired_paid_leave_time')->default(0)->after('acquired_paid_leave_half_days');
            //有給残半日数、有給残時間数
            $table->unsignedInteger('remaining_paid_leave_half_days')->default(0)->after('remaining_paid_leave_days');
            $table->unsignedInteger('remaining_paid_leave_time')->default(0)->after('remaining_paid_leave_half_days');
            //保存休取得半日数、保存休取得時間数
            $table->unsignedInteger('accumulated_paid_leave_half_days')->default(0)->after('accumulated_paid_leave_days');
            $table->unsignedInteger('accumulated_paid_leave_time')->default(0)->after('accumulated_paid_leave_half_days');
            //保存休残半日数、保存休残時間数
            $table->unsignedInteger('unused_accumulated_paid_leave_half_days')->default(0)->after('unused_accumulated_paid_leave_days');
            $table->unsignedInteger('unused_accumulated_paid_leave_time')->default(0)->after('unused_accumulated_paid_leave_half_days');
            //特別休暇半日数、保存休残時間数(有給)
            $table->unsignedInteger('special_paid_holiday_half_days')->default(0)->after('special_paid_holiday_days');
            $table->unsignedInteger('special_paid_holiday_time')->default(0)->after('special_paid_holiday_half_days');
            //特別休暇半日数、保存休残時間数(無給)
            $table->unsignedInteger('special_non_paid_holiday_half_days')->default(0)->after('special_non_paid_holiday_days');
            $table->unsignedInteger('special_non_paid_holiday_time')->default(0)->after('special_non_paid_holiday_half_days');
            //欠勤半日数
            $table->unsignedInteger('absent_half_days')->default(0)->after('absent_days');
            
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
            $table->dropColumn('acquired_paid_leave_half_days');
            $table->dropColumn('acquired_paid_leave_time');
            $table->dropColumn('remaining_paid_leave_half_days');
            $table->dropColumn('remaining_paid_leave_time');
            $table->dropColumn('accumulated_paid_leave_half_days');
            $table->dropColumn('accumulated_paid_leave_time');
            $table->dropColumn('unused_accumulated_paid_leave_half_days');
            $table->dropColumn('unused_accumulated_paid_leave_time');
            $table->dropColumn('special_paid_holiday_half_days');
            $table->dropColumn('special_paid_holiday_time');
            $table->dropColumn('special_non_paid_holiday_half_days');
            $table->dropColumn('special_non_paid_holiday_time');
            $table->dropColumn('absent_half_days');
        });
    }
}
