<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHolidayIdToT009HolidayManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t009_holiday_management', function (Blueprint $table) {
            $table->unsignedBigInteger('holiday_id')->default(0);
            $table->dropColumn('holiday_management_class');
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
            $table->unsignedInteger('holiday_management_class')->default(0);
            $table->dropColumn('holiday_id');
        });
    }
}
