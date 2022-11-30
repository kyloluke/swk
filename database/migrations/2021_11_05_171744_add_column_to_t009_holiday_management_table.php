<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToT009HolidayManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t009_holiday_management', function (Blueprint $table) {
            $table->unsignedinteger('grant_holiday_half_days')->default(0)->after('grant_holiday_days');
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
            $table->dropColumn('grant_holiday_half_days');
        });
    }
}
