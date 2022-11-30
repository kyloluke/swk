<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM03536agreementAggregateClassUniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->unique(['36agreement_aggregate_class_id','36agreement_aggregate_class'],'36agreement_aggregate_class_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
