<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM043HolidayAddColumnGrantEnableClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m043_holiday', function (Blueprint $table) {
            $table->unsignedInteger('grant_enable_class')->default(0)->after('holiday_management_class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m043_holiday', function (Blueprint $table) {
            $table->dropColumn('grant_enable_class');
        });
    }
}
