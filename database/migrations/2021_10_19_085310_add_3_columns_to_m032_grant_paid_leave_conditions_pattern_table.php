<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add3ColumnsToM032GrantPaidLeaveConditionsPatternTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('grant_rate');
            $table->integer('is_valid')->default(0)->after('company_id');
            $table->unsignedBigInteger('origin_grant_paid_leave_conditions_pattern_id')->nullable()->after('is_valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('is_valid');
            $table->dropColumn('origin_grant_paid_leave_conditions_pattern_id');
        });
    }
}
