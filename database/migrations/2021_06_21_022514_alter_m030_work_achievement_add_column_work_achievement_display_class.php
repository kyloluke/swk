<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM030WorkAchievementAddColumnWorkAchievementDisplayClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m030_work_achievement', function (Blueprint $table) {
            $table->unsignedInteger('work_achievement_display_class')->default(1)->after('work_achievement_short_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m030_work_achievement', function (Blueprint $table) {
            $table->dropColumn('work_achievement_display_class');
        });
    }
}
