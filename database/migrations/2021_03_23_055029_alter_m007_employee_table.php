<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM007EmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->renameColumn('closing_date_class', 'closing_date_id');
            $table->unsignedBigInteger('closing_date_class')->default(0)->change();
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
            $table->renameColumn('closing_date_id', 'closing_date_class');
            $table->Integer('closing_date_id')->change();
        });
    }
}
