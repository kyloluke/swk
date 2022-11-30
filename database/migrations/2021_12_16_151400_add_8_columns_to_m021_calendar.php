<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add8ColumnsToM021Calendar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m021_calendar', function (Blueprint $table) {
            $table->unsignedInteger('is_holiday_rest')->default(0)->after('start_month');
            $table->unsignedInteger('monday_work_holiday_id')->default(0)->after('is_holiday_rest');
            $table->unsignedInteger('tuesday_work_holiday_id')->default(0)->after('monday_work_holiday_id');
            $table->unsignedInteger('wednesday_work_holiday_id')->default(0)->after('tuesday_work_holiday_id');
            $table->unsignedInteger('thursday_work_holiday_id')->default(0)->after('wednesday_work_holiday_id');
            $table->unsignedInteger('friday_work_holiday_id')->default(0)->after('thursday_work_holiday_id');
            $table->unsignedInteger('saturday_work_holiday_id')->default(0)->after('friday_work_holiday_id');
            $table->unsignedInteger('sunday_work_holiday_id')->default(0)->after('saturday_work_holiday_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m021_calendar', function (Blueprint $table) {
            $table->dropColumn('is_holiday_rest');
            $table->dropColumn('monday_work_holiday_id');
            $table->dropColumn('tuesday_work_holiday_id');
            $table->dropColumn('wednesday_work_holiday_id');
            $table->dropColumn('thursday_work_holiday_id');
            $table->dropColumn('friday_work_holiday_id');
            $table->dropColumn('saturday_work_holiday_id');
            $table->dropColumn('sunday_work_holiday_id');
        });
    }
}
