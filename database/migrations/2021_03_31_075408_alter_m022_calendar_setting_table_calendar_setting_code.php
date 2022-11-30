<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM022CalendarSettingTableCalendarSettingCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m022_calendar_setting', function (Blueprint $table) {
            $table->dropColumn('calendar_setting_code');
            $table->unsignedBigInteger('calendar_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m022_calendar_setting', function (Blueprint $table) {
            $table->string('calendar_setting_code',20);
            $table->dropColumn('calendar_id');
        });
    }
}
