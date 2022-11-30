<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM007EmployeeAddFieldWorkColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->unsignedTinyInteger('field_work')->default(0)->after('detail_no');
            $table->unsignedTinyInteger('deviation_time_before_start_time_id')->default(1)->after('detail_no');
            $table->unsignedTinyInteger('deviation_time_after_end_time_id')->default(2)->after('detail_no');
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
            $table->dropColumn('field_work');
            $table->dropColumn('deviation_time_before_start_time_id');
            $table->dropColumn('deviation_time_after_end_time_id');
        });
    }
}
