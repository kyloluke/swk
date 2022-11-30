<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertT027GeneralSearchSaveParamAddTypeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t027_general_search_save_param', function (Blueprint $table) {
            $table->string('type',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t027_general_search_save_param', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
