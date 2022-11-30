<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertM007EmployeeGrantPaidLeaveTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->unsignedBigInteger('grant_paid_leave_type_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->unsignedBigInteger('grant_paid_leave_type_id')->default(0)->change();
        });
    }
}
