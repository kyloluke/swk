<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnOriginId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m030_work_achievement', function (Blueprint $table) {
            $table->renameColumn('origin_work_achievement_id', 'origin_id');
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
            $table->renameColumn('origin_id', 'origin_work_achievement_id');
        });
    }
}
