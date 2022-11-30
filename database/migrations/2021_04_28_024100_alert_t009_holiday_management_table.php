<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertT009HolidayManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t009_holiday_management', function (Blueprint $table) {
            $table->unsignedInteger('remaining_holiday_half_days')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t009_holiday_management', function (Blueprint $table) {
            $table->dropColumn('remaining_holiday_half_days');
        });
    }
}
