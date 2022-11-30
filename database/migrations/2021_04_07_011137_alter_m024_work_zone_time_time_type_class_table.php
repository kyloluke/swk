<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM024WorkZoneTimeTimeTypeClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m024_work_zone_time', function (Blueprint $table) {
            $table->renameColumn('time_type_clas', 'time_type_class');
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
            $table->renameColumn('time_type_class', 'time_type_clas');
        });
    }
}
