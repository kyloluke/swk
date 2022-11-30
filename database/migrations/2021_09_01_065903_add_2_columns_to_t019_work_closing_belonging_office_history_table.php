<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2ColumnsToT019WorkClosingBelongingOfficeHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t019_work_closing_belonging_office_history', function (Blueprint $table) {
            $table->string('office_name',100)->nullable()->after('office_code');
            $table->string('office_short_name',100)->nullable()->after('office_name');
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
            $table->dropColumn('office_name');
            $table->dropColumn('office_short_name');
        });
    }
}
