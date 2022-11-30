<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDisplaySwitchDateToM016CloseDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m016_close_date', function (Blueprint $table) {
            $table->unsignedInteger('display_switch_date')->default(0)->after('close_date_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m016_close_date', function (Blueprint $table) {
            $table->dropColumn('display_switch_date');
        });
    }
}
