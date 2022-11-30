<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM023WorkZoneAddColumnWorkZoneAggrigationClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->unsignedInteger('work_zone_aggrigation_class')->default(1)->after('work_zone_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->dropColumn('work_zone_aggrigation_class');
        });
    }
}
