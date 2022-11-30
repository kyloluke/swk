<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class T026GeneralSearchSaveAddColumnUnitType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t026_general_search_save', function (Blueprint $table) {
            $table->unsignedinteger('unit_type')->default(0)->after('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t026_general_search_save', function (Blueprint $table) {
            $table->dropColumn('unit_type');
        });
    }
}