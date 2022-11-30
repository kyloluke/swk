<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM039PreventionOverworkCheckUniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m039_prevention_overwork_check', function (Blueprint $table) {
            $table->unique(['prevention_overwork_check_id','violation_warning_type_id','violation_warning_id'],'prevention_overwork_check_id');
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
