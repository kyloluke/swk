<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT011HolidayWorkerInformationColumnIntToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t011_holiday_worker_information', function (Blueprint $table) {
            $table->text('holiday_work_reason')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t011_holiday_worker_information', function (Blueprint $table) {
            //既に値が入っている場合はエラーとなるため、downメソッドを除外
            //$table->unsignedInteger('holiday_work_reason')->change();
        });
    }
}
