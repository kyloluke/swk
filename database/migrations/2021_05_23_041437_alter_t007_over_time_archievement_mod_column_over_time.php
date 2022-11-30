<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT007OverTimeArchievementModColumnOverTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t007_over_time_achievement', function($table)
        {
            $table->unsignedBigInteger('over_time_class_id')->default(0)->after('target_date');
            $table->dropColumn('over_time_no');
            $table->dropColumn('over_time_class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t007_over_time_achievement', function($table)
        {
            $table->dropColumn('over_time_class_id');
            $table->unsignedBigInteger('over_time_class')->default(0)->after('target_date');
            $table->unsignedBigInteger('over_time_no')->default(0)->after('target_date');
        });
    }
}
