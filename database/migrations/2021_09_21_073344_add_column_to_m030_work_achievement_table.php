<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToM030WorkAchievementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m030_work_achievement', function (Blueprint $table) {
            $table->unsignedinteger('is_not_register')->default(0)->after('work_achievement_display_class');
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
            $table->dropColumn('is_not_register');
        });
    }
}
