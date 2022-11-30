<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM03536agreementAggregateClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->dropColumn('36agreement_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->unsignedBigInteger('36agreement_id')->default(0);
        });
    }
}
