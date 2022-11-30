<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM03636agreementMaxTimeUniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m036_36agreement_max_time', function (Blueprint $table) {
            $table->unique(['36agreement_max_time_id','36agreement_id','36agreement_aggregate_class_id','36agreement_aggregate_unit'],'36agreement_max_time_id');
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
