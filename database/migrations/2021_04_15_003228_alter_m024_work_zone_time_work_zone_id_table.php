<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM024WorkZoneTimeWorkZoneIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m024_work_zone_time', function (Blueprint $table) {
            $table->renameColumn('work_zone_code', 'work_zone_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m024_work_zone_time', function (Blueprint $table) {
            $table->renameColumn('work_zone_id', 'work_zone_code');
        });
    }
}