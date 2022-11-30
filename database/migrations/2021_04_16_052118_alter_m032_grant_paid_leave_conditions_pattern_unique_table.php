<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM032GrantPaidLeaveConditionsPatternUniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->unique('grant_paid_leave_conditions_pattern_id','grant_paid_leave_conditions_pattern_id');
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
