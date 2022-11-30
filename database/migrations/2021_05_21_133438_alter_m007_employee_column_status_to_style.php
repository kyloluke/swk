<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM007EmployeeColumnStatusToStyle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->renameColumn('employment_status_id', 'employment_style_id');
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
            $table->renameColumn('employment_style_id', 'employment_status_id');//<-記述
        });
    }
}
