<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertM046GrantPaidLeaveType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m046_grant_paid_leave_type', function (Blueprint $table) {
            $table->unsignedinteger('manegement_target_class')->default(0)->after('grant_paid_leave_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m046_grant_paid_leave_type', function (Blueprint $table) {
            $table->dropColumn('manegement_target_class');
        });
    }
}
