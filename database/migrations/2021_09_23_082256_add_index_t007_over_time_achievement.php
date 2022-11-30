<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexT007OverTimeAchievement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t007_over_time_achievement', function (Blueprint $table) {
            $table->index(['employee_id', 'target_date', 'is_delete'], 't007_eti_index');
            $table->index(['attendance_information_id', 'is_delete'], 't007_ai_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t007_over_time_achievement', function (Blueprint $table) {
            $table->dropIndex('t007_eti_index');
            $table->dropIndex('t007_ai_index');
        });
    }
}
