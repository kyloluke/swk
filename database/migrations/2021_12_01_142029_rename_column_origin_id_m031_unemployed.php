<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnOriginIdM031Unemployed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m031_unemployed', function (Blueprint $table) {
            $table->renameColumn('origin_unemployed_id', 'origin_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m031_unemployed', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_unemployed_id');
        });
    }
}
