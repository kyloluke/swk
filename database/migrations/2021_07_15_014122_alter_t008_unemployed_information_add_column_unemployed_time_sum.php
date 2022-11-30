<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT008UnemployedInformationAddColumnUnemployedTimeSum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t008_unemployed_information', function (Blueprint $table) {
            $table->unsignedInteger('unemployed_time')->default(0)->after('unemployed_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t008_unemployed_information', function (Blueprint $table) {
            $table->dropColumn('unemployed_time');
        });
    }
}
