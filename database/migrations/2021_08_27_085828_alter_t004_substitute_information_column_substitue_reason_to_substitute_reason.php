<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT004SubstituteInformationColumnSubstitueReasonToSubstituteReason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t004_substitute_information', function (Blueprint $table) {
            $table->renameColumn('substitue_reason', 'substitute_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t004_substitute_information', function (Blueprint $table) {
            $table->renameColumn('substitute_reason', 'substitue_reason');
        });
    }
}
