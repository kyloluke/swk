<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterUsersAddCompanyIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
           $table->unsignedBigInteger('company_id')->after('employee_id');
        });
        //employeeのcompany_idを転送
        $employees = DB::table('m007_employee')
            ->select('employee_id', 'company_id')
            ->get();
        foreach($employees as $employee)
        {
            DB::table('users')
                ->where('id', $employee->employee_id)
                ->update(['company_id' => $employee->company_id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
