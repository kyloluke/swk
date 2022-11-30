<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeT019WorkClosingBelongingOfficeHistoryTable2Columns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('t019_work_closing_belonging_office_history')->where('office_name', null)->update(['office_name' => ""]);
        DB::table('t019_work_closing_belonging_office_history')->where('office_short_name', null)->update(['office_short_name' => ""]);
        Schema::table('t019_work_closing_belonging_office_history', function (Blueprint $table) {
            $table->string('office_name',100)->nullable(false)->change();
            $table->string('office_short_name',100)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t019_work_closing_belonging_office_history', function (Blueprint $table) {
            $table->string('office_name',100)->nullable()->change();
            $table->string('office_short_name',100)->nullable()->change();
        });
    }
}
