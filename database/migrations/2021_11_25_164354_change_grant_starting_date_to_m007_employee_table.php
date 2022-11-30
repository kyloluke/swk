<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeGrantStartingDateToM007EmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->integer('grant_starting_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('m007_employee')->where('grant_starting_date', null)->update(['grant_starting_date' => 0]);
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->integer('grant_starting_date')->nullable(false)->change();
        });
    }
}
