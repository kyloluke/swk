<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeOverTimeReasonToT007OverTimeAchievementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t007_over_time_achievement', function (Blueprint $table) {
            $table->text('over_time_reason')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('t007_over_time_achievement')->where('over_time_reason', null)->update(['over_time_reason' => ""]);
        Schema::table('t007_over_time_achievement', function (Blueprint $table) {
            $table->text('over_time_reason')->nullable(false)->change();
        });
    }
}
