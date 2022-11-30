<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM007EmployeeTableCreatedUserUpdatedUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->renameColumn('create_user', 'created_user');
            $table->renameColumn('update_user', 'updated_user');
            $table->renameColumn('paid_take_grant_days_pattern_id', 'paid_leave_grant_days_pattern_id');
            $table->renameColumn('first_year_paid_take_date', 'first_year_paid_leave_date');
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
            $table->renameColumn('created_user', 'create_user');
            $table->renameColumn('updated_user', 'update_user');
            $table->renameColumn('paid_leave_grant_days_pattern_id', 'paid_take_grant_days_pattern_id');
            $table->renameColumn('first_year_paid_leave_date', 'first_year_paid_take_date');
        });
    }
}
