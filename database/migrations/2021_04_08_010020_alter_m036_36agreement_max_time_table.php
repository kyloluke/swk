<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM03636agreementMaxTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m036_36agreement_max_time', function (Blueprint $table) {
            $table->unsignedBigInteger('36agreement_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m036_36agreement_max_time', function (Blueprint $table) {
            $table->dropColumn('36agreement_id');
        });
    }
}
