<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM023WorkZoneAddColumnMidnight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->unsignedInteger('midnight_actual_work_time')->default(0)->after('actual_work_time');
            $table->unsignedInteger('midnight_break_time')->default(0)->after('break_time');
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
            $table->dropColumn('midnight_actual_work_time');
            $table->dropColumn('midnight_break_time');
        });
    }
}
