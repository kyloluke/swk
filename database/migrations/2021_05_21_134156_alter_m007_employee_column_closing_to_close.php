<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM007EmployeeColumnClosingToClose extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->renameColumn('closing_date_id', 'close_date_id');
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
            $table->renameColumn('close_date_id', 'closing_date_id');//<-記述
        });
    }
}
