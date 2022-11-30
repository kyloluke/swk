<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM031UnemployedAddColumnLateEarlyLeaveClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m031_unemployed', function(Blueprint $table) {
            $table->unsignedInteger('late_early_leave_class')->default(0)->after('unemployed_take_unit_class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m031_unemployed', function(Blueprint $table) {
            $table->dropColumn('late_early_leave_class');
        });
    }
}
