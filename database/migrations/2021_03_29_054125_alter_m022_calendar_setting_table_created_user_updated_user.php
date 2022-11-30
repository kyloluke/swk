<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM022CalendarSettingTableCreatedUserUpdatedUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m022_calendar_setting', function (Blueprint $table) {
            $table->renameColumn('create_user', 'created_user');
            $table->renameColumn('update_user', 'updated_user');
            $table->string('calendar_setting_code',20);
            $table->unsignedinteger('calendar_setting_year')->default(0);
            $table->renameColumn('week_work_holiday_id', 'work_holiday_id');
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
            $table->renameColumn('created_user', 'create_user');
            $table->renameColumn('updated_user', 'update_user');
            $table->dropColumn('calendar_setting_code');
            $table->dropColumn('calendar_setting_year');
            $table->renameColumn('work_holiday_id', 'week_work_holiday_id');
        });
    }
}
