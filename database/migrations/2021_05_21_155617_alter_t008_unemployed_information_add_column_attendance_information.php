<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT008UnemployedInformationAddColumnAttendanceInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t008_unemployed_information', function($table)
        {
            $table->unsignedBigInteger('attendance_information_id')->default(0)->after('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t008_unemployed_information', function($table)
        {
            $table->dropColumn('attendance_information_id');
        });
    }
}
